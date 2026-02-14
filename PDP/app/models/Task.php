<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Model;
use PDO;

class Task extends Model
{
    public static function create(int $userId, string $title, ?string $deadline, ?int $priority, ?int $categoryId): void
    {
        $pdo = self::db();

        $stmt = $pdo->prepare(
            'INSERT INTO tasks (user_id, title, deadline, priority, category_id, status) VALUES (?, ?, ?, ?, ?, ?)'
        );

        $stmt->execute([
            $userId,
            $title,
            $deadline,
            $priority,
            $categoryId,
            'PENDING',
        ]);
    }

    public static function countAll(): int
    {
        $pdo = self::db();
        $stmt = $pdo->query('SELECT COUNT(*) FROM tasks');
        return (int) $stmt->fetchColumn();
    }

    public static function find(int $taskId, int $userId): ?array
    {
        $pdo = self::db();
        $stmt = $pdo->prepare('SELECT * FROM tasks WHERE task_id = ? AND user_id = ? LIMIT 1');
        $stmt->execute([$taskId, $userId]);
        $task = $stmt->fetch(PDO::FETCH_ASSOC);

        return $task ?: null;
    }

    public static function updateStatus(int $taskId, int $userId, string $status): void
    {
        $pdo = self::db();
        $stmt = $pdo->prepare('UPDATE tasks SET status = ? WHERE task_id = ? AND user_id = ?');
        $stmt->execute([$status, $taskId, $userId]);
    }

    public static function delete(int $taskId, int $userId): void
    {
        $pdo = self::db();
        $stmt = $pdo->prepare('DELETE FROM tasks WHERE task_id = ? AND user_id = ?');
        $stmt->execute([$taskId, $userId]);
    }

    public static function update(int $taskId, int $userId, string $title, ?string $deadline, ?int $priority, ?int $categoryId): void
    {
        $pdo = self::db();
        $stmt = $pdo->prepare(
            'UPDATE tasks SET title = ?, deadline = ?, priority = ?, category_id = ? WHERE task_id = ? AND user_id = ?'
        );
        $stmt->execute([$title, $deadline, $priority, $categoryId, $taskId, $userId]);
    }

    public static function forUser(int $userId, string $search = '', string $status = ''): array
    {
        $pdo = self::db();

        $sql = 'SELECT t.task_id, t.title, t.deadline, t.priority, t.status, c.name AS category_name
                FROM tasks t
                LEFT JOIN categories c ON t.category_id = c.category_id
                WHERE t.user_id = ?';
        
        $params = [$userId];

        if ($search !== '') {
            $sql .= ' AND (t.title LIKE ? OR c.name LIKE ?)';
            $params[] = "%$search%";
            $params[] = "%$search%";
        }

        if ($status !== '') {
            $sql .= ' AND t.status = ?';
            $params[] = $status;
        }

        $sql .= ' ORDER BY (t.status = "COMPLETED"), t.deadline IS NULL, t.deadline, t.created_at DESC';

        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}

