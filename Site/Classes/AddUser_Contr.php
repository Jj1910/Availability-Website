<?php

declare(strict_types=1);

Class AddUserContr extends AddUserModel {
    private $username;
    private $pwd;
    private $email;
    private $isAdmin;
    
    public function __construct(string $username, string $pwd, string $email, bool $isAdmin) {
        $this->username = $username;
        $this->pwd = $pwd;
        $this->email = $email;
        $this->isAdmin = $isAdmin;
    }

    private function is_input_empty() {
        if (empty($this->username) || empty($this->pwd) || empty($this->email)){
            return true;
        } else {
            return false;
        }
    }

    private function is_username_taken(string $username) {
        if (parent::get_username($username)){
            return true;
        } else {
            return false;
        }
    }

    private function is_email_taken(string $email) {
        if (parent::get_email($email)){
            return true;
        } else {
            return false;
        }
    }

    private function is_email_invalid(string $email) {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)){
            return true;
        } else {
            return false;
        }
    }

    public function AddUser(){
        if ($this->is_input_empty()){
            header("Location: ../dashboard.php?error=inputempty");
            die();
        }
        
        $result = parent::get_username($this->username);

        if ($result &&  $this->is_username_taken($this->username)) {
            header("Location: ../dashboard.php?error=usernametaken");
            die();
        }

        if ($this->is_email_invalid($this->email)) {
            header("Location: ../dashboard.php?error=emailinvalid");
            die();
        }

        $result = parent::get_email($this->email);

        if ($result && $this->is_email_taken($this->email)) {
            header("Location: ../dashboard.php?error=emailtaken");
            die();
        }

        parent::AddUserToDB($this->username, $this->pwd, $this->email, $this->isAdmin);

        header("Location: ../dashboard.php?error=false");
        die();
    }
}