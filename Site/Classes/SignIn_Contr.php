<?php

declare(strict_types=1);

Class SignInContr extends SignInModel {
    private $username;
    private $pwd;
    
    public function __construct(string $username, string $pwd) {
        $this->username = $username;
        $this->pwd = $pwd;
    }

    private function is_input_empty() {
        if (empty($this->username) || empty($this->pwd)){
            return true;
        } else {
            return false;
        }
    }

    private function is_password_wrong(string $pwd, string $hashedPwd) {
        if (!password_verify($pwd, $hashedPwd)){
            return true;
        } else {
            return false;
        }
    }

    public function signInUser(){
        if ($this->is_input_empty()){
            header("Location: ../index.php?error=inputempty");
            die();
        }
        
        $result = parent::getUser($this->username);

        if(!$result || $this->is_password_wrong($this->pwd, $result["pwd"])) {
            header("Location: ../index.php?error=invalidlogon");
            die();
        } 

        #$newSessionId = session_create_id();
        #$sessionId = $newSessionId . "_" . $result["id"];
        #session_id($sessionId);

        $_SESSION["user_id"] = $result["id"];
        $_SESSION["user_username"] = htmlspecialchars($result["username"]);
        $_SESSION["is_admin"] = $result["is_admin"];

        $_SESSION["last_regeneration"] = time();

        header("Location: ../index.php");
        die();
    }
}