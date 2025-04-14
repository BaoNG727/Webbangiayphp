<?php
session_start();
require_once "config.php";

// Check if user is logged in
$logged_in = isset($_SESSION['user_id']);
$is_admin = $logged_in && $_SESSION['role'] == 'admin';

// Get cart count
$cart_count = 0;
if ($logged_in) {
    $user_id = $_SESSION['user_id'];
    $query = "SELECT SUM(quantity) as total FROM cart WHERE user_id = $user_id";
    $result = mysqli_query($conn, $query);
    if ($result && $row = mysqli_fetch_assoc($result)) {
        $cart_count = $row['total'] ? $row['total'] : 0;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nike Shoe Store</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/Webgiay/assets/css/style.css">
</head>
<body>
    <header>
        <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <div class="container">
                <a class="navbar-brand" href="/Webgiay/index.php">
                    <img src="/Webgiay/assets/images/nike-logo.png" alt="Nike Logo" height="30">
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav me-auto">
                        <li class="nav-item">
                            <a class="nav-link" href="/Webgiay/index.php">Home</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/Webgiay/products.php">Products</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/Webgiay/about.php">About Us</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/Webgiay/contact.php">Contact</a>
                        </li>
                    </ul>
                    <div class="d-flex align-items-center">
                        <a href="/Webgiay/cart.php" class="me-3 position-relative">
                            <i class="fas fa-shopping-cart"></i>
                            <?php if ($cart_count > 0): ?>
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                <?php echo $cart_count; ?>
                            </span>
                            <?php endif; ?>
                        </a>
                        <?php if ($logged_in): ?>
                            <div class="dropdown">
                                <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                                    <i class="fas fa-user"></i> <?php echo $_SESSION['username']; ?>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <?php if ($is_admin): ?>
                                    <li><a class="dropdown-item" href="/Webgiay/admin/index.php">Admin Panel</a></li>
                                    <?php endif; ?>
                                    <li><a class="dropdown-item" href="/Webgiay/account.php">My Account</a></li>
                                    <li><a class="dropdown-item" href="/Webgiay/orders.php">My Orders</a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li><a class="dropdown-item" href="/Webgiay/logout.php">Logout</a></li>
                                </ul>
                            </div>
                        <?php else: ?>
                            <a href="/Webgiay/login.php" class="btn btn-sm btn-outline-dark me-2">Login</a>
                            <a href="/Webgiay/register.php" class="btn btn-sm btn-dark">Register</a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </nav>
    </header>
    <main class="container py-4">
