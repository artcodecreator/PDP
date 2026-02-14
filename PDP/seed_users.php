<?php

declare(strict_types=1);

require __DIR__ . '/config.php';

echo "<h1>Seeding Users...</h1>";

try {
    $pdo = db();

    $users = [
        [
            'email' => 'admin@example.com',
            'password' => 'admin123',
            'full_name' => 'System Administrator',
            'role' => 'Admin'
        ],
        [
            'email' => 'john@example.com',
            'password' => 'user123',
            'full_name' => 'John Doe',
            'role' => 'User'
        ],
        [
            'email' => 'jane@example.com',
            'password' => 'user123',
            'full_name' => 'Jane Smith',
            'role' => 'User'
        ]
    ];

    $stmt = $pdo->prepare(
        'INSERT INTO users (email, password_hash, full_name, role, is_active) 
         VALUES (?, ?, ?, ?, 1)
         ON DUPLICATE KEY UPDATE 
            password_hash = VALUES(password_hash),
            full_name = VALUES(full_name),
            role = VALUES(role)'
    );

    foreach ($users as $user) {
        $hash = password_hash($user['password'], PASSWORD_DEFAULT);
        $stmt->execute([
            $user['email'],
            $hash,
            $user['full_name'],
            $user['role']
        ]);
        echo "<p>User <strong>{$user['email']}</strong> ({$user['role']}) seeded.</p>";
    }

    echo "<p style='color:green'>All users seeded successfully.</p>";

} catch (PDOException $e) {
    echo "<p style='color:red'>Error: " . htmlspecialchars($e->getMessage()) . "</p>";
}
