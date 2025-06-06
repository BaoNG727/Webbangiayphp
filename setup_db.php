<?php
/**
 * Database Setup Script
 * Creates database and imports schema with sample data
 */

echo "<h2>Nike Shoe Store - Database Setup</h2>";

try {
    // Connect to MySQL without selecting a database
    $pdo = new PDO("mysql:host=localhost;charset=utf8", "root", "", [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    ]);
    
    echo "✅ MySQL Connection: OK<br>";
    
    // Read and execute the database.sql file
    $sql = file_get_contents(__DIR__ . '/database.sql');
    
    if ($sql === false) {
        throw new Exception("Could not read database.sql file");
    }
    
    // Split SQL into individual statements
    $statements = array_filter(array_map('trim', explode(';', $sql)));
    
    foreach ($statements as $statement) {
        if (!empty($statement)) {
            try {
                $pdo->exec($statement);
            } catch (PDOException $e) {
                // Skip errors for statements that might already exist
                if (strpos($e->getMessage(), 'already exists') === false && 
                    strpos($e->getMessage(), 'Duplicate entry') === false) {
                    echo "⚠️ SQL Warning: " . $e->getMessage() . "<br>";
                }
            }
        }
    }
    
    echo "✅ Database schema imported successfully<br>";
    
    // Test the setup
    $pdo->exec("USE shoe_store");
    
    // Check tables
    $tables = ['products', 'users', 'orders', 'order_items', 'cart'];
    foreach ($tables as $table) {
        $stmt = $pdo->query("SELECT COUNT(*) as count FROM $table");
        $result = $stmt->fetch();
        echo "📊 Table '$table': {$result['count']} records<br>";
    }
    
    echo "<br>✅ <strong>Database setup complete!</strong><br>";
    echo "<a href='index.php'>🏠 Go to Homepage</a> | ";
    echo "<a href='diagnostic.php'>🔧 Run Diagnostics</a>";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage();
}
?>
