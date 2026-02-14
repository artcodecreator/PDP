<?php require_once __DIR__ . '/../partials/header.php'; ?>

<div class="glass-card">
    <div style="display: flex; justify-content: space-between; align-items: center;">
        <h2><i class="fas fa-brain"></i> ML Model Monitor</h2>
        <a href="index.php?controller=admin&action=index" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Back to Dashboard</a>
    </div>
</div>

<div class="dashboard-grid">
    <div class="glass-card">
        <h3>Current Model Performance</h3>
        <?php if ($model): ?>
            <?php 
                $metrics = json_decode($model['metrics'], true); 
                $params = json_decode($model['params'], true);
            ?>
            <div style="margin-bottom: 20px;">
                <div style="font-size: 1.2rem; font-weight: bold; margin-bottom: 10px;"><?php echo htmlspecialchars($model['name']); ?></div>
                <div class="badge badge-medium">v<?php echo htmlspecialchars($model['version']); ?></div>
                <div style="margin-top: 5px; opacity: 0.8;">Updated: <?php echo $model['updated_at']; ?></div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
                <div style="background: rgba(0,0,0,0.2); padding: 15px; border-radius: 10px;">
                    <h4 style="margin-bottom: 10px;">Metrics</h4>
                    <?php foreach ($metrics as $k => $v): ?>
                        <div style="display: flex; justify-content: space-between; margin-bottom: 5px;">
                            <span><?php echo ucfirst($k); ?>:</span>
                            <span style="font-weight: bold; color: var(--success);"><?php echo $v; ?></span>
                        </div>
                    <?php endforeach; ?>
                </div>
                <div style="background: rgba(0,0,0,0.2); padding: 15px; border-radius: 10px;">
                    <h4 style="margin-bottom: 10px;">Parameters</h4>
                    <?php foreach ($params as $k => $v): ?>
                        <div style="display: flex; justify-content: space-between; margin-bottom: 5px;">
                            <span><?php echo ucfirst($k); ?>:</span>
                            <span><?php echo $v; ?></span>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php else: ?>
            <p>No model deployed.</p>
        <?php endif; ?>
    </div>

    <div class="glass-card">
        <h3>Actions</h3>
        <p>Triggering a retrain will simulate a new training run and update the model version.</p>
        <a href="index.php?controller=admin&action=ml&action=retrain" class="btn btn-warning" style="width: 100%; text-align: center; margin-top: 20px;">
            <i class="fas fa-sync-alt"></i> Trigger Retraining
        </a>
    </div>
</div>

<?php require_once __DIR__ . '/../partials/footer.php'; ?>
