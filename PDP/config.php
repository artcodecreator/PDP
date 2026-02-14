<?php

declare(strict_types=1);

$dbHost = '127.0.0.1';
$dbName = 'personalized_daily_planner';
$dbUser = 'root';
$dbPass = '';

// Determine Base URL
$protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https://' : 'http://';
$host = $_SERVER['HTTP_HOST'];
$script = dirname($_SERVER['SCRIPT_NAME']);
// Remove trailing slashes and backslashes
$script = trim($script, '/\\');
$baseUrl = $protocol . $host . ($script ? '/' . $script : '');
// Ensure it ends with /
if (substr($baseUrl, -1) !== '/') {
    $baseUrl .= '/';
}
define('BASE_URL', $baseUrl);

function db(): PDO
{
    static $pdo = null;

    if ($pdo === null) {
        global $dbHost, $dbName, $dbUser, $dbPass;

        $dsn = "mysql:host={$dbHost};dbname={$dbName};charset=utf8mb4";

        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ];

        $pdo = new PDO($dsn, $dbUser, $dbPass, $options);
    }

    return $pdo;
}

