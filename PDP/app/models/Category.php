<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Model;
use PDO;

class Category extends Model
{
    public static function all(): array
    {
        $pdo = self::db();

        $stmt = $pdo->query('SELECT category_id, name FROM categories ORDER BY name');

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}

