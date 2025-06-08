<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test Contact Page</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body>
    <h1 class="text-4xl font-bold text-center mt-10">CONTACT PAGE TEST</h1>
    
    <div class="container mx-auto p-8">
        <div class="bg-red-500 text-white p-4 rounded mb-4">
            <h2 class="text-2xl">Đây là trang test contact</h2>
            <p>Nếu bạn thấy được text này thì website hoạt động bình thường</p>
        </div>
        
        <?php
        echo "<div class='bg-green-500 text-white p-4 rounded'>";
        echo "<h3>PHP đang hoạt động!</h3>";
        echo "<p>Thời gian hiện tại: " . date('Y-m-d H:i:s') . "</p>";
        echo "</div>";
        ?>
    </div>
</body>
</html>
