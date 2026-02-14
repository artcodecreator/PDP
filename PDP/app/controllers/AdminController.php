<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Models\User;
use App\Models\Task;
use App\Models\Template;
use App\Models\SystemLog;
use App\Models\MLModel;
use App\Models\Category;

class AdminController extends Controller
{
    public function index(): void
    {
        $this->requireAdmin();

        // System Stats
        $users = User::all();
        $totalUsers = count($users);
        $activeUsers = count(array_filter($users, fn($u) => $u['is_active']));
        
        // Mock "Common Tasks" (Top 5 categories distribution)
        $categories = Category::all();
        
        // ML Overview
        $mlModel = MLModel::latest();

        $this->render('admin/index', [
            'stats' => [
                'total_users' => $totalUsers,
                'active_users' => $activeUsers,
                'total_tasks' => count(Task::forUser($_SESSION['user_id'])), // This should ideally be system-wide count
            ],
            'ml_model' => $mlModel,
            'recent_logs' => SystemLog::all(5)
        ]);
    }

    public function users(): void
    {
        $this->requireAdmin();
        $users = User::all();
        $this->render('admin/users', ['users' => $users]);
    }

    public function toggleUser(): void
    {
        $this->requireAdmin();
        $userId = (int) ($_GET['id'] ?? 0);
        if ($userId && $userId !== (int)$_SESSION['user_id']) {
            User::toggleStatus($userId);
            SystemLog::log($_SESSION['user_id'], 'Admin', 'TOGGLE_USER', 'INFO', ['target_user_id' => $userId]);
        }
        header('Location: index.php?controller=admin&action=users');
        exit;
    }

    public function deleteUser(): void
    {
        $this->requireAdmin();
        $userId = (int) ($_GET['id'] ?? 0);
        if ($userId && $userId !== (int)$_SESSION['user_id']) {
            User::delete($userId);
            SystemLog::log($_SESSION['user_id'], 'Admin', 'DELETE_USER', 'WARNING', ['target_user_id' => $userId]);
        }
        header('Location: index.php?controller=admin&action=users');
        exit;
    }

    public function templates(): void
    {
        $this->requireAdmin();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = trim($_POST['name'] ?? '');
            if ($name) {
                Template::create($_SESSION['user_id'], $name);
                SystemLog::log($_SESSION['user_id'], 'Admin', 'CREATE_TEMPLATE', 'INFO', ['name' => $name]);
            }
        }

        $templates = Template::all();
        $this->render('admin/templates', ['templates' => $templates]);
    }

    public function deleteTemplate(): void
    {
        $this->requireAdmin();
        $id = (int)($_GET['id'] ?? 0);
        if ($id) {
            Template::delete($id);
            SystemLog::log($_SESSION['user_id'], 'Admin', 'DELETE_TEMPLATE', 'INFO', ['template_id' => $id]);
        }
        header('Location: index.php?controller=admin&action=templates');
        exit;
    }

    public function ml(): void
    {
        $this->requireAdmin();
        
        // Mock Retraining Trigger
        if (isset($_GET['action']) && $_GET['action'] === 'retrain') {
            MLModel::retrain(
                'TaskScheduler_v2', 
                '2.0.' . rand(1, 100), 
                ['learning_rate' => 0.01, 'epochs' => 100], 
                ['accuracy' => rand(80, 99) / 100, 'loss' => rand(1, 20) / 100]
            );
            SystemLog::log($_SESSION['user_id'], 'Admin', 'ML_RETRAIN', 'INFO', ['version' => '2.0']);
            header('Location: index.php?controller=admin&action=ml');
            exit;
        }

        $model = MLModel::latest();
        $this->render('admin/ml', ['model' => $model]);
    }

    public function logs(): void
    {
        $this->requireAdmin();
        $logs = SystemLog::all(100);
        $this->render('admin/logs', ['logs' => $logs]);
    }

    private function requireAdmin(): void
    {
        if (!isset($_SESSION['user_id']) || !isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'Admin') {
            header('Location: index.php?controller=dashboard&action=index');
            exit;
        }
    }
}
