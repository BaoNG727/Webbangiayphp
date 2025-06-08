<?php
session_start();
require_once "../includes/config.php";

// Check if user is logged in and is admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

$error_message = '';
$success_message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $code = strtoupper(trim($_POST['code']));
    $description = trim($_POST['description']);
    $type = $_POST['type'];
    $value = floatval($_POST['value']);
    $minimum_order_amount = floatval($_POST['minimum_order_amount']);
    $maximum_discount_amount = !empty($_POST['maximum_discount_amount']) ? floatval($_POST['maximum_discount_amount']) : null;
    $usage_limit = !empty($_POST['usage_limit']) ? intval($_POST['usage_limit']) : null;
    $user_usage_limit = !empty($_POST['user_usage_limit']) ? intval($_POST['user_usage_limit']) : null;
    $valid_from = $_POST['valid_from'];
    $valid_until = $_POST['valid_until'];
    $is_active = isset($_POST['is_active']) ? 1 : 0;

    // Validation
    if (empty($code) || empty($type) || $value <= 0 || empty($valid_from) || empty($valid_until)) {
        $error_message = "Please fill in all required fields.";
    } elseif (strlen($code) < 3 || strlen($code) > 50) {
        $error_message = "Code must be between 3 and 50 characters.";
    } elseif ($type === 'percentage' && $value > 100) {
        $error_message = "Percentage discount cannot exceed 100%.";
    } elseif (strtotime($valid_from) >= strtotime($valid_until)) {
        $error_message = "Valid from date must be before valid until date.";
    } else {
        // Check if code already exists
        $check_stmt = $pdo->prepare("SELECT id FROM discount_codes WHERE code = ?");
        $check_stmt->execute([$code]);
        
        if ($check_stmt->rowCount() > 0) {
            $error_message = "Discount code already exists. Please choose a different code.";
        } else {
            try {
                $stmt = $pdo->prepare("
                    INSERT INTO discount_codes (
                        code, description, type, value, minimum_order_amount, 
                        maximum_discount_amount, usage_limit, user_usage_limit, 
                        valid_from, valid_until, is_active, created_by
                    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
                ");
                
                $result = $stmt->execute([
                    $code, $description, $type, $value, $minimum_order_amount,
                    $maximum_discount_amount, $usage_limit, $user_usage_limit,
                    $valid_from, $valid_until, $is_active, $_SESSION['user_id']
                ]);
                
                if ($result) {
                    $success_message = "Discount code created successfully!";
                    // Clear form data
                    $_POST = [];
                } else {
                    $error_message = "Failed to create discount code.";
                }
            } catch (Exception $e) {
                $error_message = "Error: " . $e->getMessage();
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Discount Code - Nike Shoe Store Admin</title>
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
                    <h1 class="h2"><i class="fas fa-plus me-2"></i>Add Discount Code</h1>
                    <div>
                        <a href="discount-codes.php" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left"></i> Back to Discount Codes
                        </a>
                    </div>
                </div>

                <?php if ($success_message): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <?php echo $success_message; ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <?php if ($error_message): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <?php echo $error_message; ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <div class="row">
                    <div class="col-lg-8">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="mb-0">Discount Code Details</h5>
                            </div>
                            <div class="card-body">
                                <form method="POST" class="needs-validation" novalidate>
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="code" class="form-label">Discount Code <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" id="code" name="code" 
                                                   value="<?php echo htmlspecialchars($_POST['code'] ?? ''); ?>" 
                                                   placeholder="e.g., SAVE20, WELCOME10" required maxlength="50">
                                            <div class="form-text">3-50 characters, will be converted to uppercase</div>
                                        </div>
                                        
                                        <div class="col-md-6 mb-3">
                                            <label for="type" class="form-label">Discount Type <span class="text-danger">*</span></label>
                                            <select class="form-select" id="type" name="type" required onchange="toggleDiscountFields()">
                                                <option value="">Select type</option>
                                                <option value="percentage" <?php echo ($_POST['type'] ?? '') === 'percentage' ? 'selected' : ''; ?>>Percentage (%)</option>
                                                <option value="fixed" <?php echo ($_POST['type'] ?? '') === 'fixed' ? 'selected' : ''; ?>>Fixed Amount (VND)</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label for="description" class="form-label">Description</label>
                                        <textarea class="form-control" id="description" name="description" rows="3" 
                                                  placeholder="Brief description of the discount code"><?php echo htmlspecialchars($_POST['description'] ?? ''); ?></textarea>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="value" class="form-label">Discount Value <span class="text-danger">*</span></label>
                                            <div class="input-group">
                                                <input type="number" class="form-control" id="value" name="value" 
                                                       value="<?php echo htmlspecialchars($_POST['value'] ?? ''); ?>" 
                                                       min="0" step="0.01" required>
                                                <span class="input-group-text" id="value-suffix">%</span>
                                            </div>
                                            <div class="form-text" id="value-help">Enter discount value</div>
                                        </div>
                                        
                                        <div class="col-md-6 mb-3">
                                            <label for="minimum_order_amount" class="form-label">Minimum Order Amount</label>
                                            <div class="input-group">
                                                <input type="number" class="form-control" id="minimum_order_amount" 
                                                       name="minimum_order_amount" 
                                                       value="<?php echo htmlspecialchars($_POST['minimum_order_amount'] ?? '0'); ?>" 
                                                       min="0" step="1000">
                                                <span class="input-group-text">VND</span>
                                            </div>
                                            <div class="form-text">Minimum order amount to use this code</div>
                                        </div>
                                    </div>

                                    <div class="row" id="max-discount-row" style="display: none;">
                                        <div class="col-md-6 mb-3">
                                            <label for="maximum_discount_amount" class="form-label">Maximum Discount Amount</label>
                                            <div class="input-group">
                                                <input type="number" class="form-control" id="maximum_discount_amount" 
                                                       name="maximum_discount_amount" 
                                                       value="<?php echo htmlspecialchars($_POST['maximum_discount_amount'] ?? ''); ?>" 
                                                       min="0" step="1000">
                                                <span class="input-group-text">VND</span>
                                            </div>
                                            <div class="form-text">Maximum discount amount for percentage discounts</div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="usage_limit" class="form-label">Total Usage Limit</label>
                                            <input type="number" class="form-control" id="usage_limit" name="usage_limit" 
                                                   value="<?php echo htmlspecialchars($_POST['usage_limit'] ?? ''); ?>" 
                                                   min="1" placeholder="Leave empty for unlimited">
                                            <div class="form-text">Total number of times this code can be used</div>
                                        </div>
                                        
                                        <div class="col-md-6 mb-3">
                                            <label for="user_usage_limit" class="form-label">Per User Usage Limit</label>
                                            <input type="number" class="form-control" id="user_usage_limit" name="user_usage_limit" 
                                                   value="<?php echo htmlspecialchars($_POST['user_usage_limit'] ?? ''); ?>" 
                                                   min="1" placeholder="Leave empty for unlimited">
                                            <div class="form-text">Number of times each user can use this code</div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="valid_from" class="form-label">Valid From <span class="text-danger">*</span></label>
                                            <input type="datetime-local" class="form-control" id="valid_from" name="valid_from" 
                                                   value="<?php echo htmlspecialchars($_POST['valid_from'] ?? date('Y-m-d\TH:i')); ?>" required>
                                        </div>
                                        
                                        <div class="col-md-6 mb-3">
                                            <label for="valid_until" class="form-label">Valid Until <span class="text-danger">*</span></label>
                                            <input type="datetime-local" class="form-control" id="valid_until" name="valid_until" 
                                                   value="<?php echo htmlspecialchars($_POST['valid_until'] ?? date('Y-m-d\TH:i', strtotime('+30 days'))); ?>" required>
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="is_active" name="is_active" 
                                                   <?php echo isset($_POST['is_active']) || empty($_POST) ? 'checked' : ''; ?>>
                                            <label class="form-check-label" for="is_active">
                                                Active (code can be used immediately)
                                            </label>
                                        </div>
                                    </div>

                                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                        <a href="discount-codes.php" class="btn btn-outline-secondary me-md-2">
                                            <i class="fas fa-times"></i> Cancel
                                        </a>
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fas fa-save"></i> Create Discount Code
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-lg-4">
                        <div class="card">
                            <div class="card-header">
                                <h6 class="mb-0"><i class="fas fa-info-circle me-2"></i>Tips for Creating Discount Codes</h6>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <h6 class="text-primary">Code Best Practices:</h6>
                                    <ul class="small">
                                        <li>Use descriptive codes (e.g., WELCOME10, STUDENT20)</li>
                                        <li>Keep codes simple and easy to remember</li>
                                        <li>Avoid special characters and spaces</li>
                                    </ul>
                                </div>
                                
                                <div class="mb-3">
                                    <h6 class="text-success">Percentage Discounts:</h6>
                                    <ul class="small">
                                        <li>Set maximum discount amounts to control costs</li>
                                        <li>Ideal for encouraging larger purchases</li>
                                        <li>Values should be between 1-100%</li>
                                    </ul>
                                </div>
                                
                                <div class="mb-3">
                                    <h6 class="text-info">Fixed Amount Discounts:</h6>
                                    <ul class="small">
                                        <li>Great for specific promotional campaigns</li>
                                        <li>Easy for customers to understand</li>
                                        <li>Set minimum order amounts to ensure profitability</li>
                                    </ul>
                                </div>
                                
                                <div class="alert alert-warning">
                                    <small><strong>Note:</strong> Always test discount codes before promoting them to customers.</small>
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
        function toggleDiscountFields() {
            const type = document.getElementById('type').value;
            const valueSuffix = document.getElementById('value-suffix');
            const valueHelp = document.getElementById('value-help');
            const maxDiscountRow = document.getElementById('max-discount-row');
            const valueInput = document.getElementById('value');
            
            if (type === 'percentage') {
                valueSuffix.textContent = '%';
                valueHelp.textContent = 'Enter percentage (1-100)';
                maxDiscountRow.style.display = 'block';
                valueInput.max = '100';
            } else if (type === 'fixed') {
                valueSuffix.textContent = 'VND';
                valueHelp.textContent = 'Enter fixed amount in VND';
                maxDiscountRow.style.display = 'none';
                valueInput.removeAttribute('max');
            }
        }
        
        // Initialize on page load
        document.addEventListener('DOMContentLoaded', function() {
            toggleDiscountFields();
        });
        
        // Form validation
        (function() {
            'use strict';
            window.addEventListener('load', function() {
                var forms = document.getElementsByClassName('needs-validation');
                var validation = Array.prototype.filter.call(forms, function(form) {
                    form.addEventListener('submit', function(event) {
                        if (form.checkValidity() === false) {
                            event.preventDefault();
                            event.stopPropagation();
                        }
                        form.classList.add('was-validated');
                    }, false);
                });
            }, false);
        })();
    </script>
</body>
</html>
