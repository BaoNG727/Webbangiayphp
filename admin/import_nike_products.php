<?php
require_once '../app/Core/Database.php';

// Tạo kết nối database
$database = new Database();
$conn = $database->getConnection();

function importNikeProducts($csvFile, $conn) {
    $imported = 0;
    $errors = [];
    
    if (($handle = fopen($csvFile, 'r')) !== false) {
        // Đọc header
        $header = fgetcsv($handle, 0, ',');
        
        while (($data = fgetcsv($handle, 0, ',')) !== false) {
            if (count($data) < 6) continue; // Bỏ qua dòng không đủ dữ liệu
            
            try {
                // Mapping dữ liệu từ CSV
                $productName = trim($data[0]); // product_name
                $productId = trim($data[1]); // product_id
                $listingPrice = floatval($data[2]); // listing_price
                $salePrice = floatval($data[3]); // sale_price
                $discount = floatval($data[4]); // discount
                $brand = trim($data[5]); // brand
                $description = isset($data[6]) ? trim($data[6]) : ''; // description
                $rating = isset($data[7]) ? floatval($data[7]) : 0; // rating
                $reviews = isset($data[8]) ? intval($data[8]) : 0; // reviews
                $images = isset($data[9]) ? trim($data[9]) : ''; // images
                
                // Xử lý giá - nếu sale_price = 0 thì lấy listing_price
                $finalPrice = ($salePrice > 0) ? $salePrice / 100 : $listingPrice / 100; // Chia 100 vì giá trong CSV có vẻ được nhân 100
                if ($finalPrice == 0) {
                    $finalPrice = 100; // Giá mặc định
                }
                
                $finalSalePrice = null;
                if ($salePrice > 0 && $listingPrice > $salePrice) {
                    $finalSalePrice = $salePrice / 100;
                }
                
                // Xử lý hình ảnh - lấy URL đầu tiên từ JSON array
                $imageName = '';
                if (!empty($images)) {
                    // Loại bỏ ký tự đặc biệt và parse JSON
                    $images = str_replace(['[', ']', '""'], ['[', ']', '"'], $images);
                    $imageArray = json_decode($images, true);
                    if (is_array($imageArray) && !empty($imageArray)) {
                        $imageUrl = $imageArray[0];
                        // Lấy tên file từ URL
                        $imageName = 'nike_' . $productId . '.jpg';
                    }
                }
                
                // Xác định category dựa trên tên sản phẩm
                $category = 'Casual'; // Mặc định
                if (stripos($productName, 'Air Max') !== false || stripos($productName, 'Running') !== false || stripos($productName, 'Zoom') !== false || stripos($productName, 'React') !== false || stripos($productName, 'Pegasus') !== false) {
                    $category = 'Running';
                } elseif (stripos($productName, 'Jordan') !== false || stripos($productName, 'Basketball') !== false) {
                    $category = 'Basketball';
                } elseif (stripos($productName, 'Metcon') !== false || stripos($productName, 'Training') !== false || stripos($productName, 'SuperRep') !== false) {
                    $category = 'Training';
                } elseif (stripos($productName, 'Court') !== false || stripos($productName, 'Tennis') !== false) {
                    $category = 'Tennis';
                }
                
                // Tạo stock ngẫu nhiên từ 5-50
                $stock = rand(5, 50);
                
                // Kiểm tra xem sản phẩm đã tồn tại chưa (dựa trên tên)
                $checkSql = "SELECT id FROM products WHERE name = ?";
                $checkStmt = $conn->prepare($checkSql);
                $checkStmt->execute([$productName]);
                
                if ($checkStmt->rowCount() > 0) {
                    // Sản phẩm đã tồn tại, bỏ qua
                    continue;
                }
                
                // Thêm sản phẩm mới
                $sql = "INSERT INTO products (name, description, price, sale_price, category, image, stock, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, NOW())";
                $stmt = $conn->prepare($sql);
                
                $result = $stmt->execute([$productName, $description, $finalPrice, $finalSalePrice, $category, $imageName, $stock]);
                
                if ($result) {
                    $imported++;
                    echo "✓ Đã thêm: {$productName} - Giá: {$finalPrice}$ - Danh mục: {$category}\n";
                } else {
                    $errors[] = "Error inserting {$productName}";
                }
                
            } catch (Exception $e) {
                $errors[] = "Error processing product {$productName}: " . $e->getMessage();
            }
        }
        fclose($handle);
    }
    
    return [
        'imported' => $imported,
        'errors' => $errors
    ];
}

// Thực hiện import
echo "Bắt đầu import sản phẩm Nike từ CSV...\n\n";

$csvFile = '../nike_shoes_sales.csv';
if (!file_exists($csvFile)) {
    die("File CSV không tồn tại: {$csvFile}\n");
}

$result = importNikeProducts($csvFile, $conn);

echo "\n=== KẾT QUẢ IMPORT ===\n";
echo "Số sản phẩm đã import: " . $result['imported'] . "\n";

if (!empty($result['errors'])) {
    echo "\nLỗi:\n";
    foreach ($result['errors'] as $error) {
        echo "- " . $error . "\n";
    }
}

echo "\nHoàn tất import!\n";

// PDO không cần close() method - connection sẽ tự động đóng khi script kết thúc
$conn = null;
?>
