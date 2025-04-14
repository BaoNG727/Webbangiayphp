<?php
include_once "includes/header.php";

// Redirect if not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php?redirect=cart.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Get cart items
$cart_query = "SELECT c.*, p.name, p.price, p.sale_price, p.image
              FROM cart c
              JOIN products p ON c.product_id = p.id
              WHERE c.user_id = $user_id";
$cart_result = mysqli_query($conn, $cart_query);
$cart_count = mysqli_num_rows($cart_result);

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
?>

<div class="container py-4">
    <h1 class="mb-4">Shopping Cart</h1>
    
    <?php if ($cart_count > 0): ?>
        <div class="row" id="cart-container">
            <!-- Cart Items -->
            <div class="col-lg-8">
                <?php foreach ($cart_items as $item): ?>
                    <div class="card mb-3 cart-item" id="cart-item-<?php echo $item['product_id']; ?>">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-md-2 mb-2 mb-md-0">
                                    <a href="product.php?id=<?php echo $item['product_id']; ?>">
                                        <img src="uploads/<?php echo $item['image']; ?>" alt="<?php echo $item['name']; ?>" class="img-fluid cart-item-img">
                                    </a>
                                </div>
                                <div class="col-md-4 mb-2 mb-md-0">
                                    <h5 class="cart-item-title">
                                        <a href="product.php?id=<?php echo $item['product_id']; ?>" class="text-decoration-none text-dark">
                                            <?php echo $item['name']; ?>
                                        </a>
                                    </h5>
                                    <div class="text-muted">
                                        <?php if ($item['sale_price'] > 0): ?>
                                            <span class="text-danger">$<?php echo number_format($item['sale_price'], 2); ?></span>
                                            <del class="ms-2">$<?php echo number_format($item['price'], 2); ?></del>
                                        <?php else: ?>
                                            $<?php echo number_format($item['price'], 2); ?>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <div class="col-md-3 mb-2 mb-md-0">
                                    <div class="quantity-selector">
                                        <button type="button" class="btn-minus">-</button>
                                        <input type="number" value="<?php echo $item['quantity']; ?>" min="1" data-product-id="<?php echo $item['product_id']; ?>" readonly>
                                        <button type="button" class="btn-plus">+</button>
                                    </div>
                                </div>
                                <div class="col-md-2 mb-2 mb-md-0 text-end">
                                    <?php
                                    $item_price = ($item['sale_price'] > 0) ? $item['sale_price'] : $item['price'];
                                    $item_subtotal = $item_price * $item['quantity'];
                                    ?>
                                    <div class="cart-item-price" id="subtotal-<?php echo $item['product_id']; ?>">
                                        $<?php echo number_format($item_subtotal, 2); ?>
                                    </div>
                                </div>
                                <div class="col-md-1 text-end">
                                    <a href="#" class="text-danger remove-from-cart" data-product-id="<?php echo $item['product_id']; ?>">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
                
                <div class="d-flex justify-content-between mt-4">
                    <a href="products.php" class="btn btn-outline-dark">
                        <i class="fas fa-arrow-left me-2"></i> Continue Shopping
                    </a>
                    <a href="checkout.php" class="btn btn-dark">
                        Proceed to Checkout <i class="fas fa-arrow-right ms-2"></i>
                    </a>
                </div>
            </div>
            
            <!-- Order Summary -->
            <div class="col-lg-4 mt-4 mt-lg-0">
                <div class="card">
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
                            <span id="cart-total">$<?php echo number_format($total, 2); ?></span>
                        </div>
                        <a href="checkout.php" class="btn btn-dark w-100">Proceed to Checkout</a>
                    </div>
                </div>
                
                <div class="card mt-3">
                    <div class="card-body">
                        <h6 class="mb-3">We Accept</h6>
                        <div class="d-flex gap-2">
                            <img src="assets/images/visa.png" alt="Visa" height="30">
                            <img src="assets/images/mastercard.png" alt="Mastercard" height="30">
                            <img src="assets/images/amex.png" alt="American Express" height="30">
                            <img src="assets/images/paypal.png" alt="PayPal" height="30">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php else: ?>
        <div class="alert alert-info">
            Your cart is empty. <a href="products.php">Continue shopping</a>
        </div>
    <?php endif; ?>
</div>

<?php include_once "includes/footer.php"; ?>
