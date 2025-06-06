<?php
/**
 * Quick Database Test
 */

try {
    $pdo = new PDO("mysql:host=localhost;charset=utf8", "root", "", [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    ]);
    
    echo "✅ MySQL Connection: OK<br>";
    
    // Check if database exists
    $stmt = $pdo->query("SHOW DATABASES LIKE 'shoe_store'");
    if ($stmt->rowCount() > 0) {
        echo "✅ Database 'shoe_store': EXISTS<br>";
        
        // Connect to the database
        $pdo->exec("USE shoe_store");
        
        // Check if products table exists
        $stmt = $pdo->query("SHOW TABLES LIKE 'products'");
        if ($stmt->rowCount() > 0) {
            echo "✅ Table 'products': EXISTS<br>";
            
            // Check if there are products
            $stmt = $pdo->query("SELECT COUNT(*) as count FROM products");
            $result = $stmt->fetch();
            echo "📊 Products count: " . $result['count'] . "<br>";
            
            if ($result['count'] > 0) {
                echo "✅ Database has product data<br>";
            } else {
                echo "⚠️ Database is empty - need to import sample data<br>";
            }
        } else {
            echo "❌ Table 'products': NOT FOUND<br>";
            echo "⚠️ Need to create database schema<br>";
        }
    } else {
        echo "❌ Database 'shoe_store': NOT FOUND<br>";
        echo "⚠️ Need to create database<br>";
    }
    
} catch (PDOException $e) {
    echo "❌ Database Error: " . $e->getMessage();
}
?>
