<?php require_once __DIR__ . '/../partials/header.php'; ?>

<div class="glass-card">
    <div style="display: flex; justify-content: space-between; align-items: center;">
        <h2><i class="fas fa-file-alt"></i> Template Management</h2>
        <a href="index.php?controller=admin&action=index" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Back to Dashboard</a>
    </div>
</div>

<div class="dashboard-grid">
    <div class="glass-card">
        <h3>Add New Template</h3>
        <form action="index.php?controller=admin&action=templates" method="post">
            <div class="form-group">
                <label class="form-label">Template Name</label>
                <input type="text" name="name" class="form-control" placeholder="e.g., Morning Routine" required>
            </div>
            <button type="submit" class="btn btn-primary">Create Template</button>
        </form>
    </div>

    <div class="glass-card">
        <h3>Existing Templates</h3>
        <ul class="task-list">
            <?php if (empty($templates)): ?>
                <p>No templates found.</p>
            <?php else: ?>
                <?php foreach ($templates as $t): ?>
                    <li class="task-item">
                        <div>
                            <strong><?php echo htmlspecialchars($t['name']); ?></strong>
                            <div style="font-size: 0.8rem; opacity: 0.7;">
                                Created by <?php echo htmlspecialchars($t['creator_name']); ?> on <?php echo date('M d, Y', strtotime($t['created_at'])); ?>
                            </div>
                        </div>
                        <a href="index.php?controller=admin&action=deleteTemplate&id=<?php echo $t['template_id']; ?>" 
                           class="btn btn-sm btn-danger" 
                           onclick="return confirm('Delete this template?');">
                            <i class="fas fa-trash"></i>
                        </a>
                    </li>
                <?php endforeach; ?>
            <?php endif; ?>
        </ul>
    </div>
</div>

<?php require_once __DIR__ . '/../partials/footer.php'; ?>
