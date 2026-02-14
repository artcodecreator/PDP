<?php

declare(strict_types=1);

require __DIR__ . '/config.php';

// Enable error reporting
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

echo "<!DOCTYPE html><html><head><title>Seeding Data</title><style>body{font-family:sans-serif;line-height:1.6;max-width:800px;margin:20px auto;padding:20px;background:#f4f4f4;color:#333;} h1{color:#4e54c8;} .success{color:green;} .error{color:red;} .section{background:#fff;padding:15px;margin-bottom:15px;border-radius:5px;box-shadow:0 2px 5px rgba(0,0,0,0.1);}</style></head><body>";
echo "<h1><i class='fas fa-database'></i> Seeding Demo Data</h1>";

try {
    $pdo = db();

    // 1. Categories (Ensure they exist)
    echo "<div class='section'><h2>1. Categories</h2>";
    $categories = ['Work', 'Study', 'Health', 'Personal', 'Finance', 'Social'];
    $catMap = []; // Name => ID

    $stmt = $pdo->prepare("INSERT INTO categories (name) VALUES (?) ON DUPLICATE KEY UPDATE name = name");
    $idStmt = $pdo->prepare("SELECT category_id FROM categories WHERE name = ?");

    foreach ($categories as $cat) {
        $stmt->execute([$cat]);
        $idStmt->execute([$cat]);
        $id = $idStmt->fetchColumn();
        $catMap[$cat] = $id;
        echo "<div>Category <strong>$cat</strong> ensured (ID: $id).</div>";
    }
    echo "<div class='success'>Categories synced.</div></div>";

    // 2. Users
    echo "<div class='section'><h2>2. Users</h2>";
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

    $userMap = []; // Email => ID

    $userStmt = $pdo->prepare(
        'INSERT INTO users (email, password_hash, full_name, role, is_active) 
         VALUES (?, ?, ?, ?, 1)
         ON DUPLICATE KEY UPDATE 
            password_hash = VALUES(password_hash),
            full_name = VALUES(full_name),
            role = VALUES(role)'
    );
    
    $userIdStmt = $pdo->prepare("SELECT user_id FROM users WHERE email = ?");

    foreach ($users as $u) {
        $hash = password_hash($u['password'], PASSWORD_DEFAULT);
        $userStmt->execute([$u['email'], $hash, $u['full_name'], $u['role']]);
        
        $userIdStmt->execute([$u['email']]);
        $uid = $userIdStmt->fetchColumn();
        $userMap[$u['email']] = $uid;
        
        echo "<div>User <strong>{$u['email']}</strong> ({$u['role']}) ensured (ID: $uid).</div>";
    }
    echo "<div class='success'>Users synced.</div></div>";

    // 3. User Preferences
    echo "<div class='section'><h2>3. User Preferences</h2>";
    $prefs = [
        'john@example.com' => [
            'work_start' => '09:00',
            'work_end' => '17:00',
            'default_reminder_minutes' => 15,
            'timezone' => 'America/New_York'
        ],
        'jane@example.com' => [
            'work_start' => '08:00',
            'work_end' => '16:00',
            'default_reminder_minutes' => 30,
            'timezone' => 'Europe/London'
        ]
    ];

    $prefStmt = $pdo->prepare(
        "INSERT INTO user_preferences (user_id, work_start, work_end, default_reminder_minutes, timezone)
         VALUES (?, ?, ?, ?, ?)
         ON DUPLICATE KEY UPDATE
            work_start = VALUES(work_start),
            work_end = VALUES(work_end),
            default_reminder_minutes = VALUES(default_reminder_minutes),
            timezone = VALUES(timezone)"
    );

    foreach ($prefs as $email => $p) {
        if (isset($userMap[$email])) {
            $uid = $userMap[$email];
            $prefStmt->execute([$uid, $p['work_start'], $p['work_end'], $p['default_reminder_minutes'], $p['timezone']]);
            echo "<div>Preferences set for <strong>$email</strong>.</div>";
        }
    }
    echo "<div class='success'>Preferences synced.</div></div>";

    // 4. Tasks
    echo "<div class='section'><h2>4. Tasks</h2>";
    
    // Clear existing tasks for demo users to avoid duplicates on re-run (optional, but cleaner for demo)
    // We'll just insert new ones or ignore if we had a unique key (which we don't on title).
    // Let's just add them.
    
    $tasks = [
        'john@example.com' => [
            ['title' => 'Complete Project Proposal', 'cat' => 'Work', 'prio' => 5, 'status' => 'PENDING', 'due' => '+2 days'],
            ['title' => 'Review Q3 Budget', 'cat' => 'Work', 'prio' => 4, 'status' => 'COMPLETED', 'due' => '-1 days'],
            ['title' => 'Gym Workout', 'cat' => 'Health', 'prio' => 3, 'status' => 'PENDING', 'due' => 'today 18:00'],
            ['title' => 'Buy Groceries', 'cat' => 'Personal', 'prio' => 2, 'status' => 'PENDING', 'due' => '+1 days'],
            ['title' => 'Read "Clean Code"', 'cat' => 'Study', 'prio' => 3, 'status' => 'COMPLETED', 'due' => '-5 days'],
            ['title' => 'Call Mom', 'cat' => 'Personal', 'prio' => 1, 'status' => 'PENDING', 'due' => 'next Sunday'],
            ['title' => 'Team Meeting Preparation', 'cat' => 'Work', 'prio' => 5, 'status' => 'PENDING', 'due' => 'tomorrow 09:00'],
            // New tasks added
            ['title' => 'Dentist Appointment', 'cat' => 'Health', 'prio' => 5, 'status' => 'PENDING', 'due' => 'tomorrow 10:00'],
            ['title' => 'Submit Tax Returns', 'cat' => 'Finance', 'prio' => 5, 'status' => 'PENDING', 'due' => '+3 days'],
            ['title' => 'Weekly Team Sync', 'cat' => 'Work', 'prio' => 3, 'status' => 'COMPLETED', 'due' => '-2 days'],
            ['title' => 'Buy Birthday Gift', 'cat' => 'Personal', 'prio' => 2, 'status' => 'PENDING', 'due' => '+5 days'],
            ['title' => 'Learn React Basics', 'cat' => 'Study', 'prio' => 4, 'status' => 'PENDING', 'due' => '+1 week'],
            ['title' => 'Pay Electricity Bill', 'cat' => 'Finance', 'prio' => 5, 'status' => 'COMPLETED', 'due' => '-1 week'],
            ['title' => 'Evening Run', 'cat' => 'Health', 'prio' => 3, 'status' => 'PENDING', 'due' => 'today 19:00'],
            ['title' => 'Client Presentation Prep', 'cat' => 'Work', 'prio' => 5, 'status' => 'PENDING', 'due' => 'today 14:00'],
            ['title' => 'Clean the Garage', 'cat' => 'Personal', 'prio' => 1, 'status' => 'PENDING', 'due' => 'next Saturday']
        ],
        'jane@example.com' => [
            ['title' => 'Write Research Paper', 'cat' => 'Study', 'prio' => 5, 'status' => 'PENDING', 'due' => '+1 week'],
            ['title' => 'Yoga Class', 'cat' => 'Health', 'prio' => 3, 'status' => 'COMPLETED', 'due' => 'yesterday'],
            ['title' => 'Update Website Content', 'cat' => 'Work', 'prio' => 4, 'status' => 'PENDING', 'due' => '+3 days'],
            // New tasks added
            ['title' => 'Plan Weekend Trip', 'cat' => 'Personal', 'prio' => 2, 'status' => 'PENDING', 'due' => '+2 days'],
            ['title' => 'Coffee with Sarah', 'cat' => 'Social', 'prio' => 2, 'status' => 'PENDING', 'due' => 'tomorrow 16:00'],
            ['title' => 'Pay Credit Card', 'cat' => 'Finance', 'prio' => 5, 'status' => 'PENDING', 'due' => 'today 20:00'],
            ['title' => 'Read Chapter 4', 'cat' => 'Study', 'prio' => 3, 'status' => 'PENDING', 'due' => '+4 days']
        ]
    ];

    $taskStmt = $pdo->prepare(
        "INSERT INTO tasks (user_id, title, category_id, priority, status, deadline, created_at)
         VALUES (?, ?, ?, ?, ?, ?, ?)"
    );

    $count = 0;
    foreach ($tasks as $email => $userTasks) {
        if (isset($userMap[$email])) {
            $uid = $userMap[$email];
            foreach ($userTasks as $t) {
                $catId = $catMap[$t['cat']] ?? null;
                $deadline = date('Y-m-d H:i:s', strtotime($t['due']));
                $created = date('Y-m-d H:i:s', strtotime('-' . rand(1, 10) . ' days'));
                
                $taskStmt->execute([$uid, $t['title'], $catId, $t['prio'], $t['status'], $deadline, $created]);
                $count++;
            }
        }
    }
    echo "<div class='success'>Inserted $count demo tasks.</div></div>";

    // 5. Templates
    echo "<div class='section'><h2>5. Templates</h2>";
    $templates = [
        'john@example.com' => [
            'Morning Routine' => [
                ['title' => 'Meditation', 'mins' => 15, 'cat' => 'Health'],
                ['title' => 'Review Daily Goals', 'mins' => 10, 'cat' => 'Work'],
                ['title' => 'Check Emails', 'mins' => 20, 'cat' => 'Work']
            ],
            'Weekly Review' => [
                ['title' => 'Review Completed Tasks', 'mins' => 30, 'cat' => 'Work'],
                ['title' => 'Plan Next Week', 'mins' => 30, 'cat' => 'Work']
            ]
        ]
    ];

    $tplStmt = $pdo->prepare("INSERT INTO templates (user_id, name) VALUES (?, ?)");
    $tplItemStmt = $pdo->prepare("INSERT INTO template_items (template_id, title, duration_minutes, category_id) VALUES (?, ?, ?, ?)");
    $lastIdStmt = $pdo->query("SELECT LAST_INSERT_ID()");

    foreach ($templates as $email => $tpls) {
        if (isset($userMap[$email])) {
            $uid = $userMap[$email];
            foreach ($tpls as $name => $items) {
                $tplStmt->execute([$uid, $name]);
                $tplId = $pdo->lastInsertId(); // Better way
                
                foreach ($items as $item) {
                    $catId = $catMap[$item['cat']] ?? null;
                    $tplItemStmt->execute([$tplId, $item['title'], $item['mins'], $catId]);
                }
                echo "<div>Template <strong>$name</strong> created for $email.</div>";
            }
        }
    }
    echo "<div class='success'>Templates created.</div></div>";

    // 6. System Logs (Mock Data)
    echo "<div class='section'><h2>6. System Logs</h2>";
    $logs = [
        ['action' => 'LOGIN', 'severity' => 'INFO', 'msg' => 'User logged in'],
        ['action' => 'TASK_CREATE', 'severity' => 'INFO', 'msg' => 'Created new task'],
        ['action' => 'LOGIN_FAILED', 'severity' => 'WARNING', 'msg' => 'Invalid password attempt'],
        ['action' => 'PROFILE_UPDATE', 'severity' => 'INFO', 'msg' => 'Updated preferences']
    ];

    $logStmt = $pdo->prepare("INSERT INTO system_logs (actor_id, actor_role, action, severity, context) VALUES (?, ?, ?, ?, ?)");
    
    // Add some random logs
    for ($i = 0; $i < 10; $i++) {
        $log = $logs[array_rand($logs)];
        $uid = $userMap['john@example.com'];
        $role = 'User';
        $context = json_encode(['message' => $log['msg'], 'ip' => '127.0.0.1']);
        
        $logStmt->execute([$uid, $role, $log['action'], $log['severity'], $context]);
    }
    echo "<div class='success'>Mock logs generated.</div></div>";

    echo "<h2 class='success'>All Demo Data Seeded Successfully!</h2>";
    echo "<p><a href='index.php'>Go to Home</a></p>";
    echo "</body></html>";

} catch (Exception $e) {
    echo "<div class='error'><h1>Error</h1><p>" . htmlspecialchars($e->getMessage()) . "</p></div>";
    echo "<pre>" . $e->getTraceAsString() . "</pre>";
}
