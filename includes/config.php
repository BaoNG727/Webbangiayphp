<?php
/**
 * Database Configuration File
 * File cấu hình kết nối database cho Nike Shoe Store
 */

// Database Configuration
define('DB_HOST', 'localhost');
define('DB_NAME', 'nike_store'); // Database name corrected to match existing database
define('DB_USER', 'root');
define('DB_PASS', '');

// Site Configuration
define('SITE_NAME', 'Nike Shoe Store');
define('SITE_URL', 'http://localhost/Webgiay');
define('BASE_PATH', '/Webgiay');

// Security
define('SECURE', false); // Set to true in production
define('SESSION_LIFETIME', 86400); // 24 hours

try {
    // Create PDO connection
    $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4";
    $options = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
    ];
    
    $pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
    
    // Also create mysqli connection for backward compatibility
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    
    // Check mysqli connection
    if ($conn->connect_error) {
        throw new Exception("Connection failed: " . $conn->connect_error);
    }
    
    // Set charset
    $conn->set_charset("utf8mb4");
    
} catch (PDOException $e) {
    // Log error and show user-friendly message
    error_log("Database connection error: " . $e->getMessage());
    die("
    <!DOCTYPE html>
    <html lang='vi'>
    <head>
        <meta charset='UTF-8'>
        <meta name='viewport' content='width=device-width, initial-scale=1.0'>
        <title>Lỗi Kết Nối Database</title>
        <link href='https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css' rel='stylesheet'>
    </head>
    <body class='bg-light'>
        <div class='container mt-5'>
            <div class='row justify-content-center'>
                <div class='col-md-8'>
                    <div class='alert alert-danger'>
                        <h4>❌ Lỗi kết nối database!</h4>
                        <p><strong>Lỗi:</strong> Không thể kết nối đến database.</p>
                        <hr>
                        <h5>🔧 Hướng dẫn khắc phục:</h5>
                        <ol>
                            <li><strong>Khởi động XAMPP:</strong>
                                <ul>
                                    <li>Mở XAMPP Control Panel</li>
                                    <li>Start Apache và MySQL services</li>
                                </ul>
                            </li>
                            <li><strong>Kiểm tra database:</strong>
                                <ul>
                                    <li>Truy cập: <code>http://localhost/phpmyadmin</code></li>
                                    <li>Tạo database: <code>" . DB_NAME . "</code></li>
                                    <li>Import file: <code>database.sql</code></li>
                                </ul>
                            </li>
                            <li><strong>Kiểm tra cấu hình:</strong>
                                <ul>
                                    <li>Database name: <code>" . DB_NAME . "</code></li>
                                    <li>Username: <code>" . DB_USER . "</code></li>
                                    <li>Password: <code>" . (DB_PASS ?: '(trống)') . "</code></li>
                                </ul>
                            </li>
                        </ol>
                        <div class='mt-3'>
                            <a href='http://localhost/phpmyadmin' class='btn btn-primary' target='_blank'>
                                🔗 Mở phpMyAdmin
                            </a>
                            <a href='http://localhost/Webgiay/database.sql' class='btn btn-secondary' target='_blank'>
                                📄 Xem Database SQL
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </body>
    </html>
    ");
} catch (Exception $e) {
    error_log("Database connection error: " . $e->getMessage());
    die("Lỗi kết nối database. Vui lòng kiểm tra cấu hình.");
}

// Helper functions
function redirect($url) {
    header("Location: " . SITE_URL . $url);
    exit;
}

function sanitize_input($data) {
    return htmlspecialchars(strip_tags(trim($data)));
}

function is_logged_in() {
    return isset($_SESSION['user_id']);
}

function is_admin() {
    return isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
}

function require_login() {
    if (!is_logged_in()) {
        redirect('/login');
    }
}

function require_admin() {
    if (!is_admin()) {
        redirect('/login');
    }
}

// Set timezone
date_default_timezone_set('Asia/Ho_Chi_Minh');

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    ini_set('session.cookie_httponly', 1);
    if (SECURE) {
        ini_set('session.cookie_secure', 1);
    }
    session_start();
}
?>
