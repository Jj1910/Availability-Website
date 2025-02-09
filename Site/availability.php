<?php
require_once 'includes/config_session.inc.php';

if(!$_SESSION["is_admin"]) {
    header("Location: ./index.php");
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

    <h1>Availability</h1>

    <?php
    require_once 'includes/show_table.inc.php';
    ?>

    <form action="./dashboard.php">
        <button>Back</button>
    </form>

    <form action="includes/logout.inc.php" method="post">
        <button>Logout</button>
    </form>
</body>

</html>