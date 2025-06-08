<?php
session_start();
require_once "../includes/config.php";

// Check if user is logged in and is admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

// Get product ID from URL
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: products.php");
    exit;
}

$product_id = $_GET['id'];

// Get categories for dropdown
$categories_query = "SELECT * FROM categories ORDER BY name";
$categories_stmt = $pdo->prepare($categories_query);
$categories_stmt->execute();
$categories = $categories_stmt->fetchAll(PDO::FETCH_ASSOC);

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = trim($_POST['name']);
    $description = trim($_POST['description']);
    $price = floatval($_POST['price']);
    $size = trim($_POST['size']);
    $category_id = !empty($_POST['category_id']) ? (int)$_POST['category_id'] : null;
    $brand = trim($_POST['brand']);
    $color = trim($_POST['color']);
    $stock_quantity = (int)$_POST['stock_quantity'];
    
    // Handle image upload
    $image_name = $_POST['current_image']; // Keep current image by default
    
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $allowed_types = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
        $file_type = $_FILES['image']['type'];
        
        if (in_array($file_type, $allowed_types)) {
            $file_extension = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
            $image_name = uniqid() . '.' . $file_extension;
            $upload_path = "../uploads/" . $image_name;
            
            if (move_uploaded_file($_FILES['image']['tmp_name'], $upload_path)) {
                // Delete old image if it exists and is not the default
                if (!empty($_POST['current_image']) && $_POST['current_image'] != 'nike-logo.png') {
                    $old_image_path = "../uploads/" . $_POST['current_image'];
                    if (file_exists($old_image_path)) {
                        unlink($old_image_path);
                    }
                }
            } else {
                $error_message = "Error uploading image.";
                $image_name = $_POST['current_image'];
            }
        } else {
            $error_message = "Invalid file type. Please upload JPG, PNG, or GIF images only.";
            $image_name = $_POST['current_image'];
        }
    }
      // Update product if no errors
    if (!isset($error_message)) {
        $update_query = "UPDATE products SET name = ?, description = ?, price = ?, size = ?, image = ?, category_id = ?, brand = ?, color = ?, stock_quantity = ?, updated_at = CURRENT_TIMESTAMP WHERE id = ?";
        $stmt = $pdo->prepare($update_query);
        
        if ($stmt->execute([$name, $description, $price, $size, $image_name, $category_id, $brand, $color, $stock_quantity, $product_id])) {
            $success_message = "Product updated successfully!";
        } else {
            $error_message = "Error updating product.";
        }
    }
}

// Get current product data
$product_query = "SELECT p.*, c.name as category_name FROM products p LEFT JOIN categories c ON p.category_id = c.id WHERE p.id = ?";
$stmt = $pdo->prepare($product_query);
$stmt->execute([$product_id]);
$product = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$product) {
    header("Location: products.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Product - Nike Shoe Store Admin</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/admin.css">
    <style>
        .product-preview {
            max-width: 200px;
            height: 200px;
            object-fit: cover;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        .form-card {
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            border: none;
            border-radius: 12px;
        }
        .form-card .card-header {
            background: linear-gradient(135deg, #ff6900, #ff8533);
            color: white;
            border-radius: 12px 12px 0 0;
        }
        .preview-container {
            background: #f8f9fa;
            border-radius: 8px;
            padding: 20px;
            text-align: center;
            border: 2px dashed #dee2e6;
        }
        .file-upload-wrapper {
            position: relative;
            overflow: hidden;
            display: inline-block;
        }
        .file-upload-wrapper input[type=file] {
            position: absolute;
            left: -9999px;
        }
        .file-upload-label {
            cursor: pointer;
            display: inline-block;
            padding: 8px 16px;
            background: #6c757d;
            color: white;
            border-radius: 4px;
            transition: background-color 0.3s;
        }
        .file-upload-label:hover {
            background: #5a6268;
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
                            <a class="nav-link active text-white" href="products.php">
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
                        <i class="fas fa-edit me-2"></i>Edit Product: <?php echo htmlspecialchars($product['name']); ?>
                    </h1>
                    <div class="btn-toolbar mb-2 mb-md-0">
                        <div class="btn-group me-2" role="group">
                            <a href="products.php" class="btn btn-outline-secondary">
                                <i class="fas fa-arrow-left me-2"></i>Back to Products
                            </a>
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
                    <!-- Edit Form -->
                    <div class="col-lg-8">
                        <div class="card form-card">
                            <div class="card-header">
                                <h5 class="mb-0"><i class="fas fa-edit me-2"></i>Product Information</h5>
                            </div>
                            <div class="card-body">
                                <form method="POST" enctype="multipart/form-data">
                                    <input type="hidden" name="current_image" value="<?php echo htmlspecialchars($product['image']); ?>">
                                    
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="name" class="form-label">Product Name *</label>
                                            <input type="text" class="form-control" id="name" name="name" 
                                                   value="<?php echo htmlspecialchars($product['name']); ?>" required>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="price" class="form-label">Price (VND) *</label>
                                            <input type="number" class="form-control" id="price" name="price" 
                                                   value="<?php echo $product['price']; ?>" min="0" step="1000" required>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="size" class="form-label">Size *</label>
                                            <input type="text" class="form-control" id="size" name="size" 
                                                   value="<?php echo htmlspecialchars($product['size']); ?>" 
                                                   placeholder="e.g., 42, 41-43, M, L" required>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="category_id" class="form-label">Category</label>
                                            <select class="form-select" id="category_id" name="category_id">
                                                <option value="">-- Select Category --</option>
                                                <?php foreach ($categories as $category): ?>
                                                <option value="<?php echo $category['id']; ?>" 
                                                        <?php echo $product['category_id'] == $category['id'] ? 'selected' : ''; ?>>
                                                    <?php echo htmlspecialchars($category['name']); ?>
                                                </option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label for="description" class="form-label">Description</label>
                                        <textarea class="form-control" id="description" name="description" rows="4" 
                                                  placeholder="Enter product description..."><?php echo htmlspecialchars($product['description']); ?></textarea>
                                    </div>

                                    <div class="mb-3">
                                        <label for="image" class="form-label">Product Image</label>
                                        <div class="file-upload-wrapper">
                                            <input type="file" id="image" name="image" accept="image/*" onchange="previewImage(this)">
                                            <label for="image" class="file-upload-label">
                                                <i class="fas fa-upload me-2"></i>Choose New Image
                                            </label>
                                        </div>
                                        <small class="form-text text-muted">Leave empty to keep current image. Supported formats: JPG, PNG, GIF</small>
                                    </div>                                    <div class="mb-3">
                                        <label for="brand" class="form-label">Brand</label>
                                        <input type="text" class="form-control" id="brand" name="brand" 
                                               value="<?php echo htmlspecialchars($product['brand']); ?>" required>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="color" class="form-label">Color</label>
                                                <input type="text" class="form-control" id="color" name="color" 
                                                       value="<?php echo htmlspecialchars($product['color']); ?>">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="stock_quantity" class="form-label">Stock Quantity</label>
                                                <input type="number" class="form-control" id="stock_quantity" name="stock_quantity" 
                                                       value="<?php echo $product['stock_quantity']; ?>" min="0" required>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                        <a href="products.php" class="btn btn-secondary me-md-2">
                                            <i class="fas fa-times me-2"></i>Cancel
                                        </a>
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fas fa-save me-2"></i>Update Product
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Product Preview -->
                    <div class="col-lg-4">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="mb-0"><i class="fas fa-eye me-2"></i>Product Preview</h5>
                            </div>
                            <div class="card-body">
                                <div class="preview-container">
                                    <img id="preview-image" 
                                         src="../uploads/<?php echo $product['image'] ? $product['image'] : 'nike-logo.png'; ?>" 
                                         alt="Product Preview" 
                                         class="product-preview mb-3"
                                         onerror="this.src='../assets/images/nike-logo.png'">                                    <h6 class="mb-2"><?php echo htmlspecialchars($product['name']); ?></h6>
                                    <p class="text-muted mb-1">Brand: <?php echo htmlspecialchars($product['brand']); ?></p>
                                    <p class="text-muted mb-1">Size: <?php echo htmlspecialchars($product['size']); ?></p>
                                    <?php if ($product['color']): ?>
                                    <p class="text-muted mb-1">Color: <?php echo htmlspecialchars($product['color']); ?></p>
                                    <?php endif; ?>
                                    <p class="text-primary fw-bold">$<?php echo number_format($product['price'], 2); ?></p>                                    <span class="badge bg-<?php echo $product['stock_quantity'] > 0 ? 'success' : 'danger'; ?>">
                                        Stock: <?php echo $product['stock_quantity']; ?>
                                    </span>
                                </div>
                            </div>
                        </div>

                        <!-- Product Statistics -->
                        <div class="card mt-4">
                            <div class="card-header">
                                <h5 class="mb-0"><i class="fas fa-chart-bar me-2"></i>Product Stats</h5>
                            </div>
                            <div class="card-body">
                                <div class="row text-center">
                                    <div class="col-6">
                                        <h6>Created</h6>
                                        <p class="text-muted"><?php echo date('M d, Y', strtotime($product['created_at'])); ?></p>
                                    </div>
                                    <div class="col-6">
                                        <h6>Last Updated</h6>
                                        <p class="text-muted">
                                            <?php echo $product['updated_at'] ? date('M d, Y', strtotime($product['updated_at'])) : 'Never'; ?>
                                        </p>
                                    </div>
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
        function previewImage(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('preview-image').src = e.target.result;
                }
                reader.readAsDataURL(input.files[0]);
            }
        }

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
