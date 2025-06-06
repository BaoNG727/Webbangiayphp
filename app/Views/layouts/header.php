<?php
// Start session only if not already active
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/../../Core/Database.php';

// Check if user is logged in
$logged_in = isset($_SESSION['user_id']);
$is_admin = $logged_in && $_SESSION['role'] == 'admin';

// Get cart count
$cart_count = 0;
if ($logged_in) {
    $db = new Database();
    $user_id = $_SESSION['user_id'];
    $result = $db->fetch("SELECT SUM(quantity) as total FROM cart WHERE user_id = ?", [$user_id]);
    $cart_count = $result['total'] ?? 0;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $title ?? 'Nike Shoe Store'; ?></title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/Webgiay/assets/css/style.css">
</head>
<body>    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm sticky-top">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" href="/Webgiay/">
                <img src="/Webgiay/assets/images/nike-logo.png" alt="Nike Logo" class="navbar-logo">
                <span class="brand-text ms-2 d-none d-sm-inline">Nike Store</span>
            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="/Webgiay/">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/Webgiay/products">Products</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                            Categories
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="/Webgiay/products?category=Running">Running</a></li>
                            <li><a class="dropdown-item" href="/Webgiay/products?category=Basketball">Basketball</a></li>
                            <li><a class="dropdown-item" href="/Webgiay/products?category=Casual">Casual</a></li>
                            <li><a class="dropdown-item" href="/Webgiay/products?category=Training">Training</a></li>
                        </ul>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/Webgiay/products?sale=1">Sale</a>
                    </li>
                </ul>
                  <!-- Search -->
                <form class="d-flex me-3" method="GET" action="/Webgiay/products">
                    <div class="input-group">
                        <input class="form-control" type="search" name="search" placeholder="Search shoes..." 
                               value="<?php echo htmlspecialchars($_GET['search'] ?? ''); ?>" aria-label="Search">
                        <button class="btn btn-outline-dark" type="submit" aria-label="Search">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </form>
                
                <!-- User Menu -->
                <ul class="navbar-nav">
                    <?php if ($logged_in): ?>
                        <li class="nav-item">
                            <a class="nav-link position-relative" href="/Webgiay/cart">
                                <i class="fas fa-shopping-cart"></i>
                                <?php if ($cart_count > 0): ?>
                                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                        <?php echo $cart_count; ?>
                                    </span>
                                <?php endif; ?>
                            </a>
                        </li>
                        
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                                <i class="fas fa-user"></i> <?php echo $_SESSION['username']; ?>
                            </a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="/Webgiay/profile">Profile</a></li>
                                <li><a class="dropdown-item" href="/Webgiay/orders">My Orders</a></li>
                                <?php if ($is_admin): ?>
                                    <li><hr class="dropdown-divider"></li>
                                    <li><a class="dropdown-item" href="/Webgiay/admin">Admin Panel</a></li>
                                <?php endif; ?>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="/Webgiay/logout">Logout</a></li>
                            </ul>
                        </li>
                    <?php else: ?>
                        <li class="nav-item">
                            <a class="nav-link" href="/Webgiay/login">Login</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/Webgiay/register">Register</a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>
