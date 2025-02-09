<?php
require_once 'includes/config_session.inc.php';

if(isset($_SESSION["user_id"])) {
    if($_SESSION["is_admin"]){
        header("Location: ./dashboard.php");
        die();
    } else {
        header("Location: ./availabilityform.php");
        die();
    }
} 

if ($_GET){
    if ($_GET["error"] === "inputempty"){
        echo "<p>Please Fill Out All Fields!</p>";
    } else if ($_GET["error"] === "invalidlogon") {
        echo "<p>Invalid Credentials!</p>";
    }
}
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
    <br>
    <form action="includes/login.inc.php" method="post">
        <input required type="text" name="username" placeholder="Username">
        <input required type="password" name="pwd" placeholder="Password">
        <button>Login</button>
    </form>
</body>

</html>