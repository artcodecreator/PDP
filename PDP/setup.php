<?php

declare(strict_types=1);

require __DIR__ . '/config.php';

echo "<h1>Database Setup</h1>";

try {
    // 1. Connect without database selected to create it
    $pdo = new PDO("mysql:host={$dbHost};charset=utf8mb4", $dbUser, $dbPass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    ]);

    echo "<p>Connected to MySQL server successfully.</p>";

    // 2. Read the SQL file
    $sqlFile = __DIR__ . '/database.sql';
    if (!file_exists($sqlFile)) {
        die("<p style='color:red'>Error: database.sql not found.</p>");
    }

    $sql = file_get_contents($sqlFile);

    // 3. Execute the SQL commands
    // Note: PDO can execute multiple statements if emulated prepares are on or driver supports it.
    // We will execute it as a raw exec.
    $pdo->exec($sql);
    
    echo "<p style='color:green'>Database and tables created successfully!</p>";
    echo "<p>You can now <a href='index.php'>go to the homepage</a>.</p>";

    // 4. Insert default categories if they don't exist (just in case sql file didn't have them)
    $pdo->exec("USE personalized_daily_planner");
    $pdo->exec("
        INSERT IGNORE INTO categories (name) VALUES 
        ('Work'), ('Study'), ('Health'), ('Personal');
    ");
    echo "<p>Default categories ensured.</p>";

} catch (PDOException $e) {
    echo "<p style='color:red'>Database Error: " . htmlspecialchars($e->getMessage()) . "</p>";
}
