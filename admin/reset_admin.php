<?php
// Yêu cầu file config để kết nối database
require_once '../includes/config.php';

// Đặt mật khẩu mới cho admin
$username = 'admin';
$new_password = 'admin123';
$hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

// Cập nhật mật khẩu trong database
$query = "UPDATE users SET password = ? WHERE username = ? OR email = 'admin@example.com'";
$stmt = $conn->prepare($query);
$stmt->bind_param("ss", $hashed_password, $username);

if ($stmt->execute()) {
    echo "<div style='margin: 50px; padding: 20px; background-color: #d4edda; border-color: #c3e6cb; border-radius: 5px;'>";
    echo "<h2>Admin Password Reset Successful!</h2>";
    echo "<p>Tài khoản admin đã được đặt lại mật khẩu.</p>";
    echo "<p><strong>Username:</strong> admin</p>";
    echo "<p><strong>Email:</strong> admin@example.com</p>";
    echo "<p><strong>Password:</strong> admin123</p>";
    echo "<p>Bạn có thể <a href='/Webgiay/login.php'>đăng nhập</a> bây giờ.</p>";
    echo "</div>";
} else {
    echo "<div style='margin: 50px; padding: 20px; background-color: #f8d7da; border-color: #f5c6cb; border-radius: 5px;'>";
    echo "<h2>Đã xảy ra lỗi!</h2>";
    echo "<p>Không thể đặt lại mật khẩu admin: " . $stmt->error . "</p>";
    echo "</div>";
}

// Đóng connection
$stmt->close();
$conn->close();
?>