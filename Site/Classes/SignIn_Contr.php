<?php

declare(strict_types=1);

class SignInContr extends SignInModel {
    private string $username;
    private string $pwd;
    private string $ip;

    public function __construct(string $username, string $pwd, string $ip) {
        $this->username = $username;
        $this->pwd = $pwd;
        $this->ip = $ip;
    }

    private function is_input_empty(): bool {
        return $this->username === "" || $this->pwd === "";
    }

    private function is_password_wrong(string $pwd, string $hashedPwd): bool {
        return !password_verify($pwd, $hashedPwd);
    }

    public function signInUser(): void {
        if ($this->is_input_empty()) {
            header("Location: ../index.php?error=inputempty");
            exit;
        }

        if (parent::isThrottled($this->ip)) {
            header("Location: ../index.php?error=toomany");
            exit;
        }

        $result = parent::getUser($this->username);

        if ($result === [] || $this->is_password_wrong($this->pwd, (string)$result["pwd"])) {
            parent::recordFailedAttempt($this->ip);
            header("Location: ../index.php?error=invalidlogon");
            exit;
        }

        parent::clearFailedAttempts($this->ip);

        /* Fresh session id + CSRF token for the logged-in state. */
        regenerate_session_id();
        unset($_SESSION['csrf_token']);

        $_SESSION["user_id"] = (int)$result["id"];
        $_SESSION["user_username"] = (string)$result["username"];
        $_SESSION["is_admin"] = (bool)$result["is_admin"];

        header("Location: ../index.php?msg=loggedin");
        exit;
    }
}
