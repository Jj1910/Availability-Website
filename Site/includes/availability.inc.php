<?php

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    
    $mondayStartTime = $_POST["mondayStartTime"];
    $mondayEndTime = $_POST["mondayEndTime"];

    $tuesdayStartTime = $_POST["tuesdayStartTime"];
    $tuesdayEndTime = $_POST["tuesdayEndTime"];

    $wednesdayStartTime = $_POST["wednesdayStartTime"];
    $wednesdayEndTime = $_POST["wednesdayEndTime"];

    $thursdayStartTime = $_POST["thursdayStartTime"];
    $thursdayEndTime = $_POST["thursdayEndTime"];

    $fridayStartTime = $_POST["fridayStartTime"];
    $fridayEndTime = $_POST["fridayEndTime"];

    require_once '../Classes/Dbh.php';
    require_once '../Classes/Availability_Model.php';
    require_once '../Classes/Availability_Contr.php';
    require_once '../includes/config_session.inc.php';

    $AvailabilityContr = new AvailabilityContr($_SESSION["user_id"], $mondayStartTime, $mondayEndTime, $tuesdayStartTime, $tuesdayEndTime, $wednesdayStartTime, $wednesdayEndTime, $thursdayStartTime, $thursdayEndTime, $fridayStartTime, $fridayEndTime);

    $AvailabilityContr->submitAvailability();

    header("Location: ../availabilityform.php");
    $die();

} else {
    header("Location: ../availabilityform.php");
    die();
}