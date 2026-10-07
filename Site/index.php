<?php
require_once 'includes/config_session.inc.php';

if (isset($_SESSION["user_id"])) {
    header($_SESSION["is_admin"] ? "Location: ./dashboard.php" : "Location: ./availabilityform.php");
    exit;
}

$error = $_GET["error"] ?? "";
$msg = $_GET["msg"] ?? "";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/reset.css">
    <link rel="stylesheet" href="css/main.css">
    <title>Availability Site</title>
</head>

<body>
    <h1>Login</h1>

    <?php if ($msg === "loggedout"): ?>
        <div class="alert alert-success">You have been logged out.</div>
    <?php endif; ?>
    <?php if ($error === "inputempty"): ?>
        <div class="alert alert-error">Please fill out all fields!</div>
    <?php endif; ?>
    <?php if ($error === "invalidlogon"): ?>
        <div class="alert alert-error">Invalid username or password!</div>
    <?php endif; ?>
    <?php if ($error === "toomany"): ?>
        <div class="alert alert-error">Too many failed attempts. Please wait a few minutes and try again.</div>
    <?php endif; ?>
    <?php if ($error === "csrf"): ?>
        <div class="alert alert-error">Your form session expired. Please go back and try again.</div>
    <?php endif; ?>

    <form action="includes/login.inc.php" method="post">
        <?= csrf_field() ?>
        <input required type="text" name="username" placeholder="Username" autocomplete="username">
        <input required type="password" name="pwd" placeholder="Password" autocomplete="current-password">
        <button>Login</button>
    </form>
</body>

</html>