<?php
/**
 * Database Setup Helper - Nike Shoe Store
 * Use this to set up the database if not already done
 */

// Start session safely
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

echo "<h1>🗄️ Database Setup Helper</h1>";
echo "<hr>";

// Database connection parameters
$host = "localhost";
$username = "root";
$password = "";
$database = "shoe_store";

echo "<h2>📊 Database Configuration</h2>";
echo "<p><strong>Host:</strong> {$host}</p>";
echo "<p><strong>Username:</strong> {$username}</p>";
echo "<p><strong>Password:</strong> " . (empty($password) ? "(empty)" : "***") . "</p>";
echo "<p><strong>Database:</strong> {$database}</p>";

echo "<h2>🔍 Testing Database Connection</h2>";

try {
    // Test MySQL connection first
    $pdo = new PDO("mysql:host={$host}", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "<p>✅ MySQL connection successful!</p>";
    
    // Check if database exists
    $stmt = $pdo->query("SHOW DATABASES LIKE '{$database}'");
    $dbExists = $stmt->fetch();
    
    if ($dbExists) {
        echo "<p>✅ Database '{$database}' exists!</p>";
        
        // Connect to the specific database
        $pdo = new PDO("mysql:host={$host};dbname={$database}", $username, $password);
        
        // Check tables
        $stmt = $pdo->query("SHOW TABLES");
        $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
        
        if (count($tables) > 0) {
            echo "<p>✅ Database has " . count($tables) . " tables:</p>";
            echo "<ul>";
            foreach ($tables as $table) {
                echo "<li>{$table}</li>";
            }
            echo "</ul>";
            
            // Check if there's data
            foreach (['products', 'users'] as $table) {
                if (in_array($table, $tables)) {
                    $stmt = $pdo->query("SELECT COUNT(*) FROM {$table}");
                    $count = $stmt->fetchColumn();
                    echo "<p>📊 {$table}: {$count} records</p>";
                }
            }
            
        } else {
            echo "<p>⚠️ Database exists but no tables found!</p>";
            echo "<p>💡 You need to import the database.sql file</p>";
        }
        
    } else {
        echo "<p>❌ Database '{$database}' does not exist!</p>";
        echo "<p>💡 You need to create the database and import database.sql</p>";
    }
    
} catch (PDOException $e) {
    echo "<p>❌ Database connection failed: " . $e->getMessage() . "</p>";
    echo "<p>💡 Make sure XAMPP MySQL is running</p>";
}

echo "<hr>";
echo "<h2>📋 Setup Instructions</h2>";
echo "<ol>";
echo "<li><strong>Start XAMPP:</strong> Make sure Apache and MySQL are running</li>";
echo "<li><strong>Open phpMyAdmin:</strong> Go to <a href='http://localhost/phpmyadmin' target='_blank'>http://localhost/phpmyadmin</a></li>";
echo "<li><strong>Import Database:</strong> Import the database.sql file from this directory</li>";
echo "<li><strong>OR use SQL tab:</strong> Copy and paste the contents of database.sql</li>";
echo "</ol>";

echo "<h2>🚀 After Database Setup</h2>";
echo "<p><a href='simple_test.php' style='padding: 10px 15px; background: #28a745; color: white; text-decoration: none; border-radius: 4px;'>🧪 Test Website</a></p>";
echo "<p><a href='index.php' style='padding: 10px 15px; background: #007bff; color: white; text-decoration: none; border-radius: 4px;'>🏠 Go to Website</a></p>";

?>

<style>
body { font-family: Arial, sans-serif; margin: 40px; }
h1, h2 { color: #333; }
p { margin: 8px 0; }
ul, ol { margin: 10px 0; padding-left: 30px; }
a { color: #007bff; }
</style>
