<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Model;

class Template extends Model
{
    public static function all(): array
    {
        $db = static::db();
        $stmt = $db->query("
            SELECT t.*, u.full_name as creator_name 
            FROM templates t 
            JOIN users u ON t.user_id = u.user_id 
            ORDER BY t.created_at DESC
        ");
        return $stmt->fetchAll();
    }

    public static function create(int $userId, string $name): int
    {
        $db = static::db();
        $stmt = $db->prepare("INSERT INTO templates (user_id, name) VALUES (?, ?)");
        $stmt->execute([$userId, $name]);
        return (int) $db->lastInsertId();
    }

    public static function delete(int $templateId): void
    {
        $db = static::db();
        $stmt = $db->prepare("DELETE FROM templates WHERE template_id = ?");
        $stmt->execute([$templateId]);
    }
}
