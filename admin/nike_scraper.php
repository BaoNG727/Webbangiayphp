<?php
// Yêu cầu file config để kết nối database
require_once '../includes/config.php';

// Kiểm tra xem người dùng hiện tại có phải là admin hay không
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit;
}

/**
 * Script để thu thập dữ liệu sản phẩm giày Nike
 * Lưu ý: Web scraping có thể vi phạm điều khoản sử dụng của một số website
 * Nên sử dụng API chính thức khi có thể
 */

// Khai báo các URL nguồn để thu thập dữ liệu
$source_urls = [
    'running' => 'https://www.nike.com/w/mens-running-shoes-37v7jznik1zy7ok',
    'basketball' => 'https://www.nike.com/w/mens-basketball-shoes-3glsmznik1zy7ok',
    'casual' => 'https://www.nike.com/w/mens-lifestyle-shoes-13jrmznik1zy7ok',
    'training' => 'https://www.nike.com/w/mens-training-gym-shoes-58jtoznik1zy7ok'
];

// Khởi tạo curl để lấy dữ liệu từ web
function fetchData($url) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36');
    $data = curl_exec($ch);
    
    if (curl_errno($ch)) {
        echo 'Curl error: ' . curl_error($ch);
    }
    
    curl_close($ch);
    return $data;
}

// Tạo function để lưu hình ảnh từ URL
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
    $imageContent = fetchData($imageUrl);
    file_put_contents($savePath, $imageContent);
    
    return $imageName;
}

// Function để xử lý dữ liệu sản phẩm từ HTML
function extractProducts($html, $category) {
    $products = [];
    
    // Pattern để tìm sản phẩm (điều này sẽ thay đổi dựa trên cấu trúc HTML của Nike)
    $pattern = '/<div class="product-card">(.*?)<\/div>/s';
    preg_match_all($pattern, $html, $matches);
    
    foreach ($matches[1] as $productHtml) {
        $product = [];
        
        // Extract product name
        preg_match('/<div class="product-name">(.*?)<\/div>/s', $productHtml, $nameMatch);
        $product['name'] = isset($nameMatch[1]) ? trim($nameMatch[1]) : 'Nike Shoe';
        
        // Extract product price
        preg_match('/<div class="product-price">\$([0-9.]+)<\/div>/s', $productHtml, $priceMatch);
        $product['price'] = isset($priceMatch[1]) ? floatval($priceMatch[1]) : 99.99;
        
        // Extract sale price if exists
        preg_match('/<div class="product-sale-price">\$([0-9.]+)<\/div>/s', $productHtml, $salePriceMatch);
        $product['sale_price'] = isset($salePriceMatch[1]) ? floatval($salePriceMatch[1]) : null;
        
        // Extract product image
        preg_match('/<img src="(.*?)"/s', $productHtml, $imageMatch);
        $product['image_url'] = isset($imageMatch[1]) ? $imageMatch[1] : '';
        
        // Category
        $product['category'] = ucfirst($category);
        
        // Description
        $product['description'] = 'Authentic Nike ' . ucfirst($category) . ' shoes. ' . $product['name'] . ' offers premium comfort and style for your active lifestyle.';
        
        // Stock
        $product['stock'] = rand(10, 50);
        
        $products[] = $product;
    }
    
    return $products;
}

// Function để lưu sản phẩm vào database
function saveProduct($product) {
    global $conn;
    
    // Lưu hình ảnh nếu có URL
    $image = '';
    if (!empty($product['image_url'])) {
        $image = saveImage($product['image_url'], $product['category']);
    }
    
    // Chuẩn bị câu lệnh SQL
    $sql = "INSERT INTO products (name, description, price, sale_price, category, image, stock) 
            VALUES (?, ?, ?, ?, ?, ?, ?)";
    
    // Chuẩn bị và thực thi statement
    $stmt = $conn->prepare($sql);
    $stmt->bind_param(
        'ssddsis',
        $product['name'],
        $product['description'],
        $product['price'],
        $product['sale_price'],
        $product['category'],
        $image,
        $product['stock']
    );
    
    if ($stmt->execute()) {
        return true;
    } else {
        echo "Error: " . $stmt->error;
        return false;
    }
}

// Function chính để thu thập dữ liệu từ tất cả nguồn
function scrapeNikeProducts() {
    global $source_urls;
    $totalProducts = 0;
    $successProducts = 0;
    
    foreach ($source_urls as $category => $url) {
        echo "<h3>Fetching {$category} shoes...</h3>";
        
        $html = fetchData($url);
        $products = extractProducts($html, $category);
        
        echo "<p>Found " . count($products) . " products in {$category} category.</p>";
        $totalProducts += count($products);
        
        foreach ($products as $product) {
            if (saveProduct($product)) {
                $successProducts++;
                echo "<div style='color:green'>Successfully added: {$product['name']} - \${$product['price']}</div>";
            } else {
                echo "<div style='color:red'>Failed to add: {$product['name']}</div>";
            }
        }
        
        // Thêm một khoảng thời gian giữa các request để tránh bị chặn
        sleep(2);
    }
    
    echo "<h2>Scraping complete!</h2>";
    echo "<p>Total products found: {$totalProducts}</p>";
    echo "<p>Successfully added: {$successProducts}</p>";
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Nike Product Scraper</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/Webgiay/assets/css/admin.css">
</head>
<body>
    <div class="container mt-4">
        <h1>Nike Product Scraper</h1>
        <p class="text-danger">Lưu ý: Sử dụng công cụ này chỉ dành cho mục đích học tập và phát triển. Thu thập dữ liệu tự động có thể vi phạm điều khoản dịch vụ của Nike.</p>
        
        <?php
        // Kiểm tra nếu form đã được gửi
        if (isset($_POST['start_scraping'])) {
            // Thực hiện scraping
            scrapeNikeProducts();
        } else {
        ?>
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Bắt đầu thu thập dữ liệu</h5>
                <p>Nhấn nút bên dưới để bắt đầu thu thập dữ liệu giày Nike. Quá trình này có thể mất một vài phút.</p>
                <form method="post">
                    <button type="submit" name="start_scraping" class="btn btn-primary">Bắt đầu Thu thập</button>
                </form>
            </div>
        </div>
        <?php
        }
        ?>
        
        <div class="mt-4">
            <a href="index.php" class="btn btn-secondary">Quay lại Trang Quản trị</a>
        </div>
    </div>
</body>
</html>