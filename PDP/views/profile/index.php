<?php require_once __DIR__ . '/../partials/header.php'; ?>

<div class="container" style="max-width: 800px;">
    <div class="glass-card">
        <h2><i class="fas fa-user-cog"></i> Profile & Preferences</h2>
        
        <?php if ($success): ?>
            <div class="glass-card" style="background: rgba(40, 167, 69, 0.2); padding: 1rem;">
                <p class="text-success" style="margin: 0; font-weight: bold;"><?php echo htmlspecialchars($success); ?></p>
            </div>
        <?php endif; ?>

        <form action="index.php?controller=profile&action=index" method="post">
            <h3><i class="fas fa-clock"></i> Daily Routine</h3>
            <div class="dashboard-grid">
                <div class="form-group">
                    <label class="form-label">Work Start Time</label>
                    <input type="time" name="work_start" class="form-control" value="<?php echo $preferences['work_start'] ?? ''; ?>">
                </div>
                <div class="form-group">
                    <label class="form-label">Work End Time</label>
                    <input type="time" name="work_end" class="form-control" value="<?php echo $preferences['work_end'] ?? ''; ?>">
                </div>
            </div>

            <h3><i class="fas fa-bell"></i> Notifications</h3>
            <div class="form-group">
                <label class="form-label">Default Reminder (Minutes before)</label>
                <select name="default_reminder" class="form-control">
                    <?php 
                        $current = $preferences['default_reminder_minutes'] ?? 15;
                        $options = [5, 10, 15, 30, 60, 120];
                        foreach ($options as $opt): 
                    ?>
                        <option value="<?php echo $opt; ?>" <?php echo $current == $opt ? 'selected' : ''; ?>>
                            <?php echo $opt; ?> minutes
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label class="form-label">Timezone</label>
                <select name="timezone" class="form-control">
                    <option value="UTC" <?php echo ($preferences['timezone'] ?? 'UTC') === 'UTC' ? 'selected' : ''; ?>>UTC</option>
                    <option value="America/New_York" <?php echo ($preferences['timezone'] ?? '') === 'America/New_York' ? 'selected' : ''; ?>>New York (EST)</option>
                    <option value="Europe/London" <?php echo ($preferences['timezone'] ?? '') === 'Europe/London' ? 'selected' : ''; ?>>London (GMT)</option>
                    <option value="Asia/Tokyo" <?php echo ($preferences['timezone'] ?? '') === 'Asia/Tokyo' ? 'selected' : ''; ?>>Tokyo (JST)</option>
                    <!-- Add more as needed -->
                </select>
            </div>

            <button type="submit" class="btn btn-primary">Save Preferences</button>
        </form>
    </div>
</div>

<?php require_once __DIR__ . '/../partials/footer.php'; ?>
