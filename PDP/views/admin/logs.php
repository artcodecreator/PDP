<?php require_once __DIR__ . '/../partials/header.php'; ?>

<div class="glass-card">
    <div style="display: flex; justify-content: space-between; align-items: center;">
        <h2><i class="fas fa-list-alt"></i> System Logs</h2>
        <a href="index.php?controller=admin&action=index" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Back to Dashboard</a>
    </div>
</div>

<div class="glass-card">
    <table style="width: 100%; border-collapse: collapse;">
        <thead>
            <tr style="border-bottom: 1px solid rgba(255,255,255,0.1);">
                <th style="text-align: left; padding: 10px;">ID</th>
                <th style="text-align: left; padding: 10px;">Time</th>
                <th style="text-align: left; padding: 10px;">Action</th>
                <th style="text-align: left; padding: 10px;">Actor</th>
                <th style="text-align: left; padding: 10px;">Severity</th>
                <th style="text-align: left; padding: 10px;">Context</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($logs as $log): ?>
                <tr style="border-bottom: 1px solid rgba(255,255,255,0.05);">
                    <td style="padding: 10px; opacity: 0.7;">#<?php echo $log['log_id']; ?></td>
                    <td style="padding: 10px;"><?php echo date('M d, H:i:s', strtotime($log['occurred_at'])); ?></td>
                    <td style="padding: 10px; font-weight: bold;"><?php echo htmlspecialchars($log['action']); ?></td>
                    <td style="padding: 10px;"><?php echo htmlspecialchars($log['actor_role']); ?> (ID: <?php echo $log['actor_id']; ?>)</td>
                    <td style="padding: 10px;">
                        <span class="badge badge-<?php echo $log['severity'] === 'WARNING' ? 'high' : ($log['severity'] === 'ERROR' ? 'danger' : 'low'); ?>">
                            <?php echo htmlspecialchars($log['severity']); ?>
                        </span>
                    </td>
                    <td style="padding: 10px; font-family: monospace; font-size: 0.8rem; max-width: 300px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                        <?php echo htmlspecialchars($log['context'] ?? ''); ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php require_once __DIR__ . '/../partials/footer.php'; ?>
