<?php
/**
 * Main Entry Point - Nike Shoe Store
 * Production-ready version with reliable routing
 */

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Debug: Check if database exists
try {
    $pdo = new PDO("mysql:host=localhost;charset=utf8", "root", "");
    $result = $pdo->query("SHOW DATABASES LIKE 'nike_store'");
    if ($result->rowCount() == 0) {
        // Database doesn't exist, create it
        $pdo->exec("CREATE DATABASE IF NOT EXISTS `nike_store` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
        
        // Import the database structure by executing each statement separately
        $pdo->exec("USE nike_store");
        $sql = file_get_contents(__DIR__ . '/database.sql');
        if ($sql) {
            // Split SQL file into individual statements
            $statements = array_filter(array_map('trim', explode(';', $sql)));
            foreach ($statements as $statement) {
                if (!empty($statement) && !preg_match('/^--/', $statement)) {
                    try {
                        $pdo->exec($statement);
                    } catch (Exception $e) {
                        // Log but continue with other statements
                        error_log("SQL Statement error: " . $e->getMessage() . " for statement: " . substr($statement, 0, 100));
                    }
                }
            }
        }
    }
} catch (Exception $e) {
    die("Database setup error: " . $e->getMessage());
}

// Include the hybrid router
require_once __DIR__ . '/router.php';
?>
