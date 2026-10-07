<?php

require_once 'config_session.inc.php';
require_once 'bootstrap.inc.php';

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: ../availabilityform.php");
    exit;
}

if (!isset($_SESSION["user_id"])) {
    header("Location: ../index.php");
    exit;
}

if (!csrf_verify($_POST["csrf_token"] ?? null)) {
    header("Location: ../availabilityform.php?error=csrf");
    exit;
}

/* Validate every time field: HH:MM (24h), optional (blank = unavailable),
 * and start must come before end. */
$days = ["monday", "tuesday", "wednesday", "thursday", "friday"];
$times = [];
$invalid = 0;

foreach ($days as $day) {
    $start = trim((string)($_POST[$day . "StartTime"] ?? ""));
    $end = trim((string)($_POST[$day . "EndTime"] ?? ""));

    foreach ([$start, $end] as $value) {
        if ($value !== "" && !preg_match('/^([01]\d|2[0-3]):[0-5]\d$/', $value)) {
            $invalid = 1;
        }
    }

    if ($start !== "" && $end !== "" && $start >= $end) {
        $invalid = 1;
    }

    $times[$day . "_start"] = $start === "" ? null : $start;
    $times[$day . "_end"] = $end === "" ? null : $end;
}

if ($invalid) {
    header("Location: ../availabilityform.php?error=invalidtime");
    exit;
}

require_once '../Classes/Dbh.php';
require_once '../Classes/Availability_Model.php';
require_once '../Classes/Availability_Contr.php';

$availabilityContr = new AvailabilityContr((int)$_SESSION["user_id"], $times);
$availabilityContr->submitAvailability();

header("Location: ../availabilityform.php?msg=updated");
exit;