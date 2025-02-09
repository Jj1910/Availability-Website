<?php

declare(strict_types=1);

class AvailabilityModel extends Dbh {

    protected function UpdateAvailability(int $userId, string $mondayStartTime, string $mondayEndTime, string $tuesdayStartTime, string $tuesdayEndTime, string $wednesdayStartTime, string $wednesdayEndTime, string $thursdayStartTime, string $thursdayEndTime, string $fridayStartTime, string $fridayEndTime) {
        $query = "UPDATE availability SET mondayStartTime=:mondayStartTime, mondayEndTime=:mondayEndTime, tuesdayStartTime=:tuesdayStartTime, tuesdayEndTime=:tuesdayEndTime, wednesdayStartTime=:wednesdayStartTime, wednesdayEndTime=:wednesdayEndTime, thursdayStartTime=:thursdayStartTime, thursdayEndTime=:thursdayEndTime, fridayStartTime=:fridayStartTime, fridayEndTime=:fridayEndTime WHERE user_id=:userId";

        $stmt = parent::connect()->prepare($query);
        $stmt->bindParam(":userId", $userId);

        $stmt->bindParam(":mondayStartTime", $mondayStartTime);
        $stmt->bindParam(":mondayEndTime", $mondayEndTime);

        $stmt->bindParam(":tuesdayStartTime", $tuesdayStartTime);
        $stmt->bindParam(":tuesdayEndTime", $tuesdayEndTime);

        $stmt->bindParam(":wednesdayStartTime", $wednesdayStartTime);
        $stmt->bindParam(":wednesdayEndTime", $wednesdayEndTime);

        $stmt->bindParam(":thursdayStartTime", $thursdayStartTime);
        $stmt->bindParam(":thursdayEndTime", $thursdayEndTime);

        $stmt->bindParam(":fridayStartTime", $fridayStartTime);
        $stmt->bindParam(":fridayEndTime", $fridayEndTime);

        $stmt->execute();
    }
}