<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Model;
use PDO;

class User extends Model
{
    public static function findByEmail(string $email): ?array
    {
        $pdo = self::db();
        $stmt = $pdo->prepare('SELECT user_id, email, password_hash, full_name, role, is_active FROM users WHERE email = ? LIMIT 1');
        $stmt->execute([$email]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row === false) {
            return null;
        }

        return $row;
    }

    public static function create(string $fullName, string $email, string $password): void
    {
        $pdo = self::db();
        $hash = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $pdo->prepare(
            'INSERT INTO users (email, password_hash, full_name, role, is_active) VALUES (?, ?, ?, ?, ?)'
        );

        $stmt->execute([$email, $hash, $fullName, 'User', 1]);
    }

    public static function all(): array
    {
        $pdo = self::db();
        $stmt = $pdo->query('SELECT user_id, email, full_name, role, is_active, created_at FROM users ORDER BY created_at DESC');
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function delete(int $userId): void
    {
        $pdo = self::db();
        $stmt = $pdo->prepare('DELETE FROM users WHERE user_id = ?');
        $stmt->execute([$userId]);
    }

    public static function toggleStatus(int $userId): void
    {
        $pdo = self::db();
        $stmt = $pdo->prepare('UPDATE users SET is_active = NOT is_active WHERE user_id = ?');
        $stmt->execute([$userId]);
    }
}

