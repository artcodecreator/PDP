<?php require_once __DIR__ . '/../partials/header.php'; ?>

<div class="glass-card" style="max-width: 800px; margin: 0 auto;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
        <h2><i class="fas fa-cog"></i> User Preferences</h2>
        <a href="index.php?controller=dashboard&action=index" class="btn btn-sm btn-primary">
            <i class="fas fa-arrow-left"></i> Back to Dashboard
        </a>
    </div>

    <?php if (!empty($errors)): ?>
        <div class="glass-card" style="background: rgba(220, 53, 69, 0.2); padding: 1rem; margin-bottom: 1rem;">
            <?php foreach ($errors as $error): ?>
                <p class="text-danger" style="margin: 0.5rem 0; font-weight: bold;"><?php echo htmlspecialchars($error); ?></p>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <?php if ($success): ?>
        <div class="glass-card" style="background: rgba(16, 185, 129, 0.2); padding: 1rem; margin-bottom: 1rem;">
            <p class="text-success" style="margin: 0; font-weight: bold;"><?php echo htmlspecialchars($success); ?></p>
        </div>
    <?php endif; ?>

    <form method="post">
        <div class="dashboard-grid" style="grid-template-columns: 1fr 1fr; gap: 1rem;">
            <div class="form-group">
                <label class="form-label" for="work_start">Work Start Time</label>
                <input type="time" id="work_start" name="work_start" class="form-control"
                       value="<?php echo htmlspecialchars($prefs['work_start'] ?? ''); ?>">
            </div>
            
            <div class="form-group">
                <label class="form-label" for="work_end">Work End Time</label>
                <input type="time" id="work_end" name="work_end" class="form-control"
                       value="<?php echo htmlspecialchars($prefs['work_end'] ?? ''); ?>">
            </div>
        </div>
        
        <div class="form-group">
            <label class="form-label" for="default_reminder_minutes">Default Reminder (minutes)</label>
            <input type="number" id="default_reminder_minutes" name="default_reminder_minutes" class="form-control"
                   value="<?php echo (int) ($prefs['default_reminder_minutes'] ?? 15); ?>" min="0">
        </div>
        
        <div class="form-group">
            <label class="form-label" for="timezone">Timezone</label>
            <select id="timezone" name="timezone" class="form-control">
                <option value="UTC" <?php echo ($prefs['timezone'] ?? 'UTC') === 'UTC' ? 'selected' : ''; ?>>UTC</option>
                <option value="America/New_York" <?php echo ($prefs['timezone'] ?? '') === 'America/New_York' ? 'selected' : ''; ?>>New York (EST/EDT)</option>
                <option value="Europe/London" <?php echo ($prefs['timezone'] ?? '') === 'Europe/London' ? 'selected' : ''; ?>>London (GMT/BST)</option>
                <option value="Asia/Tokyo" <?php echo ($prefs['timezone'] ?? '') === 'Asia/Tokyo' ? 'selected' : ''; ?>>Tokyo (JST)</option>
            </select>
        </div>
        
        <div style="margin-top: 2rem; text-align: right;">
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i> Save Preferences
            </button>
        </div>
    </form>
</div>

<?php require_once __DIR__ . '/../partials/footer.php'; ?>
