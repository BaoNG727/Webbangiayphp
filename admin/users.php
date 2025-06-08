<?php
session_start();
require_once "../includes/config.php";

// Check if user is logged in and is admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

// Handle user role update
if (isset($_POST['update_role']) && isset($_POST['user_id']) && isset($_POST['role'])) {
    $user_id = $_POST['user_id'];
    $role = $_POST['role'];
    
    $update_query = "UPDATE users SET role = ? WHERE id = ?";
    $stmt = $pdo->prepare($update_query);
    if ($stmt->execute([$role, $user_id])) {
        $success_message = "User role updated successfully!";
    } else {
        $error_message = "Error updating user role.";
    }
}

// Handle user deletion
if (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
    $user_id = $_GET['delete'];
    
    // Don't allow deletion of the current admin user
    if ($user_id == $_SESSION['user_id']) {
        $error_message = "You cannot delete your own account.";
    } else {
        $delete_query = "DELETE FROM users WHERE id = ?";
        $stmt = $pdo->prepare($delete_query);
        if ($stmt->execute([$user_id])) {
            $success_message = "User deleted successfully!";
        } else {
            $error_message = "Error deleting user.";
        }
    }
}

// Get all users with pagination
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$per_page = 10;
$offset = ($page - 1) * $per_page;

// Filter by role
$role_filter = isset($_GET['role']) ? $_GET['role'] : '';
$role_sql = '';
$params = [];

if ($role_filter) {
    $role_sql = " WHERE role = ?";
    $params = [$role_filter];
}

// Search functionality
$search = isset($_GET['search']) ? $_GET['search'] : '';
if ($search) {
    if ($role_filter) {
        $role_sql .= " AND (name LIKE ? OR email LIKE ?)";
    } else {
        $role_sql = " WHERE (name LIKE ? OR email LIKE ?)";
    }
    $params[] = "%$search%";
    $params[] = "%$search%";
}

// Get total count
$count_query = "SELECT COUNT(*) as total FROM users" . $role_sql;
$stmt = $pdo->prepare($count_query);
$stmt->execute($params);
$total_users = $stmt->fetch()['total'];
$total_pages = ceil($total_users / $per_page);

// Get users
$users_query = "SELECT * FROM users" . $role_sql . " ORDER BY created_at DESC LIMIT ? OFFSET ?";
$params[] = $per_page;
$params[] = $offset;
$stmt = $pdo->prepare($users_query);
$stmt->execute($params);
$users = $stmt->fetchAll();

// Get user role counts
$role_counts = [];
$role_query = "SELECT role, COUNT(*) as count FROM users GROUP BY role";
$stmt = $pdo->prepare($role_query);
$stmt->execute();
$results = $stmt->fetchAll();
foreach ($results as $result) {
    $role_counts[$result['role']] = $result['count'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Users Management - Nike Shoe Store Admin</title>
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
                        </li>                        <li class="nav-item">
                            <a class="nav-link active text-white" href="users.php">
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
            
            <!-- Main Content -->
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2">Users Management</h1>
                    <div>
                        <span class="text-muted">Total Users: <?php echo $total_users; ?></span>
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

                <!-- User Role Summary -->
                <div class="row mb-4">
                    <div class="col-md-3">
                        <div class="card text-center">
                            <div class="card-body">
                                <h5 class="card-title">Total Users</h5>
                                <h3 class="text-primary"><?php echo array_sum($role_counts); ?></h3>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card text-center">
                            <div class="card-body">
                                <h5 class="card-title">Customers</h5>
                                <h3 class="text-success"><?php echo $role_counts['customer'] ?? 0; ?></h3>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card text-center">
                            <div class="card-body">
                                <h5 class="card-title">Admins</h5>
                                <h3 class="text-warning"><?php echo $role_counts['admin'] ?? 0; ?></h3>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Search and Filter -->
                <div class="row mb-3">
                    <div class="col-md-6">
                        <form method="GET" class="d-flex">
                            <input type="text" class="form-control me-2" name="search" placeholder="Search users..." value="<?php echo htmlspecialchars($search); ?>">
                            <select name="role" class="form-select me-2">
                                <option value="">All Roles</option>
                                <option value="customer" <?php echo $role_filter == 'customer' ? 'selected' : ''; ?>>Customer</option>
                                <option value="admin" <?php echo $role_filter == 'admin' ? 'selected' : ''; ?>>Admin</option>
                            </select>
                            <button type="submit" class="btn btn-outline-secondary">
                                <i class="fas fa-search"></i>
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Users Table -->
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Role</th>
                                        <th>Joined</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (count($users) > 0): ?>
                                        <?php foreach ($users as $user): ?>
                                            <tr>
                                                <td><strong>#<?php echo $user['id']; ?></strong></td>
                                                <td>
                                                    <strong><?php echo htmlspecialchars($user['name']); ?></strong>
                                                    <?php if ($user['id'] == $_SESSION['user_id']): ?>
                                                        <span class="badge bg-info ms-2">You</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td><?php echo htmlspecialchars($user['email']); ?></td>
                                                <td>
                                                    <?php if ($user['id'] != $_SESSION['user_id']): ?>
                                                        <form method="POST" class="d-inline">
                                                            <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">
                                                            <select name="role" class="form-select form-select-sm" onchange="this.form.submit()">
                                                                <option value="customer" <?php echo $user['role'] == 'customer' ? 'selected' : ''; ?>>Customer</option>
                                                                <option value="admin" <?php echo $user['role'] == 'admin' ? 'selected' : ''; ?>>Admin</option>
                                                            </select>
                                                            <input type="hidden" name="update_role" value="1">
                                                        </form>
                                                    <?php else: ?>
                                                        <span class="badge bg-<?php echo $user['role'] == 'admin' ? 'warning' : 'success'; ?>">
                                                            <?php echo ucfirst($user['role']); ?>
                                                        </span>
                                                    <?php endif; ?>
                                                </td>
                                                <td><?php echo date('M d, Y', strtotime($user['created_at'])); ?></td>
                                                <td>
                                                    <div class="btn-group" role="group">
                                                        <a href="user-details.php?id=<?php echo $user['id']; ?>" 
                                                           class="btn btn-sm btn-outline-info">
                                                            <i class="fas fa-eye"></i>
                                                        </a>
                                                        <?php if ($user['id'] != $_SESSION['user_id']): ?>
                                                            <a href="?delete=<?php echo $user['id']; ?>" 
                                                               class="btn btn-sm btn-outline-danger"
                                                               onclick="return confirm('Are you sure you want to delete this user?')">
                                                                <i class="fas fa-trash"></i>
                                                            </a>
                                                        <?php endif; ?>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="6" class="text-center">
                                                <div class="py-4">
                                                    <i class="fas fa-users fa-3x text-muted mb-3"></i>
                                                    <h5>No users found</h5>
                                                    <p class="text-muted">Users will appear here when they register</p>
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
                    <nav aria-label="Users pagination" class="mt-4">
                        <ul class="pagination justify-content-center">
                            <?php if ($page > 1): ?>
                                <li class="page-item">
                                    <a class="page-link" href="?page=<?php echo $page - 1; ?><?php echo $role_filter ? '&role=' . urlencode($role_filter) : ''; ?><?php echo $search ? '&search=' . urlencode($search) : ''; ?>">Previous</a>
                                </li>
                            <?php endif; ?>

                            <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                                <li class="page-item <?php echo $i == $page ? 'active' : ''; ?>">
                                    <a class="page-link" href="?page=<?php echo $i; ?><?php echo $role_filter ? '&role=' . urlencode($role_filter) : ''; ?><?php echo $search ? '&search=' . urlencode($search) : ''; ?>"><?php echo $i; ?></a>
                                </li>
                            <?php endfor; ?>

                            <?php if ($page < $total_pages): ?>
                                <li class="page-item">
                                    <a class="page-link" href="?page=<?php echo $page + 1; ?><?php echo $role_filter ? '&role=' . urlencode($role_filter) : ''; ?><?php echo $search ? '&search=' . urlencode($search) : ''; ?>">Next</a>
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
