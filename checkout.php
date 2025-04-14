<?php
include_once "includes/header.php";

// Redirect if not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php?redirect=checkout.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Get user details
$user_query = "SELECT * FROM users WHERE id = $user_id";
$user_result = mysqli_query($conn, $user_query);
$user = mysqli_fetch_assoc($user_result);

// Get cart items
$cart_query = "SELECT c.*, p.name, p.price, p.sale_price, p.image
              FROM cart c
              JOIN products p ON c.product_id = p.id
              WHERE c.user_id = $user_id";
$cart_result = mysqli_query($conn, $cart_query);
$cart_count = mysqli_num_rows($cart_result);

// Redirect if cart is empty
if ($cart_count == 0) {
    header("Location: cart.php");
    exit;
}

// Calculate cart totals
$subtotal = 0;
$cart_items = [];

while ($item = mysqli_fetch_assoc($cart_result)) {
    $cart_items[] = $item;
    $price = ($item['sale_price'] > 0) ? $item['sale_price'] : $item['price'];
    $subtotal += $price * $item['quantity'];
}

// Shipping cost calculation
$shipping = ($subtotal >= 100) ? 0 : 10;

// Total cost
$total = $subtotal + $shipping;

// Process checkout form
$error = '';
$order_success = false;
$order_id = 0;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get form data
    $first_name = trim($_POST['first_name']);
    $last_name = trim($_POST['last_name']);
    $email = trim($_POST['email']);
    $address = trim($_POST['address']);
    $city = trim($_POST['city']);
    $state = trim($_POST['state']);
    $zip = trim($_POST['zip']);
    $phone = trim($_POST['phone']);
    $payment_method = $_POST['payment_method'];
    
    // Validate inputs
    if (empty($first_name) || empty($last_name) || empty($email) || empty($address) || empty($city) || empty($state) || empty($zip) || empty($phone) || empty($payment_method)) {
        $error = "All fields are required";
    } else {
        // Create shipping address
        $shipping_address = "$first_name $last_name\n$address\n$city, $state $zip\nPhone: $phone";
        
        // Create new order
        $shipping_address = mysqli_real_escape_string($conn, $shipping_address);
        $payment_method = mysqli_real_escape_string($conn, $payment_method);
        
        $order_query = "INSERT INTO orders (user_id, total_amount, status, shipping_address, payment_method) 
                        VALUES ($user_id, $total, 'pending', '$shipping_address', '$payment_method')";
        
        if (mysqli_query($conn, $order_query)) {
            $order_id = mysqli_insert_id($conn);
            
            // Add order items
            $success = true;
            foreach ($cart_items as $item) {
                $product_id = $item['product_id'];
                $quantity = $item['quantity'];
                $price = ($item['sale_price'] > 0) ? $item['sale_price'] : $item['price'];
                
                $item_query = "INSERT INTO order_items (order_id, product_id, quantity, price) 
                              VALUES ($order_id, $product_id, $quantity, $price)";
                
                if (!mysqli_query($conn, $item_query)) {
                    $success = false;
                    $error = "Error adding order items: " . mysqli_error($conn);
                    break;
                }
                
                // Update product stock
                $update_stock = "UPDATE products SET stock = stock - $quantity WHERE id = $product_id";
                mysqli_query($conn, $update_stock);
            }
            
            if ($success) {
                // Clear cart
                $clear_cart = "DELETE FROM cart WHERE user_id = $user_id";
                mysqli_query($conn, $clear_cart);
                
                // Update user information
                $update_user = "UPDATE users SET 
                               first_name = '$first_name', 
                               last_name = '$last_name', 
                               email = '$email', 
                               address = '$address, $city, $state $zip', 
                               phone = '$phone' 
                               WHERE id = $user_id";
                mysqli_query($conn, $update_user);
                
                $order_success = true;
            }
        } else {
            $error = "Error creating order: " . mysqli_error($conn);
        }
    }
}
?>

<div class="container py-4">
    <h1 class="mb-4">Checkout</h1>
    
    <?php if ($order_success): ?>
        <div class="alert alert-success">
            <h4>Thank you for your order!</h4>
            <p>Your order #<?php echo $order_id; ?> has been placed successfully.</p>
            <p>We've sent a confirmation email to your email address.</p>
            <p>You can view your order details in <a href="account.php">your account</a>.</p>
            <p><a href="index.php" class="btn btn-dark mt-3">Continue Shopping</a></p>
        </div>
    <?php else: ?>
        <?php if (!empty($error)): ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <div class="row">
            <!-- Checkout Form -->
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-body">
                        <form method="post" action="" class="needs-validation" novalidate>
                            <h5 class="mb-4">Shipping Information</h5>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="first_name" class="form-label">First Name</label>
                                    <input type="text" class="form-control" id="first_name" name="first_name" value="<?php echo $user['first_name'] ?? ''; ?>" required>
                                    <div class="invalid-feedback">Please enter your first name</div>
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label for="last_name" class="form-label">Last Name</label>
                                    <input type="text" class="form-control" id="last_name" name="last_name" value="<?php echo $user['last_name'] ?? ''; ?>" required>
                                    <div class="invalid-feedback">Please enter your last name</div>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" name="email" value="<?php echo $user['email'] ?? ''; ?>" required>
                                <div class="invalid-feedback">Please enter a valid email</div>
                            </div>
                            
                            <div class="mb-3">
                                <label for="address" class="form-label">Address</label>
                                <input type="text" class="form-control" id="address" name="address" required>
                                <div class="invalid-feedback">Please enter your address</div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-5 mb-3">
                                    <label for="city" class="form-label">City</label>
                                    <input type="text" class="form-control" id="city" name="city" required>
                                    <div class="invalid-feedback">Please enter your city</div>
                                </div>
                                
                                <div class="col-md-4 mb-3">
                                    <label for="state" class="form-label">State</label>
                                    <input type="text" class="form-control" id="state" name="state" required>
                                    <div class="invalid-feedback">Please enter your state</div>
                                </div>
                                
                                <div class="col-md-3 mb-3">
                                    <label for="zip" class="form-label">Zip</label>
                                    <input type="text" class="form-control" id="zip" name="zip" required>
                                    <div class="invalid-feedback">Please enter your zip code</div>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label for="phone" class="form-label">Phone</label>
                                <input type="tel" class="form-control" id="phone" name="phone" value="<?php echo $user['phone'] ?? ''; ?>" required>
                                <div class="invalid-feedback">Please enter your phone number</div>
                            </div>
                            
                            <hr class="my-4">
                            
                            <h5 class="mb-4">Payment Method</h5>
                            
                            <div class="form-check mb-3">
                                <input class="form-check-input" type="radio" name="payment_method" id="credit_card" value="credit_card" checked required>
                                <label class="form-check-label" for="credit_card">
                                    Credit Card
                                </label>
                            </div>
                            
                            <div class="form-check mb-3">
                                <input class="form-check-input" type="radio" name="payment_method" id="paypal" value="paypal" required>
                                <label class="form-check-label" for="paypal">
                                    PayPal
                                </label>
                            </div>
                            
                            <div class="form-check mb-4">
                                <input class="form-check-input" type="radio" name="payment_method" id="cash_on_delivery" value="cash_on_delivery" required>
                                <label class="form-check-label" for="cash_on_delivery">
                                    Cash on Delivery
                                </label>
                            </div>
                            
                            <div id="credit-card-details" class="mb-4">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="card_name" class="form-label">Name on Card</label>
                                        <input type="text" class="form-control" id="card_name">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="card_number" class="form-label">Card Number</label>
                                        <input type="text" class="form-control" id="card_number" placeholder="XXXX XXXX XXXX XXXX">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4 mb-3">
                                        <label for="expiration" class="form-label">Expiration</label>
                                        <input type="text" class="form-control" id="expiration" placeholder="MM/YY">
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label for="cvv" class="form-label">CVV</label>
                                        <input type="text" class="form-control" id="cvv" placeholder="123">
                                    </div>
                                </div>
                                <small class="text-muted">For demo purposes only. No actual payment will be processed.</small>
                            </div>
                            
                            <hr class="my-4">
                            
                            <button class="btn btn-dark btn-lg w-100" type="submit">Place Order</button>
                        </form>
                    </div>
                </div>
            </div>
            
            <!-- Order Summary -->
            <div class="col-lg-4 mt-4 mt-lg-0">
                <div class="card mb-4">
                    <div class="card-header bg-dark text-white">
                        <h5 class="mb-0">Order Summary</h5>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between mb-3">
                            <span>Subtotal</span>
                            <span>$<?php echo number_format($subtotal, 2); ?></span>
                        </div>
                        <div class="d-flex justify-content-between mb-3">
                            <span>Shipping</span>
                            <span>
                                <?php if ($shipping > 0): ?>
                                    $<?php echo number_format($shipping, 2); ?>
                                <?php else: ?>
                                    <span class="text-success">Free</span>
                                <?php endif; ?>
                            </span>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between mb-3 fw-bold">
                            <span>Total</span>
                            <span>$<?php echo number_format($total, 2); ?></span>
                        </div>
                    </div>
                </div>
                
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Items in Your Order</h5>
                    </div>
                    <div class="card-body p-0">
                        <ul class="list-group list-group-flush">
                            <?php foreach ($cart_items as $item): ?>
                                <li class="list-group-item">
                                    <div class="d-flex">
                                        <img src="uploads/<?php echo $item['image']; ?>" alt="<?php echo $item['name']; ?>" width="50" height="50" class="me-3">
                                        <div>
                                            <h6 class="mb-0"><?php echo $item['name']; ?></h6>
                                            <small class="text-muted">
                                                Qty: <?php echo $item['quantity']; ?> × 
                                                <?php if ($item['sale_price'] > 0): ?>
                                                    $<?php echo number_format($item['sale_price'], 2); ?>
                                                <?php else: ?>
                                                    $<?php echo number_format($item['price'], 2); ?>
                                                <?php endif; ?>
                                            </small>
                                        </div>
                                    </div>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Toggle payment methods
    const creditCardRadio = document.getElementById('credit_card');
    const paypalRadio = document.getElementById('paypal');
    const codRadio = document.getElementById('cash_on_delivery');
    const creditCardDetails = document.getElementById('credit-card-details');
    
    if (creditCardRadio && paypalRadio && codRadio && creditCardDetails) {
        // Initial state
        creditCardDetails.style.display = creditCardRadio.checked ? 'block' : 'none';
        
        // Event listeners
        creditCardRadio.addEventListener('change', function() {
            creditCardDetails.style.display = 'block';
        });
        
        paypalRadio.addEventListener('change', function() {
            creditCardDetails.style.display = 'none';
        });
        
        codRadio.addEventListener('change', function() {
            creditCardDetails.style.display = 'none';
        });
    }
});
</script>

<?php include_once "includes/footer.php"; ?>
