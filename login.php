<?php
session_start();
require_once "includes/config.php";

// If already logged in, redirect to appropriate page
if (isset($_SESSION['user_id'])) {
    if ($_SESSION['role'] === 'admin') {
        header("Location: admin/index.php");
    } else {
        header("Location: index.php");
    }
    exit;
}

$error_message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    
    if ($email && $password) {
        try {
            $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
            $stmt->execute([$email]);
            $user = $stmt->fetch();
            
            if ($user && password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['name'] = $user['name'];
                $_SESSION['email'] = $user['email'];
                $_SESSION['role'] = $user['role'];
                
                // Redirect based on role
                if ($user['role'] === 'admin') {
                    header("Location: admin/index.php");
                } else {
                    header("Location: index.php");
                }
                exit;
            } else {
                $error_message = "Invalid email or password.";
            }
        } catch (Exception $e) {
            $error_message = "Login error: " . $e->getMessage();
        }
    } else {
        $error_message = "Please fill in all fields.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Nike Shoe Store</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
        }
        .login-card {
            box-shadow: 0 15px 35px rgba(0,0,0,0.1);
            border-radius: 15px;
            border: none;
            backdrop-filter: blur(10px);
            background: rgba(255,255,255,0.95);
        }
        .nike-logo {
            max-height: 60px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-4">
                <div class="card login-card">
                    <div class="card-body p-5">
                        <div class="text-center mb-4">
                            <img src="assets/images/nike-logo.png" alt="Nike Logo" class="nike-logo mb-3">
                            <h3 class="card-title">Nike Shoe Store</h3>
                            <p class="text-muted">Sign in to your account</p>
                        </div>

                        <?php if ($error_message): ?>
                            <div class="alert alert-danger" role="alert">
                                <i class="fas fa-exclamation-triangle me-2"></i>
                                <?php echo htmlspecialchars($error_message); ?>
                            </div>
                        <?php endif; ?>

                        <form method="POST">
                            <div class="mb-3">
                                <label for="email" class="form-label">Email Address</label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="fas fa-envelope"></i>
                                    </span>
                                    <input type="email" class="form-control" id="email" name="email" 
                                           value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>" required>
                                </div>
                            </div>

                            <div class="mb-4">
                                <label for="password" class="form-label">Password</label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="fas fa-lock"></i>
                                    </span>
                                    <input type="password" class="form-control" id="password" name="password" required>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-primary w-100 mb-3">
                                <i class="fas fa-sign-in-alt me-2"></i>
                                Sign In
                            </button>
                        </form>

                        <div class="text-center">
                            <p class="text-muted">Demo Accounts:</p>
                            <small class="text-muted">
                                Admin: admin@nike.com / password<br>
                                Customer: (Register as customer)
                            </small>
                        </div>

                        <div class="text-center mt-3">
                            <a href="index.php" class="text-decoration-none">
                                <i class="fas fa-arrow-left me-1"></i>
                                Back to Store
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
