<?php
session_start();
require_once '../includes/config.php';
require_once '../app/Models/DiscountCode.php';

// Set JSON response header
header('Content-Type: application/json');

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'User not authenticated']);
    exit;
}

// Only handle POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit;
}

// Get request data
$input = json_decode(file_get_contents('php://input'), true);

if (!$input || !isset($input['discount_code']) || !isset($input['order_amount'])) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Missing required parameters']);
    exit;
}

$discountCode = strtoupper(trim($input['discount_code']));
$orderAmount = floatval($input['order_amount']);
$userId = $_SESSION['user_id'];

try {
    $discountModel = new DiscountCode();
    $result = $discountModel->validateCode($discountCode, $userId, $orderAmount);
    
    if ($result['valid']) {
        echo json_encode([
            'success' => true,
            'message' => $result['message'],
            'discount_amount' => $result['discount_amount'],
            'discount_code' => $discountCode
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => $result['message']
        ]);
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Internal server error'
    ]);
}
?>
