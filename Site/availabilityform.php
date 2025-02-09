<?php
require_once 'includes/config_session.inc.php';

if(!isset($_SESSION["user_id"])) {
    header("Location: ./index.php");
    die();
}
if($_SESSION["is_admin"]) {
    header("Location: ./dashboard.php");
    die();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/reset.css">
    <link rel="stylesheet" href="css/main.css">
    <title>Availability Form</title>
</head>

<body>
    <h1>
        <?php
        echo "You are logged in as " . htmlspecialchars($_SESSION["user_username"]) . " and are not an admin!";
        ?>
    </h1>

    <h2>Submit the Times You're Available:</h2>
    <div class="availabilityForm">
        <form action="includes/availability.inc.php" method="post">
            <label for="monday">Monday:</label>
            Start Time: <input type="time" name="mondayStartTime" id="monday">
            End Time: <input type="time" name="mondayEndTime" id="monday">
            
            <label for="tuesday">Tuesday:</label>
            Start Time: <input type="time" name="tuesdayStartTime" id="tuesday">
            End Time: <input type="time" name="tuesdayEndTime" id="tuesday">
            
            <label for="wednesday">Wednesday:</label>
            Start Time: <input type="time" name="wednesdayStartTime" id="wednesday">
            End Time: <input type="time" name="wednesdayEndTime" id="wednesday">
            
            <label for="thursday">Thursday:</label>
            Start Time: <input type="time" name="thursdayStartTime" id="thursday">
            End Time: <input type="time" name="thursdayEndTime" id="thursday">
            
            <label for="friday">Friday:</label>
            Start Time: <input type="time" name="fridayStartTime" id="friday">
            End Time: <input type="time" name="fridayEndTime" id="friday">
            
            <button>Submit Availability</button>
        </form>
    </div>

    <br>

    <form action="includes/logout.inc.php" method="post">
        <button>Logout</button>
    </form>
</body>

</html>