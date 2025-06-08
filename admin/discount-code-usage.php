<?php
session_start();
require_once "../includes/config.php";

// Check if user is logged in and is admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

$discount_code = null;
$usage_history = [];

// Get discount code ID from URL
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: discount-codes.php");
    exit;
}

$code_id = $_GET['id'];

// Fetch discount code details
try {
    $stmt = $pdo->prepare("SELECT * FROM discount_codes WHERE id = ?");
    $stmt->execute([$code_id]);
    $discount_code = $stmt->fetch();
    
    if (!$discount_code) {
        $_SESSION['error'] = "Discount code not found.";
        header("Location: discount-codes.php");
        exit;
    }
} catch (Exception $e) {
    $_SESSION['error'] = "Error fetching discount code.";
    header("Location: discount-codes.php");
    exit;
}

// Get usage history with pagination
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$per_page = 20;
$offset = ($page - 1) * $per_page;

try {
    // Get total usage count
    $count_stmt = $pdo->prepare("SELECT COUNT(*) as total FROM discount_code_usage WHERE discount_code_id = ?");
    $count_stmt->execute([$code_id]);
    $total_usage = $count_stmt->fetch()['total'];
    $total_pages = ceil($total_usage / $per_page);

    // Get usage history
    $usage_stmt = $pdo->prepare("
        SELECT dcu.*, u.username, u.email, o.id as order_number, o.total_amount
        FROM discount_code_usage dcu
        JOIN users u ON dcu.user_id = u.id
        JOIN orders o ON dcu.order_id = o.id
        WHERE dcu.discount_code_id = ?
        ORDER BY dcu.used_at DESC
        LIMIT ? OFFSET ?
    ");
    $usage_stmt->execute([$code_id, $per_page, $offset]);
    $usage_history = $usage_stmt->fetchAll();

    // Get usage statistics
    $stats_stmt = $pdo->prepare("
        SELECT 
            COUNT(*) as total_uses,
            COUNT(DISTINCT user_id) as unique_users,
            SUM(discount_amount) as total_savings,
            AVG(discount_amount) as avg_savings,
            MIN(used_at) as first_used,
            MAX(used_at) as last_used
        FROM discount_code_usage 
        WHERE discount_code_id = ?
    ");
    $stats_stmt->execute([$code_id]);
    $stats = $stats_stmt->fetch();

    // Get top users
    $top_users_stmt = $pdo->prepare("
        SELECT u.username, u.email, COUNT(*) as usage_count, SUM(dcu.discount_amount) as total_savings
        FROM discount_code_usage dcu
        JOIN users u ON dcu.user_id = u.id
        WHERE dcu.discount_code_id = ?
        GROUP BY dcu.user_id, u.username, u.email
        ORDER BY usage_count DESC, total_savings DESC
        LIMIT 10
    ");
    $top_users_stmt->execute([$code_id]);
    $top_users = $top_users_stmt->fetchAll();

} catch (Exception $e) {
    $error_message = "Error fetching usage data: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Discount Code Usage - Nike Shoe Store Admin</title>
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
                            <a class="nav-link text-white" href="orders.php">
                                <i class="fas fa-shopping-cart me-2"></i> Orders
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white" href="users.php">
                                <i class="fas fa-users me-2"></i> Users
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active text-white" href="discount-codes.php">
                                <i class="fas fa-tags me-2"></i> Discount Codes
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white" href="../" target="_blank">
                                <i class="fas fa-external-link-alt me-2"></i> View Website
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white" href="../logout">
                                <i class="fas fa-sign-out-alt me-2"></i> Logout
                            </a>
                        </li>
                    </ul>
                </div>
            </nav>
            
            <!-- Main Content -->
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2"><i class="fas fa-chart-line me-2"></i>Usage Analytics: <?php echo htmlspecialchars($discount_code['code']); ?></h1>
                    <div>
                        <a href="discount-codes.php" class="btn btn-outline-secondary me-2">
                            <i class="fas fa-arrow-left"></i> Back to Discount Codes
                        </a>
                        <a href="edit-discount-code.php?id=<?php echo $discount_code['id']; ?>" class="btn btn-primary">
                            <i class="fas fa-edit"></i> Edit Code
                        </a>
                    </div>
                </div>

                <!-- Discount Code Info -->
                <div class="card mb-4">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-8">
                                <h5 class="card-title"><?php echo htmlspecialchars($discount_code['code']); ?></h5>
                                <p class="card-text"><?php echo htmlspecialchars($discount_code['description'] ?: 'No description'); ?></p>
                                <div class="row">
                                    <div class="col-sm-6">
                                        <strong>Type:</strong> <?php echo ucfirst($discount_code['type']); ?>
                                        <br><strong>Value:</strong> <?php echo $discount_code['value']; ?><?php echo $discount_code['type'] === 'percentage' ? '%' : ' VND'; ?>
                                        <?php if ($discount_code['minimum_order_amount'] > 0): ?>
                                            <br><strong>Min Order:</strong> <?php echo number_format($discount_code['minimum_order_amount']); ?> VND
                                        <?php endif; ?>
                                    </div>
                                    <div class="col-sm-6">
                                        <strong>Valid From:</strong> <?php echo date('M d, Y H:i', strtotime($discount_code['valid_from'])); ?>
                                        <br><strong>Valid Until:</strong> <?php echo date('M d, Y H:i', strtotime($discount_code['valid_until'])); ?>
                                        <br><strong>Status:</strong> 
                                        <?php
                                        $status = 'Active';
                                        $statusClass = 'success';
                                        
                                        if (!$discount_code['is_active']) {
                                            $status = 'Inactive';
                                            $statusClass = 'secondary';
                                        } elseif (strtotime($discount_code['valid_until']) < time()) {
                                            $status = 'Expired';
                                            $statusClass = 'danger';
                                        } elseif (strtotime($discount_code['valid_from']) > time()) {
                                            $status = 'Scheduled';
                                            $statusClass = 'warning';
                                        } elseif ($discount_code['usage_limit'] && $discount_code['usage_count'] >= $discount_code['usage_limit']) {
                                            $status = 'Used Up';
                                            $statusClass = 'dark';
                                        }
                                        ?>
                                        <span class="badge bg-<?php echo $statusClass; ?>"><?php echo $status; ?></span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 text-center">
                                <h3 class="text-primary"><?php echo $discount_code['usage_count']; ?></h3>
                                <p class="text-muted">Total Uses</p>
                                <?php if ($discount_code['usage_limit']): ?>
                                    <div class="progress">
                                        <div class="progress-bar" role="progressbar" 
                                             style="width: <?php echo min(100, ($discount_code['usage_count'] / $discount_code['usage_limit']) * 100); ?>%">
                                            <?php echo round(($discount_code['usage_count'] / $discount_code['usage_limit']) * 100, 1); ?>%
                                        </div>
                                    </div>
                                    <small class="text-muted"><?php echo $discount_code['usage_count']; ?> / <?php echo $discount_code['usage_limit']; ?> uses</small>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Statistics Cards -->
                <?php if ($stats && $stats['total_uses'] > 0): ?>
                <div class="row mb-4">
                    <div class="col-md-2">
                        <div class="card text-center border-primary">
                            <div class="card-body">
                                <h5 class="card-title text-primary">Total Uses</h5>
                                <h3 class="text-primary"><?php echo $stats['total_uses']; ?></h3>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="card text-center border-success">
                            <div class="card-body">
                                <h5 class="card-title text-success">Unique Users</h5>
                                <h3 class="text-success"><?php echo $stats['unique_users']; ?></h3>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="card text-center border-info">
                            <div class="card-body">
                                <h5 class="card-title text-info">Total Savings</h5>
                                <h3 class="text-info"><?php echo number_format($stats['total_savings']); ?> VND</h3>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="card text-center border-warning">
                            <div class="card-body">
                                <h5 class="card-title text-warning">Avg Savings</h5>
                                <h3 class="text-warning"><?php echo number_format($stats['avg_savings']); ?> VND</h3>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="card text-center border-secondary">
                            <div class="card-body">
                                <h5 class="card-title text-secondary">First Used</h5>
                                <small class="text-secondary"><?php echo $stats['first_used'] ? date('M d, Y', strtotime($stats['first_used'])) : 'Never'; ?></small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="card text-center border-dark">
                            <div class="card-body">
                                <h5 class="card-title text-dark">Last Used</h5>
                                <small class="text-dark"><?php echo $stats['last_used'] ? date('M d, Y', strtotime($stats['last_used'])) : 'Never'; ?></small>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endif; ?>

                <div class="row">
                    <!-- Usage History -->
                    <div class="col-lg-8">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="mb-0"><i class="fas fa-history me-2"></i>Usage History</h5>
                            </div>
                            <div class="card-body">
                                <?php if (count($usage_history) > 0): ?>
                                    <div class="table-responsive">
                                        <table class="table table-hover">
                                            <thead>
                                                <tr>
                                                    <th>User</th>
                                                    <th>Order</th>
                                                    <th>Discount Applied</th>
                                                    <th>Order Total</th>
                                                    <th>Used At</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($usage_history as $usage): ?>
                                                    <tr>
                                                        <td>
                                                            <strong><?php echo htmlspecialchars($usage['username']); ?></strong>
                                                            <br><small class="text-muted"><?php echo htmlspecialchars($usage['email']); ?></small>
                                                        </td>
                                                        <td>
                                                            <a href="order-details.php?id=<?php echo $usage['order_id']; ?>" class="text-decoration-none">
                                                                #<?php echo str_pad($usage['order_number'], 6, '0', STR_PAD_LEFT); ?>
                                                            </a>
                                                        </td>
                                                        <td>
                                                            <span class="badge bg-success"><?php echo number_format($usage['discount_amount']); ?> VND</span>
                                                        </td>
                                                        <td><?php echo number_format($usage['total_amount']); ?> VND</td>
                                                        <td>
                                                            <small><?php echo date('M d, Y H:i', strtotime($usage['used_at'])); ?></small>
                                                        </td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    </div>

                                    <!-- Pagination -->
                                    <?php if ($total_pages > 1): ?>
                                        <nav aria-label="Usage history pagination" class="mt-4">
                                            <ul class="pagination justify-content-center">
                                                <?php if ($page > 1): ?>
                                                    <li class="page-item">
                                                        <a class="page-link" href="?id=<?php echo $code_id; ?>&page=<?php echo $page - 1; ?>">Previous</a>
                                                    </li>
                                                <?php endif; ?>

                                                <?php for ($i = max(1, $page - 2); $i <= min($total_pages, $page + 2); $i++): ?>
                                                    <li class="page-item <?php echo $i == $page ? 'active' : ''; ?>">
                                                        <a class="page-link" href="?id=<?php echo $code_id; ?>&page=<?php echo $i; ?>"><?php echo $i; ?></a>
                                                    </li>
                                                <?php endfor; ?>

                                                <?php if ($page < $total_pages): ?>
                                                    <li class="page-item">
                                                        <a class="page-link" href="?id=<?php echo $code_id; ?>&page=<?php echo $page + 1; ?>">Next</a>
                                                    </li>
                                                <?php endif; ?>
                                            </ul>
                                        </nav>
                                    <?php endif; ?>
                                <?php else: ?>
                                    <div class="text-center py-4">
                                        <i class="fas fa-chart-line fa-3x text-muted mb-3"></i>
                                        <h5>No Usage History</h5>
                                        <p class="text-muted">This discount code hasn't been used yet.</p>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <!-- Top Users -->
                    <div class="col-lg-4">
                        <div class="card">
                            <div class="card-header">
                                <h6 class="mb-0"><i class="fas fa-users me-2"></i>Top Users</h6>
                            </div>
                            <div class="card-body">
                                <?php if (count($top_users) > 0): ?>
                                    <div class="list-group list-group-flush">
                                        <?php foreach ($top_users as $index => $user): ?>
                                            <div class="list-group-item d-flex justify-content-between align-items-start">
                                                <div class="ms-2 me-auto">
                                                    <div class="fw-bold"><?php echo htmlspecialchars($user['username']); ?></div>
                                                    <small class="text-muted"><?php echo htmlspecialchars($user['email']); ?></small>
                                                </div>
                                                <div class="text-end">
                                                    <span class="badge bg-primary rounded-pill"><?php echo $user['usage_count']; ?> uses</span>
                                                    <br><small class="text-muted"><?php echo number_format($user['total_savings']); ?> VND saved</small>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                <?php else: ?>
                                    <div class="text-center">
                                        <i class="fas fa-users fa-2x text-muted mb-2"></i>
                                        <p class="text-muted">No users have used this code yet.</p>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
