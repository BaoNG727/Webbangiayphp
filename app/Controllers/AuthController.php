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
                } else {                    // Create user
                    $userData = [
                        'name' => $firstName . ' ' . $lastName,
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

    public function profile()
    {
        $this->requireLogin();
        
        $userModel = $this->model('User');
        $orderModel = $this->model('Order');
        $userId = $this->session('user_id');
        $user = $userModel->find($userId);
        
        if (!$user) {
            $this->redirect('/Webgiay/login');
            return;
        }
        
        // Get user statistics
        $orderCount = $orderModel->count(['user_id' => $userId]);
        
        $success = '';
        $error = '';
        
        // Handle profile update
        if ($this->isPost()) {
            $firstName = trim($this->input('first_name'));
            $lastName = trim($this->input('last_name'));
            $email = trim($this->input('email'));
            $currentPassword = $this->input('current_password');
            $newPassword = $this->input('new_password');
            $confirmPassword = $this->input('confirm_password');
            
            $errors = [];
            
            // Validate required fields
            if (empty($firstName)) {
                $errors[] = 'First name is required';
            }
            if (empty($lastName)) {
                $errors[] = 'Last name is required';
            }
            if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errors[] = 'Valid email is required';
            }
            
            // Check if email is already taken by another user
            if ($userModel->isEmailTaken($email, $userId)) {
                $errors[] = 'Email is already taken';
            }
            
            // Password update validation
            if (!empty($newPassword)) {
                if (empty($currentPassword)) {
                    $errors[] = 'Current password is required to set new password';
                } elseif (!$userModel->verifyPassword($currentPassword, $user['password'])) {
                    $errors[] = 'Current password is incorrect';
                } elseif (strlen($newPassword) < 6) {
                    $errors[] = 'New password must be at least 6 characters long';
                } elseif ($newPassword !== $confirmPassword) {
                    $errors[] = 'New password confirmation does not match';
                }
            }
            
            if (empty($errors)) {
                // Update user data
                $updateData = [
                    'first_name' => $firstName,
                    'last_name' => $lastName,
                    'email' => $email
                ];
                
                // Update password if provided
                if (!empty($newPassword)) {
                    $updateData['password'] = password_hash($newPassword, PASSWORD_DEFAULT);
                }
                
                if ($userModel->update($userId, $updateData)) {
                    // Update session email if changed
                    if ($email !== $user['email']) {
                        $this->session('email', $email);
                    }
                    
                    $success = 'Profile updated successfully';
                    // Refresh user data
                    $user = $userModel->find($userId);
                } else {
                    $error = 'Failed to update profile';
                }
            } else {
                $error = implode('<br>', $errors);
            }
        }
        
        $data = [
            'title' => 'My Profile - Nike Shoe Store',
            'user' => $user,
            'order_count' => $orderCount,
            'success' => $success,
            'error' => $error
        ];
        
        $this->view('layouts/header', $data);
        $this->view('users/profile', $data);
        $this->view('layouts/footer');
    }
}
