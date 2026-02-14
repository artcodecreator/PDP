<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Models\User;
use App\Models\UserPreference;

class ProfileController extends Controller
{
    public function index(): void
    {
        if (!isset($_SESSION['user_id'])) {
            header('Location: index.php');
            exit;
        }

        $userId = (int) $_SESSION['user_id'];
        $success = null;
        $errors = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $workStart = $_POST['work_start'] ?: null;
            $workEnd = $_POST['work_end'] ?: null;
            $reminder = (int) $_POST['default_reminder'];
            $timezone = $_POST['timezone'] ?? 'UTC';

            UserPreference::save($userId, $workStart, $workEnd, $reminder, $timezone);
            $success = "Preferences updated successfully!";
        }

        $preferences = UserPreference::find($userId);
        
        $this->render('profile/index', [
            'preferences' => $preferences,
            'success' => $success,
            'errors' => $errors
        ]);
    }
}
