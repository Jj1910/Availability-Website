<?php

declare(strict_types=1);

class AvailabilityModel extends Dbh {

    private const DAYS = ["monday", "tuesday", "wednesday", "thursday", "friday"];

    /**
     * $times: [monday_start, monday_end, tuesday_start, ..., friday_end]
     * (null = that slot is left blank in the form)
     */
    protected function updateAvailability(int $userId, array $times): void {
        $setClauses = [];
        $params = ["userId" => $userId];

        foreach (self::DAYS as $day) {
            $setClauses[] = $day . "StartTime = :" . $day . "StartTime";
            $setClauses[] = $day . "EndTime = :" . $day . "EndTime";
            $params[$day . "StartTime"] = $times[$day . "_start"] ?? null;
            $params[$day . "EndTime"] = $times[$day . "_end"] ?? null;
        }

        $query = "UPDATE availability SET " . implode(", ", $setClauses)
            . " WHERE user_id = :userId";

        $stmt = parent::connect()->prepare($query);

        foreach ($params as $name => $value) {
            $type = $value === null
                ? PDO::PARAM_NULL
                : (is_int($value) ? PDO::PARAM_INT : PDO::PARAM_STR);
            $stmt->bindValue(":" . $name, $value, $type);
        }

        $stmt->execute();
    }

    protected function getAvailability(int $userId): array {
        $stmt = parent::connect()->prepare(
            "SELECT * FROM availability WHERE user_id = :userId"
        );
        $stmt->execute(["userId" => $userId]);

        $row = $stmt->fetch();
        return is_array($row) ? $row : [];
    }
}