<?php

declare(strict_types=1);

namespace App\Core;

class Controller
{
    protected function render(string $view, array $params = []): void
    {
        extract($params, EXTR_SKIP);
        require __DIR__ . '/../../views/' . $view . '.php';
    }
}

