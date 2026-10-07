<?php

require_once 'config_session.inc.php';
require_once 'bootstrap.inc.php';

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: ../dashboard.php");
    exit;
}

/* Only admins may create users. */
if (!isset($_SESSION["user_id"]) || !$_SESSION["is_admin"]) {
    header("Location: ../index.php");
    exit;
}

if (!csrf_verify($_POST["csrf_token"] ?? null)) {
    header("Location: ../dashboard.php?error=csrf");
    exit;
}

$username = trim((string)($_POST["username"] ?? ""));
$pwd = (string)($_POST["pwd"] ?? "");
$email = trim((string)($_POST["email"] ?? ""));
$isAdmin = filter_var($_POST["isAdmin"] ?? "0", FILTER_VALIDATE_BOOLEAN);

require_once '../Classes/Dbh.php';
require_once '../Classes/AddUser_Model.php';
require_once '../Classes/AddUser_Contr.php';

$addUserContr = new AddUserContr($username, $pwd, $email, $isAdmin);
$addUserContr->addUser();

exit;