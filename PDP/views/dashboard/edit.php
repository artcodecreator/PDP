<?php require_once __DIR__ . '/../partials/header.php'; ?>

<div class="container" style="max-width: 600px;">
    <div class="glass-card">
        <h3><i class="fas fa-edit"></i> Edit Task</h3>
        
        <?php if (!empty($errors)): ?>
            <div style="color: var(--danger); margin-bottom: 1rem;">
                <?php foreach ($errors as $error): ?>
                    <p><?php echo htmlspecialchars($error); ?></p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <form action="index.php?controller=dashboard&action=edit&id=<?php echo $task['task_id']; ?>" method="post">
            <div class="form-group">
                <label class="form-label">Title</label>
                <input type="text" name="title" class="form-control" value="<?php echo htmlspecialchars($task['title']); ?>" required>
            </div>
            
            <div class="form-group">
                <label class="form-label">Deadline</label>
                <input type="datetime-local" name="deadline" class="form-control" value="<?php echo $task['deadline'] ? date('Y-m-d\TH:i', strtotime($task['deadline'])) : ''; ?>">
            </div>
            
            <div class="form-group">
                <label class="form-label">Priority</label>
                <select name="priority" class="form-control">
                    <option value="">Select Priority</option>
                    <option value="1" <?php echo $task['priority'] == 1 ? 'selected' : ''; ?>>Low</option>
                    <option value="3" <?php echo $task['priority'] == 3 ? 'selected' : ''; ?>>Medium</option>
                    <option value="5" <?php echo $task['priority'] == 5 ? 'selected' : ''; ?>>High</option>
                </select>
            </div>
            
            <div class="form-group">
                <label class="form-label">Category</label>
                <select name="category_id" class="form-control">
                    <option value="">Select Category</option>
                    <?php foreach ($categories as $cat): ?>
                        <option value="<?php echo $cat['category_id']; ?>" <?php echo $task['category_id'] == $cat['category_id'] ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($cat['name']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <a href="index.php?controller=dashboard&action=index" class="btn btn-sm" style="background: rgba(255,255,255,0.2); color: var(--text-color);">Cancel</a>
                <button type="submit" class="btn btn-primary">Update Task</button>
            </div>
        </form>
    </div>
</div>

<?php require_once __DIR__ . '/../partials/footer.php'; ?>
