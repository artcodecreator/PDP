<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Models\User;

class AuthController extends Controller
{
    public function index(): void
    {
        $this->render('auth/index', [
            'errors' => [],
            'success' => null,
        ]);
    }

    public function login(): void
    {
        $errors = [];
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = trim($_POST['email'] ?? '');
            $password = $_POST['password'] ?? '';

            if ($email === '' || $password === '') {
                $errors[] = 'Email and password are required.';
            } else {
                $user = User::findByEmail($email);

                if ($user === null || !password_verify($password, $user['password_hash'])) {
                    $errors[] = 'Invalid email or password.';
                } elseif ((int) $user['is_active'] !== 1) {
                    $errors[] = 'This account is not active.';
                } else {
                    $_SESSION['user_id'] = (int) $user['user_id'];
                    $_SESSION['user_name'] = $user['full_name'];
                    $_SESSION['user_role'] = $user['role'];

                    header('Location: index.php?controller=dashboard&action=index');
                    exit;
                }
            }
        }

        $this->render('auth/index', [
            'errors' => $errors,
            'success' => null,
        ]);
    }

    public function register(): void
    {
        $errors = [];
        $success = null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $fullName = trim($_POST['name'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $password = $_POST['password'] ?? '';

            if ($fullName === '' || $email === '' || $password === '') {
                $errors[] = 'All registration fields are required.';
            } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errors[] = 'Email is not valid.';
            }

            if (!$errors) {
                $existing = User::findByEmail($email);

                if ($existing !== null) {
                    $errors[] = 'An account with this email already exists.';
                } else {
                    User::create($fullName, $email, $password);
                    $success = 'Registration successful. You can now sign in.';
                }
            }
        }

        $this->render('auth/index', [
            'errors' => $errors,
            'success' => $success,
        ]);
    }

    public function logout(): void
    {
        $_SESSION = [];
        session_destroy();

        header('Location: index.php');
        exit;
    }
}
