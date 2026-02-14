<?php require_once __DIR__ . '/../partials/header.php'; ?>

<div class="glass-card">
    <div style="display: flex; justify-content: space-between; align-items: center;">
        <h2><i class="fas fa-user-shield"></i> Admin Dashboard</h2>
        <span class="badge badge-high">Admin Access</span>
    </div>
</div>

<div class="dashboard-grid">
    <!-- Stats Row -->
    <div class="glass-card" style="text-align: center;">
        <h3><i class="fas fa-users"></i> Total Users</h3>
        <div style="font-size: 2.5rem; font-weight: bold; color: var(--primary);"><?php echo $stats['total_users']; ?></div>
    </div>
    <div class="glass-card" style="text-align: center;">
        <h3><i class="fas fa-user-check"></i> Active Users</h3>
        <div style="font-size: 2.5rem; font-weight: bold; color: var(--success);"><?php echo $stats['active_users']; ?></div>
    </div>
    <div class="glass-card" style="text-align: center;">
        <h3><i class="fas fa-tasks"></i> Total Tasks</h3>
        <div style="font-size: 2.5rem; font-weight: bold; color: var(--warning);"><?php echo $stats['total_tasks']; ?></div>
    </div>
</div>

<div class="dashboard-grid">
    <!-- Quick Actions -->
    <div class="glass-card">
        <h3><i class="fas fa-bolt"></i> Quick Actions</h3>
        <div style="display: flex; flex-direction: column; gap: 10px;">
            <a href="index.php?controller=admin&action=users" class="btn btn-primary"><i class="fas fa-users-cog"></i> Manage Users</a>
            <a href="index.php?controller=admin&action=templates" class="btn btn-success"><i class="fas fa-file-alt"></i> Manage Templates</a>
            <a href="index.php?controller=admin&action=ml" class="btn btn-warning"><i class="fas fa-brain"></i> ML Monitor</a>
            <a href="index.php?controller=admin&action=logs" class="btn btn-secondary"><i class="fas fa-list-alt"></i> View System Logs</a>
        </div>
    </div>

    <!-- ML Summary -->
    <div class="glass-card">
        <h3><i class="fas fa-robot"></i> ML Model Status</h3>
        <?php if ($ml_model): ?>
            <p><strong>Model:</strong> <?php echo htmlspecialchars($ml_model['name']); ?> (v<?php echo htmlspecialchars($ml_model['version']); ?>)</p>
            <p><strong>Last Updated:</strong> <?php echo $ml_model['updated_at']; ?></p>
            <?php $metrics = json_decode($ml_model['metrics'], true); ?>
            <?php if ($metrics): ?>
                <div style="display: flex; gap: 10px; margin-top: 10px;">
                    <span class="badge badge-success">Accuracy: <?php echo $metrics['accuracy'] * 100; ?>%</span>
                    <span class="badge badge-low">Loss: <?php echo $metrics['loss']; ?></span>
                </div>
            <?php endif; ?>
        <?php else: ?>
            <p>No model trained yet.</p>
        <?php endif; ?>
    </div>
</div>

<!-- Recent Logs -->
<div class="glass-card">
    <h3><i class="fas fa-history"></i> Recent System Logs</h3>
    <table style="width: 100%; border-collapse: collapse; margin-top: 1rem;">
        <thead>
            <tr style="border-bottom: 1px solid rgba(255,255,255,0.1);">
                <th style="text-align: left; padding: 10px;">Time</th>
                <th style="text-align: left; padding: 10px;">Action</th>
                <th style="text-align: left; padding: 10px;">Actor</th>
                <th style="text-align: left; padding: 10px;">Severity</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($recent_logs as $log): ?>
                <tr style="border-bottom: 1px solid rgba(255,255,255,0.05);">
                    <td style="padding: 10px;"><?php echo date('H:i:s', strtotime($log['occurred_at'])); ?></td>
                    <td style="padding: 10px;"><?php echo htmlspecialchars($log['action']); ?></td>
                    <td style="padding: 10px;"><?php echo htmlspecialchars($log['actor_role']); ?></td>
                    <td style="padding: 10px;">
                        <span class="badge badge-<?php echo $log['severity'] === 'WARNING' ? 'high' : 'low'; ?>">
                            <?php echo htmlspecialchars($log['severity']); ?>
                        </span>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <div style="margin-top: 1rem; text-align: center;">
        <a href="index.php?controller=admin&action=logs" class="btn btn-sm btn-secondary">View All Logs</a>
    </div>
</div>

<?php require_once __DIR__ . '/../partials/footer.php'; ?>
