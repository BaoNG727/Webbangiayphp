<?php

require_once __DIR__ . '/../Core/Controller.php';
require_once __DIR__ . '/../Core/Database.php';

class AuthController extends Controller
{
    public function login()
    {
        if ($this->isLoggedIn()) {
            $this->redirect('/Webgiay/');
        }

        $error = '';
        $redirect = $this->input('redirect', '');

        if ($this->isPost()) {
            $username = trim($this->input('username'));
            $password = $this->input('password');

            if (empty($username) || empty($password)) {
                $error = "Please enter both username and password";
            } else {
                $userModel = $this->model('User');
                $user = $userModel->findByUsername($username);

                if ($user && $userModel->verifyPassword($password, $user['password'])) {
                    // Set session
                    $this->session('user_id', $user['id']);
                    $this->session('username', $user['username']);
                    $this->session('email', $user['email']);
                    $this->session('role', $user['role']);

                    // Redirect
                    $redirectUrl = !empty($redirect) ? $redirect : '/Webgiay/';
                    $this->redirect($redirectUrl);
                } else {
                    $error = "Invalid username or password";
                }
            }
        }

        $data = [
            'title' => 'Login - Nike Shoe Store',
            'error' => $error,
            'redirect' => $redirect
        ];

        $this->view('layouts/header', $data);
        $this->view('users/login', $data);
        $this->view('layouts/footer');
    }

    public function register()
    {
        if ($this->isLoggedIn()) {
            $this->redirect('/Webgiay/');
        }

        $error = '';
        $success = false;
        $redirect = $this->input('redirect', '');

        if ($this->isPost()) {
            $username = trim($this->input('username'));
            $email = trim($this->input('email'));
            $password = $this->input('password');
            $confirmPassword = $this->input('confirm_password');
            $firstName = trim($this->input('first_name'));
            $lastName = trim($this->input('last_name'));

            // Validation
            if (empty($username) || empty($email) || empty($password) || empty($firstName) || empty($lastName)) {
                $error = "All fields are required";
            } elseif ($password !== $confirmPassword) {
                $error = "Passwords do not match";
            } elseif (strlen($password) < 6) {
                $error = "Password must be at least 6 characters long";
            } else {
                $userModel = $this->model('User');

                // Check if username or email already exists
                if ($userModel->isUsernameTaken($username)) {
                    $error = "Username already exists";
                } elseif ($userModel->isEmailTaken($email)) {
                    $error = "Email already exists";
                } else {
                    // Create user
                    $userData = [
                        'username' => $username,
                        'email' => $email,
                        'password' => $password,
                        'first_name' => $firstName,
                        'last_name' => $lastName,
                        'role' => 'customer'
                    ];

                    $userId = $userModel->createUser($userData);

                    if ($userId) {
                        $success = true;

                        // Auto login
                        $this->session('user_id', $userId);
                        $this->session('username', $username);
                        $this->session('email', $email);
                        $this->session('role', 'customer');

                        // Redirect after 2 seconds
                        $redirectUrl = !empty($redirect) ? $redirect : '/Webgiay/';
                        header("Refresh: 2; URL=$redirectUrl");
                    } else {
                        $error = "Registration failed. Please try again.";
                    }
                }
            }
        }

        $data = [
            'title' => 'Register - Nike Shoe Store',
            'error' => $error,
            'success' => $success,
            'redirect' => $redirect
        ];

        $this->view('layouts/header', $data);
        $this->view('users/register', $data);
        $this->view('layouts/footer');
    }

    public function logout()
    {
        session_destroy();
        $this->redirect('/Webgiay/login');
    }
}
