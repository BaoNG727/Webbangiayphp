<!DOCTYPE html>
<html>
<head>
    <title>DEBUG CONTACT PAGE</title>
    <style>
        body { 
            font-family: Arial; 
            background: red; 
            color: white; 
            padding: 20px; 
            font-size: 24px;
        }
        .big-text { 
            font-size: 48px; 
            font-weight: bold; 
            text-align: center; 
        }
    </style>
</head>
<body>
    <div class="big-text">CONTACT PAGE HIỂN THỊ THÀNH CÔNG!</div>
    <p>Nếu bạn thấy được text này thì website đang hoạt động bình thường.</p>
    <p>Thời gian: <?php echo date('Y-m-d H:i:s'); ?></p>
    <p>PHP Version: <?php echo phpversion(); ?></p>
    <p>Server: <?php echo $_SERVER['SERVER_SOFTWARE'] ?? 'Unknown'; ?></p>
</body>
</html>
