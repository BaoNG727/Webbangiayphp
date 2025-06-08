<?php
session_start();
require_once "../includes/config.php";

// Check if user is logged in and is admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

// Handle discount code deletion
if (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
    $id = $_GET['delete'];
    $delete_query = "DELETE FROM discount_codes WHERE id = ?";
    $stmt = $pdo->prepare($delete_query);
    if ($stmt->execute([$id])) {
        $success_message = "Discount code deleted successfully!";
    } else {
        $error_message = "Error deleting discount code.";
    }
}

// Handle status toggle
if (isset($_POST['toggle_status']) && isset($_POST['code_id'])) {
    $code_id = $_POST['code_id'];
    $toggle_query = "UPDATE discount_codes SET is_active = NOT is_active WHERE id = ?";
    $stmt = $pdo->prepare($toggle_query);
    if ($stmt->execute([$code_id])) {
        $success_message = "Discount code status updated successfully!";
    } else {
        $error_message = "Error updating discount code status.";
    }
}

// Get all discount codes with pagination
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$per_page = 10;
$offset = ($page - 1) * $per_page;

// Search and filter functionality
$search = isset($_GET['search']) ? $_GET['search'] : '';
$status_filter = isset($_GET['status']) ? $_GET['status'] : '';

$where_conditions = [];
$params = [];

if ($search) {
    $where_conditions[] = "(code LIKE ? OR description LIKE ?)";
    $params[] = "%$search%";
    $params[] = "%$search%";
}

if ($status_filter) {
    switch ($status_filter) {
        case 'active':
            $where_conditions[] = "is_active = 1 AND valid_from <= NOW() AND valid_until >= NOW()";
            break;
        case 'inactive':
            $where_conditions[] = "is_active = 0";
            break;
        case 'expired':
            $where_conditions[] = "valid_until < NOW()";
            break;
        case 'scheduled':
            $where_conditions[] = "valid_from > NOW()";
            break;
    }
}

$where_sql = $where_conditions ? "WHERE " . implode(" AND ", $where_conditions) : "";

// Get total count
$count_query = "SELECT COUNT(*) as total FROM discount_codes $where_sql";
$stmt = $pdo->prepare($count_query);
$stmt->execute($params);
$total_codes = $stmt->fetch()['total'];
$total_pages = ceil($total_codes / $per_page);

// Get discount codes
$codes_query = "SELECT dc.*, u.username as created_by_username,
                       CASE 
                           WHEN dc.valid_until < NOW() THEN 'Expired'
                           WHEN dc.valid_from > NOW() THEN 'Scheduled'
                           WHEN dc.is_active = 0 THEN 'Inactive'
                           WHEN dc.usage_limit IS NOT NULL AND dc.usage_count >= dc.usage_limit THEN 'Used Up'
                           ELSE 'Active'
                       END as status
                FROM discount_codes dc
                LEFT JOIN users u ON dc.created_by = u.id
                $where_sql
                ORDER BY dc.created_at DESC 
                LIMIT ? OFFSET ?";

$params[] = $per_page;
$params[] = $offset;
$stmt = $pdo->prepare($codes_query);
$stmt->execute($params);
$discount_codes = $stmt->fetchAll();

// Get statistics
$stats_query = "SELECT 
    COUNT(*) as total_codes,
    SUM(CASE WHEN is_active = 1 AND valid_from <= NOW() AND valid_until >= NOW() THEN 1 ELSE 0 END) as active_codes,
    SUM(CASE WHEN valid_until < NOW() THEN 1 ELSE 0 END) as expired_codes,
    SUM(usage_count) as total_usage,
    (SELECT COALESCE(SUM(discount_amount), 0) FROM discount_code_usage) as total_savings
    FROM discount_codes";
$stmt = $pdo->prepare($stats_query);
$stmt->execute();
$stats = $stmt->fetch();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Discount Codes Management - Nike Shoe Store Admin</title>
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
                    <h1 class="h2"><i class="fas fa-tags me-2"></i>Discount Codes Management</h1>
                    <div>
                        <a href="add-discount-code.php" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Add Discount Code
                        </a>
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

                <!-- Statistics Cards -->
                <div class="row mb-4">
                    <div class="col-md-3">
                        <div class="card text-center border-primary">
                            <div class="card-body">
                                <h5 class="card-title text-primary">Total Codes</h5>
                                <h3 class="text-primary"><?php echo $stats['total_codes']; ?></h3>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card text-center border-success">
                            <div class="card-body">
                                <h5 class="card-title text-success">Active Codes</h5>
                                <h3 class="text-success"><?php echo $stats['active_codes']; ?></h3>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card text-center border-warning">
                            <div class="card-body">
                                <h5 class="card-title text-warning">Total Usage</h5>
                                <h3 class="text-warning"><?php echo $stats['total_usage']; ?></h3>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card text-center border-info">
                            <div class="card-body">
                                <h5 class="card-title text-info">Total Savings</h5>
                                <h3 class="text-info"><?php echo number_format($stats['total_savings']); ?> VND</h3>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Search and Filter -->
                <div class="row mb-3">
                    <div class="col-md-6">
                        <form method="GET" class="d-flex">
                            <input type="text" class="form-control me-2" name="search" 
                                   placeholder="Search by code or description..." 
                                   value="<?php echo htmlspecialchars($search); ?>">
                            <button type="submit" class="btn btn-outline-secondary">
                                <i class="fas fa-search"></i>
                            </button>
                        </form>
                    </div>
                    <div class="col-md-6">
                        <form method="GET" class="d-flex">
                            <?php if ($search): ?>
                                <input type="hidden" name="search" value="<?php echo htmlspecialchars($search); ?>">
                            <?php endif; ?>
                            <select name="status" class="form-select me-2">
                                <option value="">All Status</option>
                                <option value="active" <?php echo $status_filter == 'active' ? 'selected' : ''; ?>>Active</option>
                                <option value="inactive" <?php echo $status_filter == 'inactive' ? 'selected' : ''; ?>>Inactive</option>
                                <option value="expired" <?php echo $status_filter == 'expired' ? 'selected' : ''; ?>>Expired</option>
                                <option value="scheduled" <?php echo $status_filter == 'scheduled' ? 'selected' : ''; ?>>Scheduled</option>
                            </select>
                            <button type="submit" class="btn btn-outline-secondary">
                                <i class="fas fa-filter"></i>
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Discount Codes Table -->
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Code</th>
                                        <th>Description</th>
                                        <th>Type</th>
                                        <th>Value</th>
                                        <th>Usage</th>
                                        <th>Valid Period</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (count($discount_codes) > 0): ?>
                                        <?php foreach ($discount_codes as $code): ?>
                                            <tr>
                                                <td>
                                                    <strong class="text-primary"><?php echo htmlspecialchars($code['code']); ?></strong>
                                                    <br>
                                                    <small class="text-muted">by <?php echo htmlspecialchars($code['created_by_username'] ?? 'Unknown'); ?></small>
                                                </td>
                                                <td>
                                                    <?php echo htmlspecialchars($code['description'] ?: 'No description'); ?>
                                                    <?php if ($code['minimum_order_amount'] > 0): ?>
                                                        <br><small class="text-muted">Min: <?php echo number_format($code['minimum_order_amount']); ?> VND</small>
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <?php if ($code['type'] === 'percentage'): ?>
                                                        <span class="badge bg-info"><?php echo $code['value']; ?>%</span>
                                                        <?php if ($code['maximum_discount_amount']): ?>
                                                            <br><small class="text-muted">Max: <?php echo number_format($code['maximum_discount_amount']); ?> VND</small>
                                                        <?php endif; ?>
                                                    <?php else: ?>
                                                        <span class="badge bg-success"><?php echo number_format($code['value']); ?> VND</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <strong><?php echo $code['value']; ?><?php echo $code['type'] === 'percentage' ? '%' : ' VND'; ?></strong>
                                                </td>
                                                <td>
                                                    <span class="badge bg-secondary">
                                                        <?php echo $code['usage_count']; ?>
                                                        <?php if ($code['usage_limit']): ?>
                                                            / <?php echo $code['usage_limit']; ?>
                                                        <?php else: ?>
                                                            / ∞
                                                        <?php endif; ?>
                                                    </span>
                                                </td>
                                                <td>
                                                    <small>
                                                        <strong>From:</strong> <?php echo date('M d, Y', strtotime($code['valid_from'])); ?><br>
                                                        <strong>Until:</strong> <?php echo date('M d, Y', strtotime($code['valid_until'])); ?>
                                                    </small>
                                                </td>
                                                <td>
                                                    <?php
                                                    $statusClass = [
                                                        'Active' => 'success',
                                                        'Inactive' => 'secondary',
                                                        'Expired' => 'danger',
                                                        'Scheduled' => 'warning',
                                                        'Used Up' => 'dark'
                                                    ];
                                                    ?>
                                                    <span class="badge bg-<?php echo $statusClass[$code['status']] ?? 'secondary'; ?>">
                                                        <?php echo $code['status']; ?>
                                                    </span>
                                                </td>
                                                <td>
                                                    <div class="btn-group" role="group">
                                                        <a href="edit-discount-code.php?id=<?php echo $code['id']; ?>" 
                                                           class="btn btn-sm btn-outline-primary" title="Edit">
                                                            <i class="fas fa-edit"></i>
                                                        </a>
                                                        <form method="POST" class="d-inline">
                                                            <input type="hidden" name="code_id" value="<?php echo $code['id']; ?>">
                                                            <button type="submit" name="toggle_status" 
                                                                    class="btn btn-sm btn-outline-<?php echo $code['is_active'] ? 'warning' : 'success'; ?>"
                                                                    title="<?php echo $code['is_active'] ? 'Deactivate' : 'Activate'; ?>">
                                                                <i class="fas fa-<?php echo $code['is_active'] ? 'pause' : 'play'; ?>"></i>
                                                            </button>
                                                        </form>
                                                        <a href="discount-code-usage.php?id=<?php echo $code['id']; ?>" 
                                                           class="btn btn-sm btn-outline-info" title="View Usage">
                                                            <i class="fas fa-chart-line"></i>
                                                        </a>
                                                        <a href="?delete=<?php echo $code['id']; ?>" 
                                                           class="btn btn-sm btn-outline-danger"
                                                           onclick="return confirm('Are you sure you want to delete this discount code?')"
                                                           title="Delete">
                                                            <i class="fas fa-trash"></i>
                                                        </a>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="8" class="text-center">
                                                <div class="py-4">
                                                    <i class="fas fa-tags fa-3x text-muted mb-3"></i>
                                                    <h5>No discount codes found</h5>
                                                    <p class="text-muted">Create your first discount code to get started</p>
                                                    <a href="add-discount-code.php" class="btn btn-primary">
                                                        <i class="fas fa-plus"></i> Add Discount Code
                                                    </a>
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
                    <nav aria-label="Discount codes pagination" class="mt-4">
                        <ul class="pagination justify-content-center">
                            <?php if ($page > 1): ?>
                                <li class="page-item">
                                    <a class="page-link" href="?page=<?php echo $page - 1; ?><?php echo $search ? '&search=' . urlencode($search) : ''; ?><?php echo $status_filter ? '&status=' . urlencode($status_filter) : ''; ?>">Previous</a>
                                </li>
                            <?php endif; ?>

                            <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                                <li class="page-item <?php echo $i == $page ? 'active' : ''; ?>">
                                    <a class="page-link" href="?page=<?php echo $i; ?><?php echo $search ? '&search=' . urlencode($search) : ''; ?><?php echo $status_filter ? '&status=' . urlencode($status_filter) : ''; ?>"><?php echo $i; ?></a>
                                </li>
                            <?php endfor; ?>

                            <?php if ($page < $total_pages): ?>
                                <li class="page-item">
                                    <a class="page-link" href="?page=<?php echo $page + 1; ?><?php echo $search ? '&search=' . urlencode($search) : ''; ?><?php echo $status_filter ? '&status=' . urlencode($status_filter) : ''; ?>">Next</a>
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
