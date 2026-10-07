<?php
require_once 'includes/config_session.inc.php';
require_once 'includes/bootstrap.inc.php';

if (!isset($_SESSION["user_id"])) {
    header("Location: ./index.php");
    exit;
}
if (!$_SESSION["is_admin"]) {
    header("Location: ./availabilityform.php");
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
    <title>Availability Site Dashboard</title>
</head>

<body>
    <h1>
        You are logged in as <?= htmlspecialchars($_SESSION["user_username"] ?? "", ENT_QUOTES) ?> and are an admin!
    </h1>

    <?php if ($msg === "usercreated"): ?>
        <div class="alert alert-success">User created successfully!</div>
    <?php endif; ?>
    <?php if ($error === "usernametaken"): ?>
        <div class="alert alert-error">Username is already taken!</div>
    <?php endif; ?>
    <?php if ($error === "emailinvalid"): ?>
        <div class="alert alert-error">Please enter a valid email!</div>
    <?php endif; ?>
    <?php if ($error === "emailtaken"): ?>
        <div class="alert alert-error">Email is already taken!</div>
    <?php endif; ?>
    <?php if ($error === "inputempty"): ?>
        <div class="alert alert-error">Please fill out all fields!</div>
    <?php endif; ?>
    <?php if ($error === "badusername"): ?>
        <div class="alert alert-error">Username must be 1-50 characters!</div>
    <?php endif; ?>
    <?php if ($error === "shortpassword"): ?>
        <div class="alert alert-error">Password must be at least 8 characters!</div>
    <?php endif; ?>
    <?php if ($error === "csrf"): ?>
        <div class="alert alert-error">Your form session expired. Please go back and try again.</div>
    <?php endif; ?>

    <h2>Add New Users!</h2>

    <form action="includes/adduser.inc.php" method="post">
        <?= csrf_field() ?>
        <input required type="text" name="username" placeholder="Username" maxlength="50" autocomplete="off">
        <input required type="password" name="pwd" placeholder="Password (min 8 characters)" minlength="8" autocomplete="new-password">
        <input required type="email" name="email" placeholder="E-Mail" autocomplete="off">
        <div class="checkbox-container">
            <input type="hidden" name="isAdmin" value="0">
            <input type="checkbox" id="isAdmin" name="isAdmin" value="1">
            <label for="isAdmin">Is User an Admin?</label>
        </div>
        <button>Add User</button>
    </form>

    <form action="./availability.php">
        <button>Availability</button>
    </form>

    <form action="includes/logout.inc.php" method="post">
        <?= csrf_field() ?>
        <button>Logout</button>
    </form>
</body>

</html>