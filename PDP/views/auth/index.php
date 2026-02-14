<?php require_once __DIR__ . '/../partials/header.php'; ?>

<div class="glass-card" style="text-align: center; margin-bottom: 3rem;">
    <h1 style="color: var(--text-color); text-shadow: 0 2px 4px rgba(0,0,0,0.5);">Welcome to PDP v2</h1>
    <p style="color: var(--text-light); opacity: 0.9;">NextGen productivity for the modern professional.</p>
</div>

<?php if (!empty($errors)): ?>
    <div class="glass-card" style="background: rgba(220, 53, 69, 0.2);">
        <?php foreach ($errors as $error): ?>
            <p class="text-danger" style="margin: 0.5rem 0; font-weight: bold;"><?php echo htmlspecialchars($error); ?></p>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<?php if ($success): ?>
    <div class="glass-card" style="background: rgba(40, 167, 69, 0.2);">
        <p class="text-success" style="margin: 0; font-weight: bold;"><?php echo htmlspecialchars($success); ?></p>
    </div>
<?php endif; ?>

<div class="dashboard-grid">
    <div class="glass-card">
        <h2><i class="fas fa-sign-in-alt"></i> Sign In</h2>
        <form action="index.php?controller=auth&action=login" method="post">
            <div class="form-group">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-control" required>
            </div>
            <div class="form-group">
                <label class="form-label">Password</label>
                <input type="password" name="password" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary" style="width: 100%;">Login</button>
        </form>
    </div>

    <div class="glass-card">
        <h2><i class="fas fa-user-plus"></i> Register</h2>
        <form action="index.php?controller=auth&action=register" method="post">
            <div class="form-group">
                <label class="form-label">Full Name</label>
                <input type="text" name="name" class="form-control" required>
            </div>
            <div class="form-group">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-control" required>
            </div>
            <div class="form-group">
                <label class="form-label">Password</label>
                <input type="password" name="password" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary" style="width: 100%;">Create Account</button>
        </form>
    </div>
</div>

<?php require_once __DIR__ . '/../partials/footer.php'; ?>
