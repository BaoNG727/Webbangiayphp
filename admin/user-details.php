<?php
session_start();
require_once "../includes/config.php";

// Check if user is logged in and is admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

// Get user ID from URL
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: users.php");
    exit;
}

$user_id = $_GET['id'];

// Get user details
$user_query = "SELECT * FROM users WHERE id = ?";
$stmt = $pdo->prepare($user_query);
$stmt->execute([$user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    header("Location: users.php");
    exit;
}

// Get user's orders
$orders_query = "SELECT o.*, 
                        COUNT(oi.id) as total_items,
                        SUM(oi.quantity) as total_quantity
                 FROM orders o 
                 LEFT JOIN order_items oi ON o.id = oi.order_id 
                 WHERE o.user_id = ? 
                 GROUP BY o.id 
                 ORDER BY o.created_at DESC";
$stmt = $pdo->prepare($orders_query);
$stmt->execute([$user_id]);
$orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Get user's cart
$cart_query = "SELECT c.*, p.name as product_name, p.price, p.image 
               FROM cart c 
               JOIN products p ON c.product_id = p.id 
               WHERE c.user_id = ?";
$stmt = $pdo->prepare($cart_query);
$stmt->execute([$user_id]);
$cart_items = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Get user's reviews
$reviews_query = "SELECT r.*, 
                         COALESCE(p.name, s.name) as product_name
                  FROM reviews r 
                  LEFT JOIN products p ON r.shoe_id = p.id
                  LEFT JOIN shoes s ON r.shoe_id = s.id 
                  WHERE r.user_id = ? 
                  ORDER BY r.created_at DESC";
$stmt = $pdo->prepare($reviews_query);
$stmt->execute([$user_id]);
$reviews = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Calculate statistics
$total_orders = count($orders);
$total_spent = array_sum(array_column($orders, 'total_amount'));
$recent_orders = array_slice($orders, 0, 5);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Details - <?php echo htmlspecialchars($user['name']); ?> - Nike Shoe Store Admin</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/admin.css">
    <style>
        .stat-card {
            border-left: 4px solid #ff6900;
            transition: all 0.3s ease;
        }
        .stat-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }
        .user-avatar {
            width: 120px;
            height: 120px;
            background: linear-gradient(135deg, #ff6900, #fccc02);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 48px;
            color: white;
            font-weight: bold;
        }
        .order-status {
            font-size: 0.8em;
            padding: 4px 8px;
            border-radius: 12px;
        }
        .rating-stars {
            color: #ffc107;
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <nav id="sidebar" class="col-md-3 col-lg-2 d-md-block bg-dark sidebar collapse">
                <div class="position-sticky pt-3">
                    <a href="index.php" class="d-flex align-items-center mb-3 mb-md-0 me-md-auto text-white text-decoration-none">
                        <img src="../assets/images/nike-logo.png" alt="Nike Logo" height="30" class="me-2">
                        <span class="fs-4">Admin</span>
                    </a>
                    <hr class="text-white">
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link text-white" href="index.php">
                                <i class="fas fa-tachometer-alt me-2"></i> Dashboard
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white" href="products.php">
                                <i class="fas fa-shoe-prints me-2"></i> Products
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white" href="orders.php">
                                <i class="fas fa-shopping-cart me-2"></i> Orders
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active text-white" href="users.php">
                                <i class="fas fa-users me-2"></i> Users
                            </a>
                        </li>                        <li class="nav-item">
                            <a class="nav-link text-white" href="../" target="_blank">
                                <i class="fas fa-external-link-alt me-2"></i> View Website
                            </a>
                        </li>                        <li class="nav-item">
                            <a class="nav-link text-white" href="../logout">
                                <i class="fas fa-sign-out-alt me-2"></i> Logout
                            </a>
                        </li>
                    </ul>
                </div>
            </nav>

            <!-- Main content -->
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2">
                        <i class="fas fa-user-circle me-2"></i>User Details
                    </h1>
                    <div class="btn-toolbar mb-2 mb-md-0">
                        <a href="users.php" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left me-2"></i>Back to Users
                        </a>
                    </div>
                </div>

                <!-- User Profile Card -->
                <div class="row mb-4">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-3 text-center">
                                        <div class="user-avatar mx-auto mb-3">
                                            <?php echo strtoupper(substr($user['name'], 0, 1)); ?>
                                        </div>
                                        <h4><?php echo htmlspecialchars($user['name']); ?></h4>
                                        <span class="badge bg-<?php echo $user['role'] == 'admin' ? 'warning' : 'success'; ?> mb-2">
                                            <i class="fas fa-<?php echo $user['role'] == 'admin' ? 'crown' : 'user'; ?> me-1"></i>
                                            <?php echo ucfirst($user['role']); ?>
                                        </span>
                                    </div>
                                    <div class="col-md-9">
                                        <h5 class="card-title mb-3">User Information</h5>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <p><strong>User ID:</strong> #<?php echo $user['id']; ?></p>
                                                <p><strong>Full Name:</strong> <?php echo htmlspecialchars($user['name']); ?></p>
                                                <p><strong>Email:</strong> <?php echo htmlspecialchars($user['email']); ?></p>
                                                <?php if ($user['username']): ?>
                                                <p><strong>Username:</strong> <?php echo htmlspecialchars($user['username']); ?></p>
                                                <?php endif; ?>
                                            </div>
                                            <div class="col-md-6">
                                                <p><strong>Role:</strong> <?php echo ucfirst($user['role']); ?></p>
                                                <p><strong>Joined:</strong> <?php echo date('F d, Y', strtotime($user['created_at'])); ?></p>
                                                <p><strong>Last Updated:</strong> <?php echo $user['updated_at'] ? date('F d, Y', strtotime($user['updated_at'])) : 'Never'; ?></p>
                                                <p><strong>Account Status:</strong> <span class="badge bg-success">Active</span></p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Statistics Cards -->
                <div class="row mb-4">
                    <div class="col-md-3">
                        <div class="card stat-card">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <h6 class="card-title text-muted">Total Orders</h6>
                                        <h3 class="mb-0"><?php echo $total_orders; ?></h3>
                                    </div>
                                    <div class="align-self-center">
                                        <i class="fas fa-shopping-cart fa-2x text-primary"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card stat-card">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <h6 class="card-title text-muted">Total Spent</h6>
                                        <h3 class="mb-0"><?php echo number_format($total_spent, 0, ',', '.'); ?>đ</h3>
                                    </div>
                                    <div class="align-self-center">
                                        <i class="fas fa-dollar-sign fa-2x text-success"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card stat-card">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <h6 class="card-title text-muted">Cart Items</h6>
                                        <h3 class="mb-0"><?php echo count($cart_items); ?></h3>
                                    </div>
                                    <div class="align-self-center">
                                        <i class="fas fa-shopping-basket fa-2x text-warning"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card stat-card">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <h6 class="card-title text-muted">Reviews</h6>
                                        <h3 class="mb-0"><?php echo count($reviews); ?></h3>
                                    </div>
                                    <div class="align-self-center">
                                        <i class="fas fa-star fa-2x text-info"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Recent Orders -->
                <div class="row mb-4">
                    <div class="col-md-8">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h5 class="mb-0">Recent Orders</h5>
                                <?php if ($total_orders > 5): ?>
                                <small class="text-muted">Showing latest 5 of <?php echo $total_orders; ?> orders</small>
                                <?php endif; ?>
                            </div>
                            <div class="card-body">
                                <?php if (count($recent_orders) > 0): ?>
                                <div class="table-responsive">
                                    <table class="table table-sm">
                                        <thead>
                                            <tr>
                                                <th>Order #</th>
                                                <th>Date</th>
                                                <th>Items</th>
                                                <th>Total</th>
                                                <th>Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($recent_orders as $order): ?>
                                            <tr>
                                                <td><strong>#<?php echo $order['id']; ?></strong></td>
                                                <td><?php echo date('M d, Y', strtotime($order['created_at'])); ?></td>
                                                <td><?php echo $order['total_quantity'] ?? 0; ?> items</td>
                                                <td><strong><?php echo number_format($order['total_amount'], 0, ',', '.'); ?>đ</strong></td>
                                                <td>
                                                    <?php
                                                    $status_colors = [
                                                        'pending' => 'warning',
                                                        'processing' => 'info',
                                                        'shipped' => 'primary',
                                                        'delivered' => 'success',
                                                        'cancelled' => 'danger'
                                                    ];
                                                    $color = $status_colors[$order['status']] ?? 'secondary';
                                                    ?>
                                                    <span class="order-status badge bg-<?php echo $color; ?>">
                                                        <?php echo ucfirst($order['status']); ?>
                                                    </span>
                                                </td>
                                            </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                                <?php else: ?>
                                <div class="text-center py-4">
                                    <i class="fas fa-shopping-cart fa-3x text-muted mb-3"></i>
                                    <h6>No orders yet</h6>
                                    <p class="text-muted">This user hasn't placed any orders.</p>
                                </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <!-- Current Cart -->
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="mb-0">Current Cart</h5>
                            </div>
                            <div class="card-body">
                                <?php if (count($cart_items) > 0): ?>
                                    <?php 
                                    $cart_total = 0;
                                    foreach ($cart_items as $item): 
                                        $cart_total += $item['price'] * $item['quantity'];
                                    ?>
                                    <div class="d-flex mb-3">
                                        <img src="../uploads/<?php echo $item['image']; ?>" 
                                             alt="<?php echo htmlspecialchars($item['product_name']); ?>"
                                             class="me-3" style="width: 50px; height: 50px; object-fit: cover; border-radius: 8px;">
                                        <div class="flex-grow-1">
                                            <h6 class="mb-1"><?php echo htmlspecialchars($item['product_name']); ?></h6>
                                            <small class="text-muted">
                                                Qty: <?php echo $item['quantity']; ?> × 
                                                <?php echo number_format($item['price'], 0, ',', '.'); ?>đ
                                            </small>
                                        </div>
                                    </div>
                                    <?php endforeach; ?>
                                    <hr>
                                    <div class="d-flex justify-content-between">
                                        <strong>Total:</strong>
                                        <strong><?php echo number_format($cart_total, 0, ',', '.'); ?>đ</strong>
                                    </div>
                                <?php else: ?>
                                <div class="text-center py-3">
                                    <i class="fas fa-shopping-basket fa-2x text-muted mb-2"></i>
                                    <p class="text-muted mb-0">Cart is empty</p>
                                </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Reviews Section -->
                <?php if (count($reviews) > 0): ?>
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="mb-0">Product Reviews</h5>
                            </div>
                            <div class="card-body">
                                <?php foreach ($reviews as $review): ?>
                                <div class="border-bottom pb-3 mb-3">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div>
                                            <h6 class="mb-1"><?php echo htmlspecialchars($review['product_name']); ?></h6>
                                            <div class="rating-stars mb-2">
                                                <?php for ($i = 1; $i <= 5; $i++): ?>
                                                    <i class="fas fa-star<?php echo $i <= $review['rating'] ? '' : '-empty'; ?>"></i>
                                                <?php endfor; ?>
                                                <span class="ms-2 text-muted">(<?php echo $review['rating']; ?>/5)</span>
                                            </div>
                                            <p class="mb-1"><?php echo htmlspecialchars($review['review']); ?></p>
                                        </div>
                                        <small class="text-muted"><?php echo date('M d, Y', strtotime($review['created_at'])); ?></small>
                                    </div>
                                </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endif; ?>
            </main>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
