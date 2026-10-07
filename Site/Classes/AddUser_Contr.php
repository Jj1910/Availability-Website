<?php

declare(strict_types=1);

class AddUserContr extends AddUserModel {
    private string $username;
    private string $pwd;
    private string $email;
    private bool $isAdmin;

    public function __construct(string $username, string $pwd, string $email, bool $isAdmin) {
        $this->username = $username;
        $this->pwd = $pwd;
        $this->email = $email;
        $this->isAdmin = $isAdmin;
    }

    private function is_input_empty(): bool {
        return $this->username === "" || $this->pwd === "" || $this->email === "";
    }

    private function is_username_bad(): bool {
        return strlen($this->username) > 50;
    }

    private function is_password_short(): bool {
        return strlen($this->pwd) < 8;
    }

    private function is_username_taken(string $username): bool {
        return parent::get_username($username) !== [];
    }

    private function is_email_taken(string $email): bool {
        return parent::get_email($email) !== [];
    }

    private function is_email_invalid(string $email): bool {
        return !filter_var($email, FILTER_VALIDATE_EMAIL);
    }

    public function addUser(): void {
        if ($this->is_input_empty()) {
            header("Location: ../dashboard.php?error=inputempty");
            exit;
        }

        if ($this->is_username_bad()) {
            header("Location: ../dashboard.php?error=badusername");
            exit;
        }

        if ($this->is_password_short()) {
            header("Location: ../dashboard.php?error=shortpassword");
            exit;
        }

        if ($this->is_username_taken($this->username)) {
            header("Location: ../dashboard.php?error=usernametaken");
            exit;
        }

        if ($this->is_email_invalid($this->email)) {
            header("Location: ../dashboard.php?error=emailinvalid");
            exit;
        }

        if ($this->is_email_taken($this->email)) {
            header("Location: ../dashboard.php?error=emailtaken");
            exit;
        }

        parent::addUserToDB($this->username, $this->pwd, $this->email, $this->isAdmin);

        header("Location: ../dashboard.php?msg=usercreated");
        exit;
    }
}