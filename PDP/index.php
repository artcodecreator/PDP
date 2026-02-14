<?php

declare(strict_types=1);

session_start();

require __DIR__ . '/config.php';

spl_autoload_register(function (string $class): void {
    $prefix = 'App\\';
    $baseDir = __DIR__ . '/app/';
    $prefixLength = strlen($prefix);

    if (strncmp($prefix, $class, $prefixLength) !== 0) {
        return;
    }

    $relativeClass = substr($class, $prefixLength);
    $file = $baseDir . str_replace('\\', '/', $relativeClass) . '.php';

    if (file_exists($file)) {
        require $file;
    }
});

$controllerParam = $_GET['controller'] ?? null;
$actionParam = $_GET['action'] ?? null;

if (!isset($_SESSION['user_id'])) {
    $controllerName = 'auth';
    $actionName = $actionParam ?? 'index';
} else {
    if ($controllerParam === null) {
        $controllerName = 'dashboard';
    } else {
        $controllerName = $controllerParam;
    }

    $actionName = $actionParam ?? 'index';
}

$controllerClass = 'App\\Controllers\\' . ucfirst($controllerName) . 'Controller';

if (!class_exists($controllerClass)) {
    http_response_code(404);
    echo 'Controller not found';
    exit;
}

$controller = new $controllerClass();

if (!method_exists($controller, $actionName)) {
    http_response_code(404);
    echo 'Action not found';
    exit;
}

$controller->{$actionName}();
