<?php require_once __DIR__ . '/../partials/header.php'; ?>

<div class="glass-card">
    <div style="display: flex; justify-content: space-between; align-items: center;">
        <h2><i class="fas fa-users-cog"></i> User Management</h2>
        <a href="index.php?controller=admin&action=index" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Back to Dashboard</a>
    </div>
</div>

<div class="glass-card">
    <table style="width: 100%; border-collapse: collapse;">
        <thead>
            <tr style="border-bottom: 1px solid rgba(255,255,255,0.1);">
                <th style="text-align: left; padding: 10px;">ID</th>
                <th style="text-align: left; padding: 10px;">Name</th>
                <th style="text-align: left; padding: 10px;">Email</th>
                <th style="text-align: left; padding: 10px;">Role</th>
                <th style="text-align: left; padding: 10px;">Status</th>
                <th style="text-align: right; padding: 10px;">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($users as $u): ?>
                <tr style="border-bottom: 1px solid rgba(255,255,255,0.05);">
                    <td style="padding: 10px;">#<?php echo $u['user_id']; ?></td>
                    <td style="padding: 10px;"><?php echo htmlspecialchars($u['full_name']); ?></td>
                    <td style="padding: 10px;"><?php echo htmlspecialchars($u['email']); ?></td>
                    <td style="padding: 10px;">
                        <span class="badge badge-<?php echo $u['role'] === 'Admin' ? 'high' : 'medium'; ?>">
                            <?php echo htmlspecialchars($u['role']); ?>
                        </span>
                    </td>
                    <td style="padding: 10px;">
                        <span class="badge badge-<?php echo $u['is_active'] ? 'success' : 'low'; ?>">
                            <?php echo $u['is_active'] ? 'Active' : 'Disabled'; ?>
                        </span>
                    </td>
                    <td style="padding: 10px; text-align: right;">
                        <?php if ($u['user_id'] !== (int)$_SESSION['user_id']): ?>
                            <a href="index.php?controller=admin&action=toggleUser&id=<?php echo $u['user_id']; ?>" 
                               class="btn btn-sm <?php echo $u['is_active'] ? 'btn-warning' : 'btn-success'; ?>"
                               title="<?php echo $u['is_active'] ? 'Disable' : 'Enable'; ?>">
                                <i class="fas <?php echo $u['is_active'] ? 'fa-ban' : 'fa-check'; ?>"></i>
                            </a>
                            <a href="index.php?controller=admin&action=deleteUser&id=<?php echo $u['user_id']; ?>" 
                               class="btn btn-sm btn-danger" 
                               onclick="return confirm('Are you sure you want to permanently delete this user? This cannot be undone.');"
                               title="Delete User">
                                <i class="fas fa-trash"></i>
                            </a>
                        <?php else: ?>
                            <span style="opacity: 0.5;">(You)</span>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php require_once __DIR__ . '/../partials/footer.php'; ?>
