<?php

declare(strict_types=1);

class ShowTableView extends ShowTableContr {
    private string $table;

    public function __construct(string $table) {
        $this->table = $table;
    }

    public function showTable(): string {
        $rows = parent::retrieveTable($this->table);

        ob_start();
        ?>
        <table>
            <thead>
                <tr>
                    <th>Username</th>
                    <th>Monday Start Time</th>
                    <th>Monday End Time</th>
                    <th>Tuesday Start Time</th>
                    <th>Tuesday End Time</th>
                    <th>Wednesday Start Time</th>
                    <th>Wednesday End Time</th>
                    <th>Thursday Start Time</th>
                    <th>Thursday End Time</th>
                    <th>Friday Start Time</th>
                    <th>Friday End Time</th>
                    <th>User's ID</th>
                </tr>
            </thead>
            <tbody>
            <?php if ($rows === []): ?>
                <tr><td colspan="12">No availability records yet.</td></tr>
            <?php else: ?>
                <?php foreach ($rows as $user): ?>
                    <tr>
                        <?php foreach ($user as $value): ?>
                            <td><?= htmlspecialchars((string)($value ?? ''), ENT_QUOTES) ?></td>
                        <?php endforeach; ?>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
            </tbody>
        </table>
        <?php
        return (string)ob_get_clean();
    }
}
