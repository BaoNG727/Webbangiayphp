<?php
session_start();
require_once "../includes/config.php";

// Check if user is logged in and is admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

// Get order ID from URL
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: orders.php");
    exit;
}

$order_id = $_GET['id'];

// Handle order status update
if (isset($_POST['update_status']) && isset($_POST['status'])) {
    $status = $_POST['status'];
    $update_query = "UPDATE orders SET status = ?, updated_at = CURRENT_TIMESTAMP WHERE id = ?";
    $stmt = $pdo->prepare($update_query);
    if ($stmt->execute([$status, $order_id])) {
        $success_message = "Order status updated successfully!";
    } else {
        $error_message = "Error updating order status.";
    }
}

// Get order details with customer info
$order_query = "SELECT o.*, u.name as customer_name, u.email as customer_email, u.id as customer_id
                FROM orders o 
                JOIN users u ON o.user_id = u.id 
                WHERE o.id = ?";
$stmt = $pdo->prepare($order_query);
$stmt->execute([$order_id]);
$order = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$order) {
    header("Location: orders.php");
    exit;
}

// Get order items with product details
$items_query = "SELECT oi.*, 
                       COALESCE(p.name, s.name) as product_name,
                       COALESCE(p.image, s.image) as product_image,
                       COALESCE(p.brand, 'Nike') as product_brand,
                       s.size as shoe_size
                FROM order_items oi 
                LEFT JOIN products p ON oi.product_id = p.id
                LEFT JOIN shoes s ON oi.shoe_id = s.id
                WHERE oi.order_id = ?
                ORDER BY oi.id";
$stmt = $pdo->prepare($items_query);
$stmt->execute([$order_id]);
$order_items = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Calculate order summary
$subtotal = 0;
$total_items = 0;
foreach ($order_items as $item) {
    $subtotal += $item['subtotal'];
    $total_items += $item['quantity'];
}

// Get discount information from order
$discount_amount = $order['discount_amount'] ?? 0;
$discount_code = $order['discount_code'] ?? null;
$original_subtotal = $order['subtotal_amount'] ?? $subtotal;

// If we have discount but no subtotal_amount, calculate it
if ($discount_amount > 0 && !$order['subtotal_amount']) {
    $original_subtotal = $subtotal + $discount_amount;
}

// Shipping cost (example)
$shipping_cost = $original_subtotal > 1000000 ? 0 : 50000; // Free shipping over 1M VND
$tax_rate = 0.1; // 10% VAT
$tax_amount = ($original_subtotal - $discount_amount) * $tax_rate;
$final_total = $original_subtotal - $discount_amount + $shipping_cost + $tax_amount;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Details #<?php echo $order['id']; ?> - Nike Shoe Store Admin</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/admin.css">
    <style>
        .order-timeline {
            position: relative;
            padding-left: 30px;
        }
        .order-timeline::before {
            content: '';
            position: absolute;
            left: 15px;
            top: 0;
            bottom: 0;
            width: 2px;
            background: #dee2e6;
        }
        .timeline-item {
            position: relative;
            margin-bottom: 20px;
        }
        .timeline-item::before {
            content: '';
            position: absolute;
            left: -26px;
            top: 8px;
            width: 12px;
            height: 12px;
            border-radius: 50%;
            background: #6c757d;
        }
        .timeline-item.active::before {
            background: #ff6900;
            box-shadow: 0 0 0 4px rgba(255, 105, 0, 0.2);
        }
        .order-status-badge {
            font-size: 0.9em;
            padding: 8px 16px;
            border-radius: 20px;
        }
        .product-image {
            width: 60px;
            height: 60px;
            object-fit: cover;
            border-radius: 8px;
        }
        .order-summary-card {
            background: linear-gradient(135deg, #f8f9fa, #e9ecef);
            border: none;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }
        @media print {
            .no-print { display: none !important; }
            body { font-size: 12px; }
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <nav id="sidebar" class="col-md-3 col-lg-2 d-md-block bg-dark sidebar collapse no-print">
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
                        </li>                        <li class="nav-item">
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
                            <a class="nav-link text-white" href="discount-codes.php">
                                <i class="fas fa-tags me-2"></i> Discount Codes
                            </a>
                        </li><li class="nav-item">
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
                        <i class="fas fa-file-invoice me-2"></i>Order Details #<?php echo $order['id']; ?>
                    </h1>
                    <div class="btn-toolbar mb-2 mb-md-0 no-print">
                        <div class="btn-group me-2" role="group">
                            <a href="orders.php" class="btn btn-outline-secondary">
                                <i class="fas fa-arrow-left me-2"></i>Back to Orders
                            </a>
                            <button onclick="window.print()" class="btn btn-outline-info">
                                <i class="fas fa-print me-2"></i>Print Invoice
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Success/Error Messages -->
                <?php if (isset($success_message)): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle me-2"></i><?php echo $success_message; ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                <?php endif; ?>

                <?php if (isset($error_message)): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-circle me-2"></i><?php echo $error_message; ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                <?php endif; ?>

                <div class="row">
                    <!-- Order Information -->
                    <div class="col-lg-8">
                        <!-- Order Header -->
                        <div class="card mb-4">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h5 class="mb-0">Order Information</h5>
                                <?php
                                $status_colors = [
                                    'pending' => 'warning',
                                    'processing' => 'info', 
                                    'shipped' => 'primary',
                                    'delivered' => 'success',
                                    'cancelled' => 'danger'
                                ];
                                $status_icons = [
                                    'pending' => 'clock',
                                    'processing' => 'cog fa-spin',
                                    'shipped' => 'shipping-fast',
                                    'delivered' => 'check-circle',
                                    'cancelled' => 'times-circle'
                                ];
                                $color = $status_colors[$order['status']] ?? 'secondary';
                                $icon = $status_icons[$order['status']] ?? 'question';
                                ?>
                                <span class="order-status-badge badge bg-<?php echo $color; ?>">
                                    <i class="fas fa-<?php echo $icon; ?> me-2"></i>
                                    <?php echo ucfirst($order['status']); ?>
                                </span>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <h6>Order Details</h6>
                                        <p><strong>Order ID:</strong> #<?php echo $order['id']; ?></p>
                                        <p><strong>Order Date:</strong> <?php echo date('F d, Y H:i A', strtotime($order['created_at'])); ?></p>
                                        <p><strong>Last Updated:</strong> <?php echo $order['updated_at'] ? date('F d, Y H:i A', strtotime($order['updated_at'])) : 'Never'; ?></p>
                                        <p><strong>Total Items:</strong> <?php echo $total_items; ?> items</p>
                                    </div>
                                    <div class="col-md-6">
                                        <h6>Customer Information</h6>
                                        <p><strong>Name:</strong> <?php echo htmlspecialchars($order['customer_name']); ?></p>
                                        <p><strong>Email:</strong> <?php echo htmlspecialchars($order['customer_email']); ?></p>
                                        <p><strong>Phone:</strong> <?php echo htmlspecialchars($order['phone']); ?></p>
                                        <p>
                                            <strong>Customer:</strong> 
                                            <a href="user-details.php?id=<?php echo $order['customer_id']; ?>" class="btn btn-sm btn-outline-info no-print">
                                                <i class="fas fa-user me-1"></i>View Profile
                                            </a>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Shipping Information -->
                        <div class="card mb-4">
                            <div class="card-header">
                                <h5 class="mb-0"><i class="fas fa-shipping-fast me-2"></i>Shipping Information</h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <h6>Shipping Address</h6>
                                        <p class="mb-1"><strong><?php echo htmlspecialchars($order['name']); ?></strong></p>
                                        <p class="mb-1"><?php echo htmlspecialchars($order['address']); ?></p>
                                        <p class="mb-1"><?php echo htmlspecialchars($order['city']); ?></p>
                                        <p class="mb-0">Phone: <?php echo htmlspecialchars($order['phone']); ?></p>
                                    </div>
                                    <div class="col-md-6">
                                        <h6>Delivery Notes</h6>
                                        <?php if ($order['notes']): ?>
                                            <p class="text-muted"><?php echo nl2br(htmlspecialchars($order['notes'])); ?></p>
                                        <?php else: ?>
                                            <p class="text-muted">No special delivery instructions</p>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Order Items -->
                        <div class="card mb-4">
                            <div class="card-header">
                                <h5 class="mb-0"><i class="fas fa-list me-2"></i>Order Items</h5>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>Product</th>
                                                <th>Details</th>
                                                <th>Price</th>
                                                <th>Quantity</th>
                                                <th class="text-end">Subtotal</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($order_items as $item): ?>
                                            <tr>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <img src="../uploads/<?php echo $item['product_image']; ?>" 
                                                             alt="<?php echo htmlspecialchars($item['product_name']); ?>"
                                                             class="product-image me-3"
                                                             onerror="this.src='../assets/images/nike-logo.png'">
                                                        <div>
                                                            <h6 class="mb-1"><?php echo htmlspecialchars($item['product_name']); ?></h6>
                                                            <small class="text-muted"><?php echo htmlspecialchars($item['product_brand'] ?? 'Nike'); ?></small>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <?php if ($item['shoe_size']): ?>
                                                        <span class="badge bg-light text-dark">Size: <?php echo $item['shoe_size']; ?></span>
                                                    <?php endif; ?>
                                                </td>
                                                <td><?php echo number_format($item['price'], 0, ',', '.'); ?>đ</td>
                                                <td>
                                                    <span class="badge bg-secondary"><?php echo $item['quantity']; ?></span>
                                                </td>
                                                <td class="text-end">
                                                    <strong><?php echo number_format($item['subtotal'], 0, ',', '.'); ?>đ</strong>
                                                </td>
                                            </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Order Summary & Status -->
                    <div class="col-lg-4">
                        <!-- Update Order Status -->
                        <div class="card mb-4 no-print">
                            <div class="card-header">
                                <h5 class="mb-0"><i class="fas fa-edit me-2"></i>Update Order Status</h5>
                            </div>
                            <div class="card-body">
                                <form method="POST">
                                    <div class="mb-3">
                                        <label class="form-label">Current Status: <strong><?php echo ucfirst($order['status']); ?></strong></label>
                                        <select name="status" class="form-select" required>
                                            <option value="pending" <?php echo $order['status'] == 'pending' ? 'selected' : ''; ?>>
                                                <i class="fas fa-clock"></i> Pending
                                            </option>
                                            <option value="processing" <?php echo $order['status'] == 'processing' ? 'selected' : ''; ?>>
                                                <i class="fas fa-cog"></i> Processing
                                            </option>
                                            <option value="shipped" <?php echo $order['status'] == 'shipped' ? 'selected' : ''; ?>>
                                                <i class="fas fa-shipping-fast"></i> Shipped
                                            </option>
                                            <option value="delivered" <?php echo $order['status'] == 'delivered' ? 'selected' : ''; ?>>
                                                <i class="fas fa-check-circle"></i> Delivered
                                            </option>
                                            <option value="cancelled" <?php echo $order['status'] == 'cancelled' ? 'selected' : ''; ?>>
                                                <i class="fas fa-times-circle"></i> Cancelled
                                            </option>
                                        </select>
                                    </div>
                                    <button type="submit" name="update_status" class="btn btn-primary w-100">
                                        <i class="fas fa-save me-2"></i>Update Status
                                    </button>
                                </form>
                            </div>
                        </div>

                        <!-- Order Summary -->
                        <div class="card order-summary-card mb-4">
                            <div class="card-header">
                                <h5 class="mb-0"><i class="fas fa-calculator me-2"></i>Order Summary</h5>
                            </div>                            <div class="card-body">
                                <div class="d-flex justify-content-between mb-2">
                                    <span>Subtotal (<?php echo $total_items; ?> items):</span>
                                    <span><?php echo number_format($original_subtotal, 0, ',', '.'); ?>đ</span>
                                </div>
                                <?php if ($discount_amount > 0): ?>
                                <div class="d-flex justify-content-between mb-2 text-success">
                                    <span>
                                        <i class="fas fa-tag me-1"></i>
                                        Discount <?php echo $discount_code ? "({$discount_code})" : ''; ?>:
                                    </span>
                                    <span>-<?php echo number_format($discount_amount, 0, ',', '.'); ?>đ</span>
                                </div>
                                <div class="d-flex justify-content-between mb-2">
                                    <span>Subtotal after discount:</span>
                                    <span><?php echo number_format($original_subtotal - $discount_amount, 0, ',', '.'); ?>đ</span>
                                </div>
                                <?php endif; ?>
                                <div class="d-flex justify-content-between mb-2">
                                    <span>Shipping:</span>
                                    <span><?php echo $shipping_cost > 0 ? number_format($shipping_cost, 0, ',', '.') . 'đ' : 'Free'; ?></span>
                                </div>
                                <div class="d-flex justify-content-between mb-2">
                                    <span>Tax (VAT 10%):</span>
                                    <span><?php echo number_format($tax_amount, 0, ',', '.'); ?>đ</span>
                                </div>
                                <hr>
                                <div class="d-flex justify-content-between">
                                    <strong>Total:</strong>
                                    <strong class="text-primary"><?php echo number_format($order['total_amount'], 0, ',', '.'); ?>đ</strong>
                                </div>
                                <?php if ($discount_amount > 0): ?>
                                <div class="text-center mt-2">
                                    <small class="text-success">
                                        <i class="fas fa-check-circle me-1"></i>
                                        You saved <?php echo number_format($discount_amount, 0, ',', '.'); ?>đ with this order!
                                    </small>
                                </div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <!-- Order Timeline -->
                        <div class="card">
                            <div class="card-header">
                                <h5 class="mb-0"><i class="fas fa-history me-2"></i>Order Timeline</h5>
                            </div>
                            <div class="card-body">
                                <div class="order-timeline">
                                    <div class="timeline-item <?php echo in_array($order['status'], ['pending', 'processing', 'shipped', 'delivered']) ? 'active' : ''; ?>">
                                        <strong>Order Placed</strong>
                                        <br><small class="text-muted"><?php echo date('M d, Y H:i A', strtotime($order['created_at'])); ?></small>
                                    </div>
                                    
                                    <?php if (in_array($order['status'], ['processing', 'shipped', 'delivered'])): ?>
                                    <div class="timeline-item active">
                                        <strong>Processing</strong>
                                        <br><small class="text-muted">Order is being prepared</small>
                                    </div>
                                    <?php endif; ?>
                                    
                                    <?php if (in_array($order['status'], ['shipped', 'delivered'])): ?>
                                    <div class="timeline-item active">
                                        <strong>Shipped</strong>
                                        <br><small class="text-muted">Order has been shipped</small>
                                    </div>
                                    <?php endif; ?>
                                    
                                    <?php if ($order['status'] == 'delivered'): ?>
                                    <div class="timeline-item active">
                                        <strong>Delivered</strong>
                                        <br><small class="text-muted">Order delivered successfully</small>
                                    </div>
                                    <?php endif; ?>
                                    
                                    <?php if ($order['status'] == 'cancelled'): ?>
                                    <div class="timeline-item" style="color: #dc3545;">
                                        <strong>Cancelled</strong>
                                        <br><small class="text-muted">Order was cancelled</small>
                                    </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Auto-hide alerts after 5 seconds
        setTimeout(function() {
            var alerts = document.querySelectorAll('.alert');
            alerts.forEach(function(alert) {
                var bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            });
        }, 5000);
    </script>
</body>
</html>
