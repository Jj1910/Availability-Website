<?php

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    
    $username = $_POST["username"];
    $pwd = $_POST["pwd"];
    $email = $_POST["email"];
    $isAdmin = $_POST["isAdmin"];

    require_once '../Classes/Dbh.php';
    require_once '../Classes/AddUser_Model.php';
    require_once '../Classes/AddUser_Contr.php';
    
    $AddUserContr = new AddUserContr($username, $pwd, $email, $isAdmin);

    $AddUserContr->AddUser();

    $die();

} else {
    header("Location: ../dashboard.php");
    die();
}