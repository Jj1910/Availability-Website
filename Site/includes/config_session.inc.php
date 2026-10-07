<?php

declare(strict_types=1);

/*
 * Session setup + CSRF helpers.
 * Include this file (via require_once) before touching $_SESSION or
 * rendering any form.
 */

function regenerate_session_id(): void {
    session_regenerate_id(true);
    $_SESSION['last_regeneration'] = time();
}

/** Return (and lazily create) this session's CSRF token. */
function csrf_token(): string {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }

    return $_SESSION['csrf_token'];
}

/** Hidden input to embed in every POST form. */
function csrf_field(): string {
    return '<input type="hidden" name="csrf_token" value="' 
        . htmlspecialchars(csrf_token(), ENT_QUOTES) . '">';
}

/** Constant-time check of a submitted token against this session. */
function csrf_verify(?string $token): bool {
    return is_string($token)
        && isset($_SESSION['csrf_token'])
        && hash_equals($_SESSION['csrf_token'], $token);
}

/* Only mark the cookie secure when the site is actually served over HTTPS. */
$isHttps = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
    || (($_SERVER['HTTP_X_FORWARDED_PROTO'] ?? '') === 'https');

if (session_status() === PHP_SESSION_NONE) {
    ini_set('session.use_strict_mode', '1');
    ini_set('session.use_only_cookies', '1');

    session_name('AVAIL_SESSID');
    session_set_cookie_params([
        'lifetime' => 0,          /* session cookie: gone when browser closes */
        'path' => '/',
        'secure' => $isHttps,
        'httponly' => true,
        'samesite' => 'Lax',      /* blocks the cookie in cross-site POSTs */
    ]);
    session_start();
}

/* Re-issue the session id at most every 30 minutes (session fixation). */
if (!isset($_SESSION['last_regeneration'])) {
    regenerate_session_id();
} elseif (time() - (int)$_SESSION['last_regeneration'] >= 60 * 30) {
    regenerate_session_id();
}
