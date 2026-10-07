<?php

require_once 'config_session.inc.php';

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: ../index.php");
    exit;
}

if (!csrf_verify($_POST["csrf_token"] ?? null)) {
    header("Location: ../index.php");
    exit;
}

$_SESSION = [];

if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), "", [
        "expires" => time() - 42000,
        "path" => $params["path"],
        "domain" => $params["domain"],
        "secure" => $params["secure"],
        "httponly" => $params["httponly"],
        "samesite" => $params["samesite"] ?? "Lax",
    ]);
}

session_destroy();

header("Location: ../index.php?msg=loggedout");
exit;