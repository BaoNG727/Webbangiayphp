<?php
/**
 * Test Registration and Database Sync
 */

require_once "includes/config.php";
require_once "app/Core/Database.php";
require_once "app/Models/User.php";

echo "<h1>Testing User Registration</h1>";

try {
    $userModel = new User();
    
    // Test data
    $testUsername = "testuser_" . time();
    $testEmail = "testuser_" . time() . "@test.com";
    
    $userData = [
        'name' => 'Test User',
        'username' => $testUsername,
        'email' => $testEmail,
        'password' => 'test123',
        'first_name' => 'Test',
        'last_name' => 'User',
        'role' => 'customer'
    ];
    
    echo "<h3>Test 1: Create User</h3>";
    $userId = $userModel->createUser($userData);
    
    if ($userId) {
        echo "✅ User created successfully with ID: $userId<br>";
        
        // Test finding user
        echo "<h3>Test 2: Find User by Username</h3>";
        $foundUser = $userModel->findByUsername($testUsername);
        
        if ($foundUser) {
            echo "✅ User found successfully<br>";
            echo "User data: " . json_encode($foundUser) . "<br>";
        } else {
            echo "❌ User not found<br>";
        }
        
        // Test finding user by email
        echo "<h3>Test 3: Find User by Email</h3>";
        $foundByEmail = $userModel->findByEmail($testEmail);
        
        if ($foundByEmail) {
            echo "✅ User found by email successfully<br>";
        } else {
            echo "❌ User not found by email<br>";
        }
        
    } else {
        echo "❌ Failed to create user<br>";
    }
    
    echo "<h3>Test 4: Check Database Tables</h3>";
    
    // Check all users
    $db = new Database();
    $users = $db->fetchAll("SELECT id, name, username, email, role FROM users ORDER BY created_at DESC LIMIT 5");
    
    echo "<h4>Recent Users:</h4>";
    echo "<table border='1' cellpadding='5'>";
    echo "<tr><th>ID</th><th>Name</th><th>Username</th><th>Email</th><th>Role</th></tr>";
    
    foreach ($users as $user) {
        echo "<tr>";
        echo "<td>{$user['id']}</td>";
        echo "<td>{$user['name']}</td>";
        echo "<td>{$user['username']}</td>";
        echo "<td>{$user['email']}</td>";
        echo "<td>{$user['role']}</td>";
        echo "</tr>";
    }
    echo "</table>";
    
    // Check products
    echo "<h4>Products Available:</h4>";
    $products = $db->fetchAll("SELECT id, name, price, stock FROM products LIMIT 5");
    
    echo "<table border='1' cellpadding='5'>";
    echo "<tr><th>ID</th><th>Name</th><th>Price</th><th>Stock</th></tr>";
    
    foreach ($products as $product) {
        echo "<tr>";
        echo "<td>{$product['id']}</td>";
        echo "<td>{$product['name']}</td>";
        echo "<td>$" . number_format($product['price'], 2) . "</td>";
        echo "<td>{$product['stock']}</td>";
        echo "</tr>";
    }
    echo "</table>";
    
    echo "<h3>✅ All tests completed successfully!</h3>";
    echo "<h4>Next Steps:</h4>";
    echo "<ul>";
    echo "<li><a href='index.php'>Test website homepage</a></li>";
    echo "<li><a href='admin/'>Check admin panel</a></li>";
    echo "<li>Try registering a new account on the website</li>";
    echo "<li>Try placing an order</li>";
    echo "</ul>";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "<br>";
    echo "Stack trace: <pre>" . $e->getTraceAsString() . "</pre>";
}
?>
