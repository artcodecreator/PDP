CREATE DATABASE IF NOT EXISTS personalized_daily_planner
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE personalized_daily_planner;

CREATE TABLE users (
  user_id INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  email VARCHAR(255) NOT NULL UNIQUE,
  password_hash VARCHAR(255) NOT NULL,
  full_name VARCHAR(255) NOT NULL,
  role VARCHAR(20) NOT NULL DEFAULT 'User',
  is_active TINYINT(1) NOT NULL DEFAULT 1,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE user_preferences (
  user_id INT UNSIGNED NOT NULL PRIMARY KEY,
  work_start TIME,
  work_end TIME,
  focus_blocks JSON,
  default_reminder_minutes INT DEFAULT 15,
  timezone VARCHAR(64) DEFAULT 'UTC',
  CONSTRAINT fk_user_preferences_user
    FOREIGN KEY (user_id) REFERENCES users(user_id)
    ON DELETE CASCADE
);

CREATE TABLE categories (
  category_id INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL UNIQUE
);

INSERT INTO categories (name) VALUES
  ('Work'),
  ('Study'),
  ('Health'),
  ('Personal')
ON DUPLICATE KEY UPDATE name = VALUES(name);

CREATE TABLE tasks (
  task_id INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  user_id INT UNSIGNED NOT NULL,
  title VARCHAR(255) NOT NULL,
  notes TEXT,
  deadline DATETIME,
  duration_minutes INT,
  priority TINYINT,
  category_id INT UNSIGNED,
  status VARCHAR(20) NOT NULL DEFAULT 'PENDING',
  recurrence_rule VARCHAR(255),
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  CONSTRAINT fk_tasks_user
    FOREIGN KEY (user_id) REFERENCES users(user_id)
    ON DELETE CASCADE,
  CONSTRAINT fk_tasks_category
    FOREIGN KEY (category_id) REFERENCES categories(category_id)
    ON DELETE SET NULL,
  CONSTRAINT chk_tasks_priority
    CHECK (priority BETWEEN 1 AND 5)
);

CREATE TABLE reminders (
  reminder_id INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  task_id INT UNSIGNED NOT NULL,
  user_id INT UNSIGNED NOT NULL,
  scheduled_at DATETIME NOT NULL,
  channel VARCHAR(20) NOT NULL DEFAULT 'IN_APP',
  status VARCHAR(20) NOT NULL DEFAULT 'QUEUED',
  sent_at DATETIME,
  CONSTRAINT fk_reminders_task
    FOREIGN KEY (task_id) REFERENCES tasks(task_id)
    ON DELETE CASCADE,
  CONSTRAINT fk_reminders_user
    FOREIGN KEY (user_id) REFERENCES users(user_id)
    ON DELETE CASCADE
);

CREATE TABLE templates (
  template_id INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  user_id INT UNSIGNED NOT NULL,
  name VARCHAR(255) NOT NULL,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT fk_templates_user
    FOREIGN KEY (user_id) REFERENCES users(user_id)
    ON DELETE CASCADE
);

CREATE TABLE template_items (
  template_item_id INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  template_id INT UNSIGNED NOT NULL,
  title VARCHAR(255) NOT NULL,
  duration_minutes INT,
  priority TINYINT,
  category_id INT UNSIGNED,
  CONSTRAINT fk_template_items_template
    FOREIGN KEY (template_id) REFERENCES templates(template_id)
    ON DELETE CASCADE,
  CONSTRAINT fk_template_items_category
    FOREIGN KEY (category_id) REFERENCES categories(category_id)
    ON DELETE SET NULL
);

CREATE TABLE task_history (
  history_id INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  task_id INT UNSIGNED NOT NULL,
  user_id INT UNSIGNED NOT NULL,
  action VARCHAR(30) NOT NULL,
  occurred_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  details JSON,
  CONSTRAINT fk_task_history_task
    FOREIGN KEY (task_id) REFERENCES tasks(task_id)
    ON DELETE CASCADE,
  CONSTRAINT fk_task_history_user
    FOREIGN KEY (user_id) REFERENCES users(user_id)
    ON DELETE CASCADE
);

CREATE TABLE ml_models (
  model_id INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL,
  version VARCHAR(50) NOT NULL,
  params JSON,
  metrics JSON,
  updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE ml_predictions (
  prediction_id INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  user_id INT UNSIGNED NOT NULL,
  task_id INT UNSIGNED,
  type VARCHAR(30) NOT NULL,
  payload JSON NOT NULL,
  confidence DECIMAL(4,3),
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT fk_ml_predictions_user
    FOREIGN KEY (user_id) REFERENCES users(user_id)
    ON DELETE CASCADE,
  CONSTRAINT fk_ml_predictions_task
    FOREIGN KEY (task_id) REFERENCES tasks(task_id)
    ON DELETE SET NULL
);

CREATE TABLE system_logs (
  log_id INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  actor_id INT UNSIGNED,
  actor_role VARCHAR(20),
  action VARCHAR(100) NOT NULL,
  severity VARCHAR(10) DEFAULT 'INFO',
  occurred_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  context JSON
);

CREATE INDEX idx_tasks_user_status ON tasks (user_id, status);
CREATE INDEX idx_tasks_user_deadline ON tasks (user_id, deadline);
CREATE INDEX idx_tasks_deadline ON tasks (deadline);
CREATE INDEX idx_reminders_scheduled_at ON reminders (scheduled_at);
