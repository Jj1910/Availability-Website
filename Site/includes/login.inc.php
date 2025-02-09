<?php

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    
    $username = $_POST["username"];
    $pwd = $_POST["pwd"];

    require_once '../Classes/Dbh.php';
    require_once '../Classes/SignIn_Model.php';
    require_once '../Classes/SignIn_Contr.php';
    require_once 'config_session.inc.php';
    
    $signInContr = new SignInContr($username, $pwd);

    $signInContr->signInUser();

    $die();

} else {
    header("Location: ../index.php");
    die();
}