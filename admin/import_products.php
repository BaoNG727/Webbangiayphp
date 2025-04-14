<?php
// Yêu cầu file config để kết nối database
require_once '../includes/config.php';

// Kiểm tra xem người dùng hiện tại có phải là admin hay không
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit;
}

// Function để xử lý upload file
function handleFileUpload() {
    // Kiểm tra nếu file được gửi lên
    if (!isset($_FILES['csv_file']) || $_FILES['csv_file']['error'] != 0) {
        return [
            'success' => false,
            'message' => 'Vui lòng chọn file CSV để upload.'
        ];
    }
    
    $file = $_FILES['csv_file'];
    $fileName = $file['name'];
    $fileType = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
    
    // Kiểm tra định dạng file (chỉ chấp nhận CSV)
    if ($fileType != 'csv') {
        return [
            'success' => false,
            'message' => 'Chỉ chấp nhận file CSV.'
        ];
    }
    
    // Đọc file CSV
    $csvData = [];
    if (($handle = fopen($file['tmp_name'], 'r')) !== false) {
        // Đọc header
        $header = fgetcsv($handle, 1000, ',');
        
        // Đọc dữ liệu từng dòng
        while (($data = fgetcsv($handle, 1000, ',')) !== false) {
            if (count($header) == count($data)) {
                $csvData[] = array_combine($header, $data);
            }
        }
        fclose($handle);
    } else {
        return [
            'success' => false,
            'message' => 'Không thể đọc file CSV.'
        ];
    }
    
    // Kiểm tra dữ liệu CSV
    if (empty($csvData)) {
        return [
            'success' => false,
            'message' => 'File CSV không có dữ liệu hoặc định dạng không đúng.'
        ];
    }
    
    return [
        'success' => true,
        'data' => $csvData
    ];
}

// Function để lưu sản phẩm từ dữ liệu CSV vào database
function saveProducts($products) {
    global $conn;
    
    $totalProducts = count($products);
    $successCount = 0;
    $errors = [];
    
    foreach ($products as $key => $product) {
        // Kiểm tra các trường bắt buộc
        if (empty($product['name']) || empty($product['price'])) {
            $errors[] = "Dòng " . ($key + 2) . ": Thiếu tên hoặc giá sản phẩm.";
            continue;
        }
        
        // Xử lý hình ảnh nếu có URL
        $image = '';
        if (!empty($product['image_url'])) {
            $image = saveImage($product['image_url'], $product['category']);
        } elseif (!empty($product['image'])) {
            $image = $product['image'];
        }
        
        // Chuẩn bị giá và giá khuyến mãi
        $price = floatval($product['price']);
        $salePrice = !empty($product['sale_price']) ? floatval($product['sale_price']) : null;
        
        // Chuẩn bị số lượng tồn kho
        $stock = !empty($product['stock']) ? intval($product['stock']) : 10;
        
        // Chuẩn bị câu lệnh SQL
        $sql = "INSERT INTO products (name, description, price, sale_price, category, image, stock) 
                VALUES (?, ?, ?, ?, ?, ?, ?)";
        
        // Chuẩn bị và thực thi statement
        $stmt = $conn->prepare($sql);
        $stmt->bind_param(
            'ssddsis',
            $product['name'],
            $product['description'],
            $price,
            $salePrice,
            $product['category'],
            $image,
            $stock
        );
        
        if ($stmt->execute()) {
            $successCount++;
        } else {
            $errors[] = "Dòng " . ($key + 2) . ": " . $stmt->error;
        }
    }
    
    return [
        'total' => $totalProducts,
        'success' => $successCount,
        'errors' => $errors
    ];
}

// Function để lưu hình ảnh từ URL
function saveImage($imageUrl, $category) {
    $imageName = basename($imageUrl);
    // Đảm bảo tên file không chứa ký tự đặc biệt
    $imageName = preg_replace('/[^a-zA-Z0-9_.-]/', '', $imageName);
    
    // Nếu không có phần mở rộng .jpg hoặc .png, thêm .jpg
    if (!preg_match('/(\.jpg|\.jpeg|\.png)$/i', $imageName)) {
        $imageName .= '.jpg';
    }
    
    // Thêm danh mục vào tên file để dễ quản lý
    $imageName = 'nike-' . strtolower($category) . '-' . $imageName;
    
    $savePath = '../uploads/' . $imageName;
    
    // Tải và lưu hình ảnh
    file_put_contents($savePath, file_get_contents($imageUrl));
    
    return $imageName;
}

// Xử lý form khi submit
$result = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['import_csv'])) {
    // Xử lý upload file CSV
    $fileUploadResult = handleFileUpload();
    
    if ($fileUploadResult['success']) {
        // Lưu sản phẩm vào database
        $result = saveProducts($fileUploadResult['data']);
    } else {
        $result = [
            'error' => $fileUploadResult['message']
        ];
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Import Products - Nike Shoes</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/Webgiay/assets/css/admin.css">
</head>
<body>
    <div class="container mt-4">
        <h1>Import Nike Products from CSV</h1>
        
        <?php if ($result !== null): ?>
            <div class="alert <?php echo isset($result['error']) ? 'alert-danger' : 'alert-success'; ?> mt-3">
                <?php if (isset($result['error'])): ?>
                    <h5>Error:</h5>
                    <p><?php echo $result['error']; ?></p>
                <?php else: ?>
                    <h5>Import Complete!</h5>
                    <p>Total products: <?php echo $result['total']; ?></p>
                    <p>Successfully imported: <?php echo $result['success']; ?></p>
                    
                    <?php if (!empty($result['errors'])): ?>
                        <h6>Errors:</h6>
                        <ul>
                            <?php foreach ($result['errors'] as $error): ?>
                                <li><?php echo $error; ?></li>
                            <?php endforeach; ?>
                        </ul>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
        <?php endif; ?>
        
        <div class="card">
            <div class="card-header bg-dark text-white">
                Upload CSV File
            </div>
            <div class="card-body">
                <form method="post" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label for="csv_file" class="form-label">Chọn file CSV:</label>
                        <input type="file" class="form-control" id="csv_file" name="csv_file" accept=".csv" required>
                        <div class="form-text">
                            File CSV phải có định dạng: name, description, price, sale_price, category, image_url, stock
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <button type="submit" name="import_csv" class="btn btn-primary">Import Products</button>
                    </div>
                </form>
            </div>
        </div>
        
        <div class="card mt-4">
            <div class="card-header bg-dark text-white">
                Mẫu File CSV
            </div>
            <div class="card-body">
                <p>Tải xuống mẫu file CSV để import sản phẩm giày Nike:</p>
                <a href="sample_nike_products.csv" download class="btn btn-outline-dark">Tải xuống mẫu file CSV</a>
                
                <div class="mt-3">
                    <h6>Cấu trúc file CSV:</h6>
                    <pre class="bg-light p-3">
name,description,price,sale_price,category,image_url,stock
"Nike Air Max 270","The Nike Air Max 270 delivers incredible comfort.",150,129.99,Running,https://static.nike.com/a/images/c_limit,w_592,f_auto/t_product_v1/i1-fake-path-270.jpg,25
"Nike Air Force 1","The radiance lives on in the Nike Air Force 1.",100,89.99,Casual,https://static.nike.com/a/images/c_limit,w_592,f_auto/t_product_v1/i1-fake-path-af1.jpg,40
"Nike Revolution 6","Responsive cushioning for your run.",70,59.99,Running,,30
"Nike Blazer Mid","Vintage look, modern comfort.",100,89.99,Casual,,20</pre>
                </div>
            </div>
        </div>
        
        <div class="mt-4">
            <a href="index.php" class="btn btn-secondary">Quay lại Trang Quản trị</a>
        </div>
    </div>
</body>
</html>