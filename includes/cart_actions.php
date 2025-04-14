<?php
session_start();
require_once "config.php";

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Please login to add items to cart']);
    exit;
}

$user_id = $_SESSION['user_id'];
$response = ['success' => false];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = isset($_POST['action']) ? $_POST['action'] : '';
    
    // Add item to cart
    if ($action === 'add') {
        $product_id = isset($_POST['product_id']) ? intval($_POST['product_id']) : 0;
        $quantity = isset($_POST['quantity']) ? intval($_POST['quantity']) : 1;
        
        if ($product_id <= 0 || $quantity <= 0) {
            $response['message'] = 'Invalid product or quantity';
            echo json_encode($response);
            exit;
        }
        
        // Check if product exists and has enough stock
        $product_query = "SELECT * FROM products WHERE id = $product_id AND stock >= $quantity";
        $product_result = mysqli_query($conn, $product_query);
        
        if (mysqli_num_rows($product_result) === 0) {
            $response['message'] = 'Product not available or insufficient stock';
            echo json_encode($response);
            exit;
        }
        
        // Check if product is already in cart
        $check_query = "SELECT * FROM cart WHERE user_id = $user_id AND product_id = $product_id";
        $check_result = mysqli_query($conn, $check_query);
        
        if (mysqli_num_rows($check_result) > 0) {
            // Update quantity if product already in cart
            $cart_item = mysqli_fetch_assoc($check_result);
            $new_quantity = $cart_item['quantity'] + $quantity;
            
            // Check if new quantity exceeds stock
            $stock_query = "SELECT stock FROM products WHERE id = $product_id";
            $stock_result = mysqli_query($conn, $stock_query);
            $stock_data = mysqli_fetch_assoc($stock_result);
            
            if ($new_quantity > $stock_data['stock']) {
                $response['message'] = 'Cannot add more items than available in stock';
                echo json_encode($response);
                exit;
            }
            
            $update_query = "UPDATE cart SET quantity = $new_quantity WHERE user_id = $user_id AND product_id = $product_id";
            $update_result = mysqli_query($conn, $update_query);
            
            if ($update_result) {
                $response['success'] = true;
                $response['message'] = 'Cart updated successfully';
            } else {
                $response['message'] = 'Failed to update cart';
            }
        } else {
            // Add new product to cart
            $insert_query = "INSERT INTO cart (user_id, product_id, quantity) VALUES ($user_id, $product_id, $quantity)";
            $insert_result = mysqli_query($conn, $insert_query);
            
            if ($insert_result) {
                $response['success'] = true;
                $response['message'] = 'Product added to cart';
            } else {
                $response['message'] = 'Failed to add product to cart';
            }
        }
        
        // Get updated cart count
        $count_query = "SELECT SUM(quantity) as total FROM cart WHERE user_id = $user_id";
        $count_result = mysqli_query($conn, $count_query);
        $count_data = mysqli_fetch_assoc($count_result);
        $response['cart_count'] = $count_data['total'] ? intval($count_data['total']) : 0;
    }
    
    // Update cart item quantity
    elseif ($action === 'update') {
        $product_id = isset($_POST['product_id']) ? intval($_POST['product_id']) : 0;
        $quantity = isset($_POST['quantity']) ? intval($_POST['quantity']) : 1;
        
        if ($product_id <= 0 || $quantity <= 0) {
            $response['message'] = 'Invalid product or quantity';
            echo json_encode($response);
            exit;
        }
        
        // Check if product has enough stock
        $stock_query = "SELECT * FROM products WHERE id = $product_id AND stock >= $quantity";
        $stock_result = mysqli_query($conn, $stock_query);
        
        if (mysqli_num_rows($stock_result) === 0) {
            $response['message'] = 'Insufficient stock';
            echo json_encode($response);
            exit;
        }
        
        $product = mysqli_fetch_assoc($stock_result);
        
        // Update cart item quantity
        $update_query = "UPDATE cart SET quantity = $quantity WHERE user_id = $user_id AND product_id = $product_id";
        $update_result = mysqli_query($conn, $update_query);
        
        if ($update_result) {
            $response['success'] = true;
            $response['message'] = 'Cart updated successfully';
            
            // Calculate subtotal for this item
            $subtotal = ($product['sale_price'] > 0) ? $product['sale_price'] * $quantity : $product['price'] * $quantity;
            $response['subtotal'] = number_format($subtotal, 2);
            
            // Calculate cart total
            $total_query = "SELECT c.quantity, p.price, p.sale_price 
                            FROM cart c 
                            JOIN products p ON c.product_id = p.id 
                            WHERE c.user_id = $user_id";
            $total_result = mysqli_query($conn, $total_query);
            
            $cart_total = 0;
            while ($item = mysqli_fetch_assoc($total_result)) {
                $item_price = ($item['sale_price'] > 0) ? $item['sale_price'] : $item['price'];
                $cart_total += $item_price * $item['quantity'];
            }
            
            $response['cart_total'] = number_format($cart_total, 2);
        } else {
            $response['message'] = 'Failed to update cart';
        }
    }
    
    // Remove item from cart
    elseif ($action === 'remove') {
        $product_id = isset($_POST['product_id']) ? intval($_POST['product_id']) : 0;
        
        if ($product_id <= 0) {
            $response['message'] = 'Invalid product';
            echo json_encode($response);
            exit;
        }
        
        $delete_query = "DELETE FROM cart WHERE user_id = $user_id AND product_id = $product_id";
        $delete_result = mysqli_query($conn, $delete_query);
        
        if ($delete_result) {
            $response['success'] = true;
            $response['message'] = 'Product removed from cart';
            
            // Calculate cart total after removal
            $total_query = "SELECT c.quantity, p.price, p.sale_price 
                            FROM cart c 
                            JOIN products p ON c.product_id = p.id 
                            WHERE c.user_id = $user_id";
            $total_result = mysqli_query($conn, $total_query);
            
            $cart_total = 0;
            while ($item = mysqli_fetch_assoc($total_result)) {
                $item_price = ($item['sale_price'] > 0) ? $item['sale_price'] : $item['price'];
                $cart_total += $item_price * $item['quantity'];
            }
            
            $response['cart_total'] = number_format($cart_total, 2);
            
            // Get updated cart count
            $count_query = "SELECT SUM(quantity) as total FROM cart WHERE user_id = $user_id";
            $count_result = mysqli_query($conn, $count_query);
            $count_data = mysqli_fetch_assoc($count_result);
            $response['cart_count'] = $count_data['total'] ? intval($count_data['total']) : 0;
        } else {
            $response['message'] = 'Failed to remove product from cart';
        }
    } else {
        $response['message'] = 'Invalid action';
    }
}

echo json_encode($response);
?>
