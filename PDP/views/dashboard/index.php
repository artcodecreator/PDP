<?php require_once __DIR__ . '/../partials/header.php'; ?>

<?php
    // Calculate Stats
    $totalTasks = count($tasks);
    $completedTasks = 0;
    $pendingTasks = 0;
    foreach ($tasks as $t) {
        if ($t['status'] === 'COMPLETED') $completedTasks++;
        else $pendingTasks++;
    }
?>

<!-- Top Stats Row -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-info">
            <h4>Total Tasks</h4>
            <p><?php echo $totalTasks; ?></p>
        </div>
        <div class="stat-icon" style="color: var(--primary-color);">
            <i class="fas fa-layer-group"></i>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-info">
            <h4>Pending</h4>
            <p><?php echo $pendingTasks; ?></p>
        </div>
        <div class="stat-icon" style="color: var(--warning);">
            <i class="fas fa-clock"></i>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-info">
            <h4>Completed</h4>
            <p><?php echo $completedTasks; ?></p>
        </div>
        <div class="stat-icon" style="color: var(--success);">
            <i class="fas fa-check-circle"></i>
        </div>
    </div>
</div>

<div class="dashboard-grid">
    <!-- Left Column: Sidebar (Add Task & Chart) -->
    <div class="left-col">
        <div class="glass-card">
            <h3><i class="fas fa-plus-circle"></i> Quick Add</h3>
            <?php if (!empty($errors)): ?>
                <div style="color: var(--danger); margin-bottom: 1rem;">
                    <?php foreach ($errors as $error): ?>
                        <p><?php echo htmlspecialchars($error); ?></p>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <form action="index.php?controller=dashboard&action=index" method="post">
                <div class="form-group">
                    <label class="form-label">Title</label>
                    <input type="text" name="title" class="form-control" placeholder="Task title..." required>
                </div>
                <div class="form-group">
                    <label class="form-label">Deadline</label>
                    <input type="datetime-local" name="deadline" class="form-control">
                </div>
                <div class="form-group">
                    <label class="form-label">Priority</label>
                    <select name="priority" class="form-control">
                        <option value="1">Low</option>
                        <option value="3" selected>Medium</option>
                        <option value="5">High</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Category</label>
                    <select name="category_id" class="form-control">
                        <option value="">No Category</option>
                        <?php foreach ($categories as $cat): ?>
                            <option value="<?php echo $cat['category_id']; ?>">
                                <?php echo htmlspecialchars($cat['name']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary" style="width: 100%;">
                    <i class="fas fa-plus"></i> Create Task
                </button>
            </form>
        </div>

        <div class="glass-card">
            <h3><i class="fas fa-chart-pie"></i> Overview</h3>
            <canvas id="productivityChart"></canvas>
        </div>
    </div>

    <!-- Right Column: Main Content -->
    <div class="right-col">
        <!-- Search Bar -->
        <div class="glass-card" style="padding: 1.5rem; margin-bottom: 2rem;">
            <form action="index.php" method="get" style="display: flex; gap: 10px; flex-wrap: wrap;">
                <input type="hidden" name="controller" value="dashboard">
                <input type="hidden" name="action" value="index">
                <div style="flex: 1; position: relative;">
                    <i class="fas fa-search" style="position: absolute; left: 15px; top: 50%; transform: translateY(-50%); color: var(--text-light);"></i>
                    <input type="text" name="search" class="form-control" placeholder="Search tasks..." 
                           value="<?php echo htmlspecialchars($_GET['search'] ?? ''); ?>" 
                           style="padding-left: 40px;">
                </div>
                <select name="filter_status" class="form-control" style="width: auto;">
                    <option value="">All Tasks</option>
                    <option value="PENDING" <?php echo (isset($_GET['filter_status']) && $_GET['filter_status'] === 'PENDING') ? 'selected' : ''; ?>>Pending</option>
                    <option value="COMPLETED" <?php echo (isset($_GET['filter_status']) && $_GET['filter_status'] === 'COMPLETED') ? 'selected' : ''; ?>>Completed</option>
                </select>
                <button type="submit" class="btn btn-primary">Filter</button>
            </form>
        </div>

        <!-- Task List -->
        <div class="glass-card">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
                <h3 style="margin: 0;"><i class="fas fa-tasks"></i> Your Tasks</h3>
                <span class="badge badge-medium" style="background: rgba(255,255,255,0.1); color: var(--text-color);">
                    <?php echo count($tasks); ?> Found
                </span>
            </div>
            
            <ul class="task-list">
                <?php if (empty($tasks)): ?>
                    <div style="text-align: center; padding: 2rem; opacity: 0.6;">
                        <i class="fas fa-clipboard-list" style="font-size: 3rem; margin-bottom: 1rem;"></i>
                        <p>No tasks found. Create one to get started!</p>
                    </div>
                <?php else: ?>
                    <?php foreach ($tasks as $task): ?>
                        <li class="task-item <?php echo $task['status'] === 'COMPLETED' ? 'completed' : ''; ?>">
                            <div style="flex: 1;">
                                <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 5px;">
                                    <span class="task-title" style="font-weight: 600; font-size: 1.1rem;">
                                        <?php echo htmlspecialchars($task['title']); ?>
                                    </span>
                                    <?php if ($task['priority']): ?>
                                        <?php 
                                            $pClass = 'badge-low';
                                            if ($task['priority'] >= 4) $pClass = 'badge-high';
                                            elseif ($task['priority'] >= 3) $pClass = 'badge-medium';
                                        ?>
                                        <span class="badge <?php echo $pClass; ?>" style="font-size: 0.65rem;">P<?php echo $task['priority']; ?></span>
                                    <?php endif; ?>
                                </div>
                                
                                <div style="font-size: 0.85rem; opacity: 0.7; display: flex; gap: 15px; align-items: center;">
                                    <?php if ($task['category_name']): ?>
                                        <span><i class="fas fa-tag"></i> <?php echo htmlspecialchars($task['category_name']); ?></span>
                                    <?php endif; ?>
                                    <?php if ($task['deadline']): ?>
                                        <span><i class="far fa-clock"></i> <?php echo date('M d, H:i', strtotime($task['deadline'])); ?></span>
                                    <?php endif; ?>
                                </div>
                            </div>
                            
                            <div style="display: flex; gap: 8px;">
                                <a href="index.php?controller=dashboard&action=toggle&id=<?php echo $task['task_id']; ?>&status=<?php echo $task['status']; ?>" 
                                   class="btn btn-sm <?php echo $task['status'] === 'COMPLETED' ? 'btn-primary' : 'btn-success'; ?>"
                                   title="<?php echo $task['status'] === 'COMPLETED' ? 'Mark Pending' : 'Mark Complete'; ?>">
                                    <i class="fas <?php echo $task['status'] === 'COMPLETED' ? 'fa-undo' : 'fa-check'; ?>"></i>
                                </a>
                                <a href="index.php?controller=dashboard&action=edit&id=<?php echo $task['task_id']; ?>" class="btn btn-sm btn-primary" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a href="index.php?controller=dashboard&action=delete&id=<?php echo $task['task_id']; ?>" 
                                   class="btn btn-sm btn-danger" 
                                   onclick="return confirm('Are you sure?');" title="Delete">
                                    <i class="fas fa-trash"></i>
                                </a>
                            </div>
                        </li>
                    <?php endforeach; ?>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</div>

<script>
    // Productivity Chart
    const ctx = document.getElementById('productivityChart').getContext('2d');
    
    new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: ['Completed', 'Pending'],
            datasets: [{
                data: [<?php echo $completedTasks; ?>, <?php echo $pendingTasks; ?>],
                backgroundColor: ['#10b981', '#f59e0b'],
                borderWidth: 0
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: { color: '#f1f5f9' }
                }
            }
        }
    });
</script>

<?php require_once __DIR__ . '/../partials/footer.php'; ?>