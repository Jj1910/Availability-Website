<?php
require_once 'includes/config_session.inc.php';

if(!isset($_SESSION["user_id"])) {
    header("Location: ./index.php");
    die();
}
if(!$_SESSION["is_admin"]) {
    header("Location: ./availabilityform.php");
    die();
}
if($_GET){
    if($_GET["error"] === "usernametaken") {
        echo "<h1>Username is Already Taken!</h1>";
    }
    if($_GET["error"] === "emailinvalid") {
        echo "<h1>Please Enter a Valid Email!</h1>";
    }
    if($_GET["error"] === "emailtaken") {
        echo "<h1>Email is Already Taken!</h1>";
    }
    if($_GET["error"] === "inputempty") {
        echo "<h1>Please Fill out all Fields!</h1>";
    }
    if($_GET["error"] === "false") {
        echo "<h1>User Successfully Created!</h1>";
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
    <title>Availability Site Dashboard</title>
</head>

<body>
    <h1>
        <?php
        echo "You are logged in as " . htmlspecialchars($_SESSION["user_username"]) . " and are an admin!";
        ?>
    </h1>

    <h2>Add New Users!</h2>

    <form action="includes/adduser.inc.php" method="post">
        <input required type="text" name="username" placeholder="Username">
        <input required type="password" name="pwd" placeholder="Password">
        <input required type="email" name="email" placeholder="E-Mail">
        <input type="hidden" name="isAdmin" value="0">
        <div class="checkbox-container">
            <input type="checkbox" id="isAdmin" name="isAdmin" value="1">
            <label for="isAdmin">Is User an Admin?</label>
        </div>
        <br><br>
        <button>Add User</button>
    </form>

    <br>
    <form action="./availability.php">
        <button>Availability</button> 
    </form>
    <br>

    <form action="includes/logout.inc.php" method="post">
        <button>Logout</button>
    </form>
</body>

</html>