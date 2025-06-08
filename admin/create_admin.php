<?php
/**
 * Create New Admin Account
 * Script để tạo tài khoản admin mới
 */

// Database configuration
$host = 'localhost';
$dbname = 'shoe_store'; // Hoặc 'nike_shoe_store' tùy vào database bạn đang dùng
$username = 'root';
$password = '';

try {
    // Kết nối database
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "<!DOCTYPE html>
    <html lang='vi'>
    <head>
        <meta charset='UTF-8'>
        <meta name='viewport' content='width=device-width, initial-scale=1.0'>
        <title>Tạo Tài Khoản Admin</title>
        <link href='https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css' rel='stylesheet'>
        <style>
            body { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); min-height: 100vh; }
            .card { border-radius: 15px; box-shadow: 0 10px 30px rgba(0,0,0,0.1); }
            .btn-nike { background: #111; color: white; border: none; }
            .btn-nike:hover { background: #333; color: white; }
        </style>
    </head>
    <body>
        <div class='container mt-5'>
            <div class='row justify-content-center'>
                <div class='col-md-6'>
                    <div class='card'>
                        <div class='card-header bg-dark text-white text-center'>
                            <h3>🔐 Tạo Tài Khoản Admin</h3>
                        </div>
                        <div class='card-body'>";

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $admin_username = trim($_POST['username']);
        $admin_email = trim($_POST['email']);
        $admin_password = $_POST['password'];
        $admin_first_name = trim($_POST['first_name']);
        $admin_last_name = trim($_POST['last_name']);
        
        // Validate input
        if (empty($admin_username) || empty($admin_email) || empty($admin_password)) {
            echo "<div class='alert alert-danger'>Vui lòng điền đầy đủ thông tin bắt buộc!</div>";
        } else if (strlen($admin_password) < 6) {
            echo "<div class='alert alert-danger'>Mật khẩu phải có ít nhất 6 ký tự!</div>";
        } else {
            // Check if username or email already exists
            $check_stmt = $pdo->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
            $check_stmt->execute([$admin_username, $admin_email]);
            
            if ($check_stmt->rowCount() > 0) {
                echo "<div class='alert alert-warning'>Username hoặc email đã tồn tại!</div>";
            } else {
                // Hash password
                $hashed_password = password_hash($admin_password, PASSWORD_DEFAULT);
                
                // Insert new admin
                $insert_stmt = $pdo->prepare("
                    INSERT INTO users (username, email, password, first_name, last_name, role, created_at) 
                    VALUES (?, ?, ?, ?, ?, 'admin', NOW())
                ");
                
                if ($insert_stmt->execute([$admin_username, $admin_email, $hashed_password, $admin_first_name, $admin_last_name])) {
                    echo "<div class='alert alert-success'>
                        <h5>✅ Tạo tài khoản admin thành công!</h5>
                        <hr>
                        <p><strong>Username:</strong> $admin_username</p>
                        <p><strong>Email:</strong> $admin_email</p>
                        <p><strong>Họ tên:</strong> $admin_first_name $admin_last_name</p>
                        <p><strong>Role:</strong> Admin</p>
                        <hr>
                        <p class='mb-0'>Bạn có thể đăng nhập với thông tin trên.</p>
                    </div>";
                } else {
                    echo "<div class='alert alert-danger'>Có lỗi xảy ra khi tạo tài khoản!</div>";
                }
            }
        }
    }

    // Show form
    echo "<form method='POST' class='mt-3'>
        <div class='mb-3'>
            <label for='username' class='form-label'>Username <span class='text-danger'>*</span></label>
            <input type='text' class='form-control' id='username' name='username' required 
                   placeholder='Nhập username' value='" . ($_POST['username'] ?? '') . "'>
        </div>
        
        <div class='mb-3'>
            <label for='email' class='form-label'>Email <span class='text-danger'>*</span></label>
            <input type='email' class='form-control' id='email' name='email' required 
                   placeholder='Nhập email' value='" . ($_POST['email'] ?? '') . "'>
        </div>
        
        <div class='mb-3'>
            <label for='password' class='form-label'>Mật khẩu <span class='text-danger'>*</span></label>
            <input type='password' class='form-control' id='password' name='password' required 
                   placeholder='Nhập mật khẩu (tối thiểu 6 ký tự)'>
        </div>
        
        <div class='row'>
            <div class='col-md-6 mb-3'>
                <label for='first_name' class='form-label'>Tên</label>
                <input type='text' class='form-control' id='first_name' name='first_name' 
                       placeholder='Nhập tên' value='" . ($_POST['first_name'] ?? '') . "'>
            </div>
            <div class='col-md-6 mb-3'>
                <label for='last_name' class='form-label'>Họ</label>
                <input type='text' class='form-control' id='last_name' name='last_name' 
                       placeholder='Nhập họ' value='" . ($_POST['last_name'] ?? '') . "'>
            </div>
        </div>
        
        <div class='d-grid gap-2'>
            <button type='submit' class='btn btn-nike btn-lg'>
                🚀 Tạo Tài Khoản Admin
            </button>
        </div>
    </form>";

    echo "
                        </div>
                        <div class='card-footer text-center text-muted'>
                            <small>Nike Shoe Store - Admin Management</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Current Admin Accounts -->
        <div class='container mt-4'>
            <div class='row justify-content-center'>
                <div class='col-md-8'>
                    <div class='card'>
                        <div class='card-header bg-info text-white'>
                            <h5>👥 Danh Sách Admin Hiện Tại</h5>
                        </div>
                        <div class='card-body'>
                            <div class='table-responsive'>";

    // Show current admin accounts
    $admin_stmt = $pdo->prepare("SELECT id, username, email, first_name, last_name, created_at FROM users WHERE role = 'admin' ORDER BY created_at DESC");
    $admin_stmt->execute();
    $admins = $admin_stmt->fetchAll(PDO::FETCH_ASSOC);

    if (count($admins) > 0) {
        echo "<table class='table table-striped'>
            <thead class='table-dark'>
                <tr>
                    <th>ID</th>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Họ Tên</th>
                    <th>Ngày Tạo</th>
                </tr>
            </thead>
            <tbody>";
        
        foreach ($admins as $admin) {
            $full_name = trim($admin['first_name'] . ' ' . $admin['last_name']);
            echo "<tr>
                <td>{$admin['id']}</td>
                <td><strong>{$admin['username']}</strong></td>
                <td>{$admin['email']}</td>
                <td>" . ($full_name ?: '<em>Chưa cập nhật</em>') . "</td>
                <td>" . date('d/m/Y H:i', strtotime($admin['created_at'])) . "</td>
            </tr>";
        }
        
        echo "</tbody></table>";
    } else {
        echo "<div class='alert alert-warning text-center'>
            <i class='fas fa-exclamation-triangle'></i> Chưa có tài khoản admin nào!
        </div>";
    }

    echo "
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <script src='https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js'></script>
    </body>
    </html>";

} catch (PDOException $e) {
    echo "<!DOCTYPE html>
    <html>
    <head>
        <meta charset='UTF-8'>
        <title>Lỗi Database</title>
        <link href='https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css' rel='stylesheet'>
    </head>
    <body class='bg-light'>
        <div class='container mt-5'>
            <div class='alert alert-danger'>
                <h4>❌ Lỗi kết nối database!</h4>
                <p><strong>Chi tiết lỗi:</strong> " . $e->getMessage() . "</p>
                <hr>
                <p><strong>Hướng dẫn khắc phục:</strong></p>
                <ol>
                    <li>Đảm bảo XAMPP đã được khởi động</li>
                    <li>Kiểm tra MySQL service đang chạy</li>
                    <li>Xác nhận database name: <code>shoe_store</code> hoặc <code>nike_shoe_store</code></li>
                    <li>Import file <code>database.sql</code> nếu chưa có database</li>
                </ol>
            </div>
        </div>
    </body>
    </html>";
}
?>
