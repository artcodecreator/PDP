<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PDP v2 - NextGen Planner</title>
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>public/css/glassy.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <!-- Chart.js for Analytics -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <div class="container">
        <nav class="glass-nav">
            <a href="<?php echo BASE_URL; ?>index.php" class="nav-brand">
                <i class="fas fa-layer-group"></i> PDP v2
            </a>
            <div class="nav-links">
                <?php if (isset($_SESSION['user_id'])): ?>
                    <a href="<?php echo BASE_URL; ?>index.php?controller=dashboard&action=index"><i class="fas fa-home"></i> Dashboard</a>
                    <a href="<?php echo BASE_URL; ?>index.php?controller=profile&action=index"><i class="fas fa-user"></i> Profile</a>
                    <?php if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'Admin'): ?>
                        <a href="<?php echo BASE_URL; ?>index.php?controller=admin&action=index"><i class="fas fa-cog"></i> Admin</a>
                    <?php endif; ?>
                    <a href="<?php echo BASE_URL; ?>index.php?controller=auth&action=logout"><i class="fas fa-sign-out-alt"></i> Logout</a>
                <?php else: ?>
                    <a href="<?php echo BASE_URL; ?>index.php?controller=auth&action=index">Login</a>
                <?php endif; ?>
            </div>
        </nav>
