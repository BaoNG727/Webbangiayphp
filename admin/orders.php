<?php
session_start();
require_once "../includes/config.php";

// Check if user is logged in and is admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

// Handle order status update
if (isset($_POST['update_status']) && isset($_POST['order_id']) && isset($_POST['status'])) {
    $order_id = $_POST['order_id'];
    $status = $_POST['status'];
    
    $update_query = "UPDATE orders SET status = ? WHERE id = ?";
    $stmt = $pdo->prepare($update_query);
    if ($stmt->execute([$status, $order_id])) {
        $success_message = "Order status updated successfully!";
    } else {
        $error_message = "Error updating order status.";
    }
}

// Get all orders with pagination
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$per_page = 10;
$offset = ($page - 1) * $per_page;

// Filter by status
$status_filter = isset($_GET['status']) ? $_GET['status'] : '';
$status_sql = '';
$params = [];

if ($status_filter) {
    $status_sql = " WHERE o.status = ?";
    $params = [$status_filter];
}

// Get total count
$count_query = "SELECT COUNT(*) as total FROM orders o" . $status_sql;
$stmt = $pdo->prepare($count_query);
$stmt->execute($params);
$total_orders = $stmt->fetch()['total'];
$total_pages = ceil($total_orders / $per_page);

// Get orders
$orders_query = "SELECT o.*, u.name, u.email FROM orders o 
                 JOIN users u ON o.user_id = u.id" . 
                 $status_sql . " ORDER BY o.created_at DESC LIMIT ? OFFSET ?";
$params[] = $per_page;
$params[] = $offset;
$stmt = $pdo->prepare($orders_query);
$stmt->execute($params);
$orders = $stmt->fetchAll();

// Get order status counts
$status_counts = [];
$status_query = "SELECT status, COUNT(*) as count FROM orders GROUP BY status";
$stmt = $pdo->prepare($status_query);
$stmt->execute();
$results = $stmt->fetchAll();
foreach ($results as $result) {
    $status_counts[$result['status']] = $result['count'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Orders Management - Nike Shoe Store Admin</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/admin.css">
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <nav id="sidebar" class="col-md-3 col-lg-2 d-md-block bg-dark sidebar collapse">
                <div class="position-sticky pt-3">
                    <a href="index.php" class="d-flex align-items-center mb-3 mb-md-0 me-md-auto text-white text-decoration-none">
                        <img src="../assets/images/nike-logo-white.png" alt="Nike Logo" height="30" class="me-2">
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
                            <a class="nav-link active text-white" href="orders.php">
                                <i class="fas fa-shopping-cart me-2"></i> Orders
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white" href="users.php">
                                <i class="fas fa-users me-2"></i> Users
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white" href="../index.php" target="_blank">
                                <i class="fas fa-external-link-alt me-2"></i> View Website
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white" href="../logout.php">
                                <i class="fas fa-sign-out-alt me-2"></i> Logout
                            </a>
                        </li>
                    </ul>
                </div>
            </nav>
            
            <!-- Main Content -->
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2">Orders Management</h1>
                    <div>
                        <span class="text-muted">Total Orders: <?php echo $total_orders; ?></span>
                    </div>
                </div>

                <?php if (isset($success_message)): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <?php echo $success_message; ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <?php if (isset($error_message)): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <?php echo $error_message; ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <!-- Order Status Summary -->
                <div class="row mb-4">
                    <div class="col-md-2">
                        <div class="card text-center">
                            <div class="card-body">
                                <h5 class="card-title">Pending</h5>
                                <h3 class="text-warning"><?php echo $status_counts['pending'] ?? 0; ?></h3>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="card text-center">
                            <div class="card-body">
                                <h5 class="card-title">Processing</h5>
                                <h3 class="text-info"><?php echo $status_counts['processing'] ?? 0; ?></h3>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="card text-center">
                            <div class="card-body">
                                <h5 class="card-title">Shipped</h5>
                                <h3 class="text-primary"><?php echo $status_counts['shipped'] ?? 0; ?></h3>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="card text-center">
                            <div class="card-body">
                                <h5 class="card-title">Delivered</h5>
                                <h3 class="text-success"><?php echo $status_counts['delivered'] ?? 0; ?></h3>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="card text-center">
                            <div class="card-body">
                                <h5 class="card-title">Cancelled</h5>
                                <h3 class="text-danger"><?php echo $status_counts['cancelled'] ?? 0; ?></h3>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Filter -->
                <div class="row mb-3">
                    <div class="col-md-6">
                        <form method="GET" class="d-flex">
                            <select name="status" class="form-select me-2">
                                <option value="">All Orders</option>
                                <option value="pending" <?php echo $status_filter == 'pending' ? 'selected' : ''; ?>>Pending</option>
                                <option value="processing" <?php echo $status_filter == 'processing' ? 'selected' : ''; ?>>Processing</option>
                                <option value="shipped" <?php echo $status_filter == 'shipped' ? 'selected' : ''; ?>>Shipped</option>
                                <option value="delivered" <?php echo $status_filter == 'delivered' ? 'selected' : ''; ?>>Delivered</option>
                                <option value="cancelled" <?php echo $status_filter == 'cancelled' ? 'selected' : ''; ?>>Cancelled</option>
                            </select>
                            <button type="submit" class="btn btn-outline-secondary">Filter</button>
                        </form>
                    </div>
                </div>

                <!-- Orders Table -->
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Order ID</th>
                                        <th>Customer</th>
                                        <th>Total Amount</th>
                                        <th>Status</th>
                                        <th>Order Date</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (count($orders) > 0): ?>
                                        <?php foreach ($orders as $order): ?>
                                            <tr>
                                                <td><strong>#<?php echo $order['id']; ?></strong></td>
                                                <td>
                                                    <strong><?php echo htmlspecialchars($order['name']); ?></strong>
                                                    <br><small class="text-muted"><?php echo htmlspecialchars($order['email']); ?></small>
                                                </td>
                                                <td>$<?php echo number_format($order['total_amount'], 2); ?></td>
                                                <td>
                                                    <form method="POST" class="d-inline">
                                                        <input type="hidden" name="order_id" value="<?php echo $order['id']; ?>">
                                                        <select name="status" class="form-select form-select-sm" onchange="this.form.submit()">
                                                            <option value="pending" <?php echo $order['status'] == 'pending' ? 'selected' : ''; ?>>Pending</option>
                                                            <option value="processing" <?php echo $order['status'] == 'processing' ? 'selected' : ''; ?>>Processing</option>
                                                            <option value="shipped" <?php echo $order['status'] == 'shipped' ? 'selected' : ''; ?>>Shipped</option>
                                                            <option value="delivered" <?php echo $order['status'] == 'delivered' ? 'selected' : ''; ?>>Delivered</option>
                                                            <option value="cancelled" <?php echo $order['status'] == 'cancelled' ? 'selected' : ''; ?>>Cancelled</option>
                                                        </select>
                                                        <input type="hidden" name="update_status" value="1">
                                                    </form>
                                                </td>
                                                <td><?php echo date('M d, Y H:i', strtotime($order['created_at'])); ?></td>
                                                <td>
                                                    <div class="btn-group" role="group">
                                                        <a href="order-details.php?id=<?php echo $order['id']; ?>" 
                                                           class="btn btn-sm btn-outline-info">
                                                            <i class="fas fa-eye"></i>
                                                        </a>
                                                        <a href="invoice.php?id=<?php echo $order['id']; ?>" 
                                                           class="btn btn-sm btn-outline-secondary" target="_blank">
                                                            <i class="fas fa-file-pdf"></i>
                                                        </a>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="6" class="text-center">
                                                <div class="py-4">
                                                    <i class="fas fa-shopping-cart fa-3x text-muted mb-3"></i>
                                                    <h5>No orders found</h5>
                                                    <p class="text-muted">Orders will appear here when customers make purchases</p>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Pagination -->
                <?php if ($total_pages > 1): ?>
                    <nav aria-label="Orders pagination" class="mt-4">
                        <ul class="pagination justify-content-center">
                            <?php if ($page > 1): ?>
                                <li class="page-item">
                                    <a class="page-link" href="?page=<?php echo $page - 1; ?><?php echo $status_filter ? '&status=' . urlencode($status_filter) : ''; ?>">Previous</a>
                                </li>
                            <?php endif; ?>

                            <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                                <li class="page-item <?php echo $i == $page ? 'active' : ''; ?>">
                                    <a class="page-link" href="?page=<?php echo $i; ?><?php echo $status_filter ? '&status=' . urlencode($status_filter) : ''; ?>"><?php echo $i; ?></a>
                                </li>
                            <?php endfor; ?>

                            <?php if ($page < $total_pages): ?>
                                <li class="page-item">
                                    <a class="page-link" href="?page=<?php echo $page + 1; ?><?php echo $status_filter ? '&status=' . urlencode($status_filter) : ''; ?>">Next</a>
                                </li>
                            <?php endif; ?>
                        </ul>
                    </nav>
                <?php endif; ?>
            </main>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
