<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Model;

class SystemLog extends Model
{
    public static function all(int $limit = 100): array
    {
        $db = static::db();
        $stmt = $db->prepare("SELECT * FROM system_logs ORDER BY occurred_at DESC LIMIT ?");
        $stmt->bindValue(1, $limit, \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public static function log(int $actorId, string $actorRole, string $action, string $severity = 'INFO', ?array $context = null): void
    {
        $db = static::db();
        $stmt = $db->prepare("
            INSERT INTO system_logs (actor_id, actor_role, action, severity, context)
            VALUES (?, ?, ?, ?, ?)
        ");
        $stmt->execute([
            $actorId,
            $actorRole,
            $action,
            $severity,
            $context ? json_encode($context) : null
        ]);
    }
}
