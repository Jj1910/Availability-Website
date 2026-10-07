<?php

require_once 'config_session.inc.php';
require_once 'bootstrap.inc.php';

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: ../index.php");
    exit;
}

if (!csrf_verify($_POST["csrf_token"] ?? null)) {
    header("Location: ../index.php?error=csrf");
    exit;
}

$username = trim((string)($_POST["username"] ?? ""));
$pwd = (string)($_POST["pwd"] ?? "");
$ip = (string)($_SERVER["REMOTE_ADDR"] ?? "0.0.0.0");

require_once '../Classes/Dbh.php';
require_once '../Classes/SignIn_Model.php';
require_once '../Classes/SignIn_Contr.php';

$signInContr = new SignInContr($username, $pwd, $ip);
$signInContr->signInUser();

exit;