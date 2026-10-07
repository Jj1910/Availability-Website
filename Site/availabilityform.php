<?php
require_once 'includes/config_session.inc.php';
require_once 'includes/bootstrap.inc.php';

if (!isset($_SESSION["user_id"])) {
    header("Location: ./index.php");
    exit;
}
if ($_SESSION["is_admin"]) {
    header("Location: ./dashboard.php");
    exit;
}

/* Pre-fill the form with the user's currently stored times. */
require_once 'Classes/Availability_Model.php';
require_once 'Classes/Availability_Contr.php';
$current = (new AvailabilityContr((int)$_SESSION["user_id"], []))->currentTimes();

$value = static function (string $key) use ($current): string {
    $v = $current[$key] ?? '';
    return is_string($v) ? $v : '';
};

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
    <title>Availability Form</title>
</head>

<body>
    <h1>
        You are logged in as <?= htmlspecialchars($_SESSION["user_username"] ?? "", ENT_QUOTES) ?> and are not an admin!
    </h1>

    <?php if ($msg === "updated"): ?>
        <div class="alert alert-success">Availability saved!</div>
    <?php endif; ?>
    <?php if ($error === "invalidtime"): ?>
        <div class="alert alert-error">One or more times are invalid. Use 24-hour HH:MM, start before end. Leave a day blank if you're unavailable.</div>
    <?php endif; ?>
    <?php if ($error === "csrf"): ?>
        <div class="alert alert-error">Your form session expired. Please try again.</div>
    <?php endif; ?>

    <h2>Submit the Times You're Available:</h2>
    <div class="availabilityForm">
        <form action="includes/availability.inc.php" method="post">
            <?= csrf_field() ?>

            <label for="monday-start">Monday:</label>
            Start Time: <input type="time" name="mondayStartTime" id="monday-start" value="<?= htmlspecialchars($value("monday_start")) ?>">
            End Time: <input type="time" name="mondayEndTime" id="monday-end" value="<?= htmlspecialchars($value("monday_end")) ?>">

            <label for="tuesday-start">Tuesday:</label>
            Start Time: <input type="time" name="tuesdayStartTime" id="tuesday-start" value="<?= htmlspecialchars($value("tuesday_start")) ?>">
            End Time: <input type="time" name="tuesdayEndTime" id="tuesday-end" value="<?= htmlspecialchars($value("tuesday_end")) ?>">

            <label for="wednesday-start">Wednesday:</label>
            Start Time: <input type="time" name="wednesdayStartTime" id="wednesday-start" value="<?= htmlspecialchars($value("wednesday_start")) ?>">
            End Time: <input type="time" name="wednesdayEndTime" id="wednesday-end" value="<?= htmlspecialchars($value("wednesday_end")) ?>">

            <label for="thursday-start">Thursday:</label>
            Start Time: <input type="time" name="thursdayStartTime" id="thursday-start" value="<?= htmlspecialchars($value("thursday_start")) ?>">
            End Time: <input type="time" name="thursdayEndTime" id="thursday-end" value="<?= htmlspecialchars($value("thursday_end")) ?>">

            <label for="friday-start">Friday:</label>
            Start Time: <input type="time" name="fridayStartTime" id="friday-start" value="<?= htmlspecialchars($value("friday_start")) ?>">
            End Time: <input type="time" name="fridayEndTime" id="friday-end" value="<?= htmlspecialchars($value("friday_end")) ?>">

            <button>Submit Availability</button>
        </form>
    </div>

    <form action="includes/logout.inc.php" method="post">
        <?= csrf_field() ?>
        <button>Logout</button>
    </form>
</body>

</html>