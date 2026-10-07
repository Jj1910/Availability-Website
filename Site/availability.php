<?php
require_once 'includes/config_session.inc.php';
require_once 'includes/bootstrap.inc.php';

if (!isset($_SESSION["user_id"]) || !$_SESSION["is_admin"]) {
    header("Location: ./index.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/reset.css">
    <link rel="stylesheet" href="css/main.css">
    <title>Availability</title>
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
        <?= csrf_field() ?>
        <button>Logout</button>
    </form>
</body>

</html>