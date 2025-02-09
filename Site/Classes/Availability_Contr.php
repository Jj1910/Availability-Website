<?php

declare(strict_types=1);

Class AvailabilityContr extends AvailabilityModel {
    private $userId;
    private $mondayStartTime;
    private $mondayEndTime;
    private $tuesdayStartTime;
    private $tuesdayEndTime;
    private $wednesdayStartTime;
    private $wednesdayEndTime;
    private $thursdayStartTime;
    private $thursdayEndTime;
    private $fridayStartTime;
    private $fridayEndTime;

    public function __construct(int $userId, string $mondayStartTime, string $mondayEndTime, string $tuesdayStartTime, string $tuesdayEndTime, string $wednesdayStartTime, string $wednesdayEndTime, string $thursdayStartTime, string $thursdayEndTime, string $fridayStartTime, string $fridayEndTime) {
        $this->userId = $userId;

        $this->mondayStartTime = $mondayStartTime;
        $this->mondayEndTime = $mondayEndTime;

        $this->tuesdayStartTime = $tuesdayStartTime;
        $this->tuesdayEndTime = $tuesdayEndTime;

        $this->wednesdayStartTime = $wednesdayStartTime;
        $this->wednesdayEndTime = $wednesdayEndTime;

        $this->thursdayStartTime = $thursdayStartTime;
        $this->thursdayEndTime = $thursdayEndTime;

        $this->fridayStartTime = $fridayStartTime;
        $this->fridayEndTime = $fridayEndTime;
    }

    public function submitAvailability() {
        parent::UpdateAvailability($this->userId, $this->mondayStartTime, $this->mondayEndTime, $this->tuesdayStartTime, $this->tuesdayEndTime, $this->wednesdayStartTime, $this->wednesdayEndTime, $this->thursdayStartTime, $this->thursdayEndTime, $this->fridayStartTime, $this->fridayEndTime);
    }
}