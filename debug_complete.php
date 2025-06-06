<?php
/**
 * Complete System Debug - Nike Shoe Store
 */
?>
<!DOCTYPE html>
<html>
<head>
    <title>System Debug - Nike Shoe Store</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .section { margin: 20px 0; padding: 15px; border: 1px solid #ddd; border-radius: 5px; }
        .success { color: green; }
        .error { color: red; }
        .warning { color: orange; }
        .info { color: blue; }
        pre { background: #f5f5f5; padding: 10px; border-radius: 3px; overflow-x: auto; }
    </style>
</head>
<body>
    <h1>Nike Shoe Store - System Debug</h1>
    
    <div class="section">
        <h2>1. PHP Configuration</h2>
        <?php
        echo "PHP Version: " . phpversion() . "<br>";
        echo "Session Status: " . session_status() . " (1=disabled, 2=active, 3=none)<br>";
        echo "Error Reporting: " . error_reporting() . "<br>";
        ?>
    </div>

    <div class="section">
        <h2>2. Request Information</h2>
        <?php
        echo "REQUEST_URI: " . ($_SERVER['REQUEST_URI'] ?? 'NOT SET') . "<br>";
        echo "REQUEST_METHOD: " . ($_SERVER['REQUEST_METHOD'] ?? 'NOT SET') . "<br>";
        echo "SCRIPT_NAME: " . ($_SERVER['SCRIPT_NAME'] ?? 'NOT SET') . "<br>";
        echo "PATH_INFO: " . ($_SERVER['PATH_INFO'] ?? 'NOT SET') . "<br>";
        
        $request = $_SERVER['REQUEST_URI'] ?? '';
        $path = parse_url($request, PHP_URL_PATH);
        echo "Parsed Path: " . $path . "<br>";
        
        $basePath = '/Webgiay';
        if (strpos($path, $basePath) === 0) {
            $path = substr($path, strlen($basePath));
        }
        
        if (empty($path) || $path === '') {
            $path = '/';
        } elseif ($path[0] !== '/') {
            $path = '/' . $path;
        }
        
        echo "Final Route Path: <strong>" . $path . "</strong><br>";
        ?>
    </div>

    <div class="section">
        <h2>3. Database Connection</h2>
        <?php
        try {
            $pdo = new PDO("mysql:host=localhost;dbname=shoe_store;charset=utf8", "root", "", [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            ]);
            echo "<span class='success'>✅ Database Connection: OK</span><br>";
            
            $stmt = $pdo->query("SELECT COUNT(*) as count FROM products");
            $result = $stmt->fetch();
            echo "Products in database: " . $result['count'] . "<br>";
        } catch (PDOException $e) {
            echo "<span class='error'>❌ Database Error: " . $e->getMessage() . "</span><br>";
        }
        ?>
    </div>

    <div class="section">
        <h2>4. File Structure Check</h2>
        <?php
        $files = [
            'index.php' => __DIR__ . '/index.php',
            'index-mvc.php' => __DIR__ . '/index-mvc.php',
            'Router.php' => __DIR__ . '/app/Core/Router.php',
            'HomeController.php' => __DIR__ . '/app/Controllers/HomeController.php',
            'Product Model' => __DIR__ . '/app/Models/Product.php',
            'Header View' => __DIR__ . '/app/Views/layouts/header.php',
            'Home View' => __DIR__ . '/app/Views/home/index.php',
        ];
        
        foreach ($files as $name => $path) {
            if (file_exists($path)) {
                echo "<span class='success'>✅ $name</span><br>";
            } else {
                echo "<span class='error'>❌ $name (Missing: $path)</span><br>";
            }
        }
        ?>
    </div>

    <div class="section">
        <h2>5. Autoloader Test</h2>
        <?php
        // Test autoloader
        $classes = ['Router', 'HomeController', 'Product'];
        foreach ($classes as $class) {
            if (class_exists($class)) {
                echo "<span class='success'>✅ $class loaded</span><br>";
            } else {
                echo "<span class='error'>❌ $class not found</span><br>";
            }
        }
        ?>
    </div>

    <div class="section">
        <h2>6. Direct Route Test</h2>
        <?php
        // Test routing directly
        try {
            require_once __DIR__ . '/app/Core/Router.php';
            require_once __DIR__ . '/app/Controllers/HomeController.php';
            require_once __DIR__ . '/app/Core/Controller.php';
            require_once __DIR__ . '/app/Core/Database.php';
            require_once __DIR__ . '/app/Core/Model.php';
            require_once __DIR__ . '/app/Models/Product.php';
            
            echo "<span class='success'>✅ All core classes loaded successfully</span><br>";
            
            $router = new Router();
            $router->get('/', 'HomeController', 'index');
            
            echo "<span class='info'>📝 Router configured for home route</span><br>";
            echo "<span class='info'>🔍 Testing route dispatch for '/'...</span><br>";
            
            // Capture output
            ob_start();
            $router->dispatch('/');
            $output = ob_get_clean();
            
            if (!empty($output)) {
                echo "<span class='success'>✅ Route dispatch generated content</span><br>";
                echo "<details><summary>View Output (first 500 chars)</summary><pre>" . 
                     htmlspecialchars(substr($output, 0, 500)) . "...</pre></details>";
            } else {
                echo "<span class='warning'>⚠️ Route dispatch generated no output</span><br>";
            }
            
        } catch (Exception $e) {
            echo "<span class='error'>❌ Route Test Error: " . $e->getMessage() . "</span><br>";
        }
        ?>
    </div>

    <div class="section">
        <h2>7. Actions</h2>
        <a href="index.php">🏠 Test Homepage</a> | 
        <a href="setup_db.php">🔧 Setup Database</a> | 
        <a href="diagnostic.php">📊 Full Diagnostics</a>
    </div>
</body>
</html>
