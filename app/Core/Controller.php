<?php

class Controller
{
    protected $db;

    public function __construct()
    {
        $this->db = new Database();
    }

    protected function view($view, $data = [])
    {
        // Extract data to variables
        extract($data);
        
        // Check if view file exists
        $viewFile = __DIR__ . "/../Views/{$view}.php";
        if (file_exists($viewFile)) {
            require_once $viewFile;
        } else {
            throw new Exception("View {$view} not found");
        }
    }

    protected function model($model)
    {
        $modelFile = __DIR__ . "/../Models/{$model}.php";
        if (file_exists($modelFile)) {
            require_once $modelFile;
            return new $model();
        } else {
            throw new Exception("Model {$model} not found");
        }
    }

    protected function redirect($url)
    {
        header("Location: {$url}");
        exit;
    }

    protected function json($data)
    {
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }

    protected function isPost()
    {
        return $_SERVER['REQUEST_METHOD'] === 'POST';
    }

    protected function isGet()
    {
        return $_SERVER['REQUEST_METHOD'] === 'GET';
    }

    protected function input($key, $default = null)
    {
        return $_POST[$key] ?? $_GET[$key] ?? $default;
    }

    protected function session($key, $value = null)
    {
        if ($value !== null) {
            $_SESSION[$key] = $value;
        }
        return $_SESSION[$key] ?? null;
    }

    protected function isLoggedIn()
    {
        return isset($_SESSION['user_id']);
    }

    protected function requireLogin()
    {
        if (!$this->isLoggedIn()) {
            $this->redirect('/Webgiay/login');
        }
    }

    protected function isAdmin()
    {
        return $this->isLoggedIn() && $_SESSION['role'] === 'admin';
    }

    protected function requireAdmin()
    {
        if (!$this->isAdmin()) {
            $this->redirect('/Webgiay/login');
        }
    }
}
