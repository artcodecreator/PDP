<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Model;

class MLModel extends Model
{
    public static function latest(): ?array
    {
        $db = static::db();
        $stmt = $db->query("SELECT * FROM ml_models ORDER BY updated_at DESC LIMIT 1");
        $result = $stmt->fetch();
        return $result ?: null;
    }

    public static function updateMetrics(int $modelId, array $metrics): void
    {
        $db = static::db();
        $stmt = $db->prepare("UPDATE ml_models SET metrics = ? WHERE model_id = ?");
        $stmt->execute([json_encode($metrics), $modelId]);
    }

    public static function retrain(string $name, string $version, array $params, array $metrics): void
    {
        $db = static::db();
        $stmt = $db->prepare("
            INSERT INTO ml_models (name, version, params, metrics)
            VALUES (?, ?, ?, ?)
        ");
        $stmt->execute([
            $name,
            $version,
            json_encode($params),
            json_encode($metrics)
        ]);
    }
}
