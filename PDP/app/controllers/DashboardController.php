<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Category;
use App\Models\Task;

class DashboardController extends Controller
{
    public function index(): void
    {
        if (!isset($_SESSION['user_id'])) {
            header('Location: index.php');
            exit;
        }

        $errors = [];
        $userId = (int) $_SESSION['user_id'];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $title = trim($_POST['title'] ?? '');
            $deadline = trim($_POST['deadline'] ?? '');
            $priority = trim($_POST['priority'] ?? '');
            $categoryId = trim($_POST['category_id'] ?? '');

            if ($title === '') {
                $errors[] = 'Task title is required.';
            }

            $deadlineValue = $deadline !== '' ? $deadline : null;
            $priorityValue = $priority !== '' ? (int) $priority : null;
            $categoryIdValue = $categoryId !== '' ? (int) $categoryId : null;

            if (!$errors) {
                Task::create($userId, $title, $deadlineValue, $priorityValue, $categoryIdValue);
                header('Location: index.php?controller=dashboard&action=index');
                exit;
            }
        }

        $categories = Category::all();
        
        $search = trim($_GET['search'] ?? '');
        $status = trim($_GET['filter_status'] ?? '');
        
        $tasks = Task::forUser($userId, $search, $status);

        $this->render('dashboard/index', [
            'errors' => $errors,
            'categories' => $categories,
            'tasks' => $tasks,
            'userName' => $_SESSION['user_name'] ?? '',
        ]);
    }

    public function toggle(): void
    {
        $this->requireAuth();
        $taskId = (int) ($_GET['id'] ?? 0);
        $userId = (int) $_SESSION['user_id'];
        $currentStatus = $_GET['status'] ?? 'PENDING';
        
        $newStatus = ($currentStatus === 'COMPLETED') ? 'PENDING' : 'COMPLETED';
        
        Task::updateStatus($taskId, $userId, $newStatus);
        header('Location: index.php?controller=dashboard&action=index');
        exit;
    }

    public function delete(): void
    {
        $this->requireAuth();
        $taskId = (int) ($_GET['id'] ?? 0);
        $userId = (int) $_SESSION['user_id'];

        Task::delete($taskId, $userId);
        header('Location: index.php?controller=dashboard&action=index');
        exit;
    }

    public function edit(): void
    {
        $this->requireAuth();
        $userId = (int) $_SESSION['user_id'];
        $taskId = (int) ($_GET['id'] ?? 0);
        $errors = [];

        $task = Task::find($taskId, $userId);
        if (!$task) {
            header('Location: index.php?controller=dashboard&action=index');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $title = trim($_POST['title'] ?? '');
            $deadline = trim($_POST['deadline'] ?? '');
            $priority = trim($_POST['priority'] ?? '');
            $categoryId = trim($_POST['category_id'] ?? '');

            if ($title === '') {
                $errors[] = 'Task title is required.';
            }

            $deadlineValue = $deadline !== '' ? $deadline : null;
            $priorityValue = $priority !== '' ? (int) $priority : null;
            $categoryIdValue = $categoryId !== '' ? (int) $categoryId : null;

            if (!$errors) {
                Task::update($taskId, $userId, $title, $deadlineValue, $priorityValue, $categoryIdValue);
                header('Location: index.php?controller=dashboard&action=index');
                exit;
            }
        }

        $categories = Category::all();
        $this->render('dashboard/edit', [
            'errors' => $errors,
            'task' => $task,
            'categories' => $categories,
            'userName' => $_SESSION['user_name'] ?? '',
        ]);
    }

    private function requireAuth(): void
    {
        if (!isset($_SESSION['user_id'])) {
            header('Location: index.php');
            exit;
        }
    }
}

