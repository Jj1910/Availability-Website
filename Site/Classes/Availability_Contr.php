<?php

declare(strict_types=1);

class AvailabilityContr extends AvailabilityModel {
    private int $userId;

    /** @var array<string, string|null> e.g. monday_start => "09:00" */
    private array $times;

    public function __construct(int $userId, array $times) {
        $this->userId = $userId;
        $this->times = $times;
    }

    public function submitAvailability(): void {
        parent::updateAvailability($this->userId, $this->times);
    }

    /** Stored times for pre-filling the form (empty array if none yet). */
    public function currentTimes(): array {
        $row = parent::getAvailability($this->userId);

        if ($row === []) {
            return [];
        }

        $times = [];
        foreach (["monday", "tuesday", "wednesday", "thursday", "friday"] as $day) {
            $times[$day . "_start"] = $row[$day . "StartTime"] ?? null;
            $times[$day . "_end"] = $row[$day . "EndTime"] ?? null;
        }

        return $times;
    }
}