<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Models\UserPreference;

class PreferencesController extends Controller
{
    public function index(): void
    {
        if (!isset($_SESSION['user_id'])) {
            header('Location: index.php');
            exit;
        }
        
        $userId = (int) $_SESSION['user_id'];
        $errors = [];
        $success = null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $workStart = !empty($_POST['work_start']) ? $_POST['work_start'] : null;
            $workEnd = !empty($_POST['work_end']) ? $_POST['work_end'] : null;
            $reminder = (int) ($_POST['default_reminder_minutes'] ?? 15);
            $timezone = $_POST['timezone'] ?? 'UTC';

            try {
                UserPreference::save($userId, $workStart, $workEnd, $reminder, $timezone);
                $success = 'Preferences saved successfully.';
            } catch (\Exception $e) {
                $errors[] = 'Failed to save preferences.';
            }
        }

        $prefs = UserPreference::find($userId);

        $this->render('preferences/index', [
            'prefs' => $prefs,
            'errors' => $errors,
            'success' => $success,
            'userName' => $_SESSION['user_name'] ?? ''
        ]);
    }
}
