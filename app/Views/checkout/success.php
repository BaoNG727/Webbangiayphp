<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <?php if ($order): ?>
                <!-- Success Header -->
                <div class="text-center mb-5">
                    <div class="success-icon mb-3">
                        <i class="fas fa-check-circle text-success" style="font-size: 4rem;"></i>
                    </div>
                    <h1 class="h2 text-success mb-2">Order Confirmed!</h1>
                    <p class="text-muted">Thank you for your purchase. Your order has been successfully placed.</p>
                </div>

                <!-- Order Details -->
                <div class="card mb-4">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0">
                            <i class="fas fa-receipt"></i> Order Details
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <h6>Order Information</h6>
                                <table class="table table-borderless table-sm">
                                    <tr>
                                        <td><strong>Order #:</strong></td>
                                        <td><?php echo str_pad($order['id'], 6, '0', STR_PAD_LEFT); ?></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Order Date:</strong></td>
                                        <td><?php echo date('M d, Y H:i', strtotime($order['created_at'])); ?></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Status:</strong></td>
                                        <td>
                                            <span class="badge bg-warning text-dark">
                                                <?php echo ucfirst($order['status']); ?>
                                            </span>
                                        </td>
                                    </tr>                                    <tr>
                                        <td><strong>Payment Method:</strong></td>
                                        <td><?php echo isset($order['payment_method']) ? ucwords(str_replace('_', ' ', $order['payment_method'])) : 'See notes'; ?></td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-md-6">                                <h6>Shipping Address</h6>
                                <address class="small">
                                    <?php echo nl2br(htmlspecialchars(isset($order['shipping_address']) ? $order['shipping_address'] : ($order['name'] . "\n" . $order['address'] . "\n" . $order['city'] . "\n" . $order['phone']))); ?>
                                </address>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Order Items -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="fas fa-shopping-bag"></i> Order Items
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Product</th>
                                        <th>Quantity</th>
                                        <th>Price</th>
                                        <th>Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($order['items'])): ?>
                                        <?php foreach ($order['items'] as $item): ?>
                                            <tr>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <img src="/Webgiay/uploads/<?php echo htmlspecialchars($item['image']); ?>" 
                                                             alt="<?php echo htmlspecialchars($item['name']); ?>" 
                                                             class="img-thumbnail me-3" 
                                                             style="width: 50px; height: 50px; object-fit: cover;">
                                                        <div>
                                                            <h6 class="mb-0"><?php echo htmlspecialchars($item['name']); ?></h6>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td><?php echo $item['quantity']; ?></td>
                                                <td>$<?php echo number_format($item['price'], 2); ?></td>
                                                <td>$<?php echo number_format($item['price'] * $item['quantity'], 2); ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php endif; ?>                                </tbody>
                                <tfoot>
                                    <?php if (!empty($order['subtotal_amount'])): ?>
                                        <tr>
                                            <th colspan="3" class="text-end">Subtotal:</th>
                                            <th>$<?php echo number_format($order['subtotal_amount'], 2); ?></th>
                                        </tr>
                                    <?php endif; ?>
                                    
                                    <?php if (!empty($order['discount_code']) && $order['discount_amount'] > 0): ?>
                                        <tr class="table-success">
                                            <th colspan="3" class="text-end text-success">Discount (<?php echo htmlspecialchars($order['discount_code']); ?>):</th>
                                            <th class="text-success">-$<?php echo number_format($order['discount_amount'], 2); ?></th>
                                        </tr>
                                    <?php endif; ?>
                                    
                                    <tr>
                                        <th colspan="3" class="text-end">Total Amount:</th>
                                        <th>$<?php echo number_format($order['total_amount'], 2); ?></th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Next Steps -->
                <div class="card mb-4">
                    <div class="card-body">
                        <h5><i class="fas fa-info-circle text-primary"></i> What's Next?</h5>
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <div class="text-center">
                                    <i class="fas fa-envelope fa-2x text-primary mb-2"></i>
                                    <h6>Email Confirmation</h6>
                                    <p class="small text-muted">You'll receive an order confirmation email shortly.</p>
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <div class="text-center">
                                    <i class="fas fa-box fa-2x text-primary mb-2"></i>
                                    <h6>Processing</h6>
                                    <p class="small text-muted">We'll prepare your order for shipping within 1-2 business days.</p>
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <div class="text-center">
                                    <i class="fas fa-truck fa-2x text-primary mb-2"></i>
                                    <h6>Shipping</h6>
                                    <p class="small text-muted">Your order will arrive in 5-7 business days.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="text-center">
                    <a href="/Webgiay/" class="btn btn-dark me-3">
                        <i class="fas fa-home"></i> Continue Shopping
                    </a>
                    <a href="/Webgiay/orders" class="btn btn-outline-dark">
                        <i class="fas fa-list"></i> View All Orders
                    </a>
                </div>

                <!-- Print Button -->
                <div class="text-center mt-3">
                    <button onclick="window.print()" class="btn btn-outline-secondary btn-sm">
                        <i class="fas fa-print"></i> Print Order
                    </button>
                </div>

            <?php else: ?>
                <div class="text-center">
                    <i class="fas fa-exclamation-triangle text-warning" style="font-size: 4rem;"></i>
                    <h2 class="mt-3">Order Not Found</h2>
                    <p class="text-muted">We couldn't find your order details.</p>
                    <a href="/Webgiay/" class="btn btn-dark">Return to Home</a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<style>
.success-icon {
    animation: successPulse 2s ease-in-out;
}

@keyframes successPulse {
    0% {
        transform: scale(0.8);
        opacity: 0.5;
    }
    50% {
        transform: scale(1.1);
        opacity: 0.8;
    }
    100% {
        transform: scale(1);
        opacity: 1;
    }
}

@media print {
    .btn, .navbar, footer {
        display: none !important;
    }
    
    .container {
        max-width: 100% !important;
    }
    
    .card {
        border: 1px solid #000 !important;
        box-shadow: none !important;
    }
}

.table-borderless td {
    border: none;
    padding: 0.25rem 0.5rem;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Add some interaction for the success page
    setTimeout(function() {
        const successIcon = document.querySelector('.success-icon i');
        if (successIcon) {
            successIcon.style.transform = 'scale(1.1)';
            setTimeout(() => {
                successIcon.style.transform = 'scale(1)';
            }, 200);
        }
    }, 500);
});
</script>
