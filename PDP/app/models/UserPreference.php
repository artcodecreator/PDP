<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Model;
use PDO;

class UserPreference extends Model
{
    public static function find(int $userId): ?array
    {
        $pdo = self::db();
        $stmt = $pdo->prepare('SELECT * FROM user_preferences WHERE user_id = ? LIMIT 1');
        $stmt->execute([$userId]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        return $result ?: null;
    }

    public static function save(int $userId, ?string $workStart, ?string $workEnd, int $defaultReminder, string $timezone): void
    {
        $pdo = self::db();
        
        $existing = self::find($userId);

        if ($existing) {
            $stmt = $pdo->prepare(
                'UPDATE user_preferences 
                 SET work_start = ?, work_end = ?, default_reminder_minutes = ?, timezone = ? 
                 WHERE user_id = ?'
            );
            $stmt->execute([$workStart, $workEnd, $defaultReminder, $timezone, $userId]);
        } else {
            $stmt = $pdo->prepare(
                'INSERT INTO user_preferences (user_id, work_start, work_end, default_reminder_minutes, timezone) 
                 VALUES (?, ?, ?, ?, ?)'
            );
            $stmt->execute([$userId, $workStart, $workEnd, $defaultReminder, $timezone]);
        }
    }
}
