<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="checkout-header mb-4">
                <h1 class="h2">Checkout</h1>
                <div class="progress" style="height: 3px;">
                    <div class="progress-bar bg-dark" role="progressbar" style="width: 50%" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
                <div class="d-flex justify-content-between mt-2">
                    <small class="text-muted">Cart</small>
                    <small><strong>Checkout</strong></small>
                    <small class="text-muted">Confirmation</small>
                </div>
            </div>

            <?php 
            $errors = $this->session('checkout_errors');
            $formData = $this->session('checkout_data', []);
            
            // Clear session data
            unset($_SESSION['checkout_errors']);
            unset($_SESSION['checkout_data']);
            ?>

            <?php if (!empty($errors)): ?>
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        <?php foreach ($errors as $error): ?>
                            <li><?php echo htmlspecialchars($error); ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <form method="POST" action="/Webgiay/checkout/process" id="checkout-form">
                <div class="row">
                    <!-- Shipping Information -->
                    <div class="col-lg-7">
                        <div class="card mb-4">
                            <div class="card-header">
                                <h5 class="mb-0"><i class="fas fa-shipping-fast"></i> Shipping Information</h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="first_name" class="form-label">First Name *</label>
                                        <input type="text" class="form-control" id="first_name" name="first_name" 
                                               value="<?php echo htmlspecialchars($formData['first_name'] ?? ''); ?>" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="last_name" class="form-label">Last Name *</label>
                                        <input type="text" class="form-control" id="last_name" name="last_name" 
                                               value="<?php echo htmlspecialchars($formData['last_name'] ?? ''); ?>" required>
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="email" class="form-label">Email *</label>
                                        <input type="email" class="form-control" id="email" name="email" 
                                               value="<?php echo htmlspecialchars($formData['email'] ?? ($_SESSION['email'] ?? '')); ?>" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="phone" class="form-label">Phone *</label>
                                        <input type="tel" class="form-control" id="phone" name="phone" 
                                               value="<?php echo htmlspecialchars($formData['phone'] ?? ''); ?>" required>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="address" class="form-label">Address *</label>
                                    <input type="text" class="form-control" id="address" name="address" 
                                           value="<?php echo htmlspecialchars($formData['address'] ?? ''); ?>" 
                                           placeholder="Street address, P.O. box, company name, c/o" required>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="city" class="form-label">City *</label>
                                        <input type="text" class="form-control" id="city" name="city" 
                                               value="<?php echo htmlspecialchars($formData['city'] ?? ''); ?>" required>
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <label for="state" class="form-label">State *</label>
                                        <input type="text" class="form-control" id="state" name="state" 
                                               value="<?php echo htmlspecialchars($formData['state'] ?? ''); ?>" required>
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <label for="zip" class="form-label">ZIP Code *</label>
                                        <input type="text" class="form-control" id="zip" name="zip" 
                                               value="<?php echo htmlspecialchars($formData['zip'] ?? ''); ?>" required>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="country" class="form-label">Country *</label>
                                    <select class="form-select" id="country" name="country" required>
                                        <option value="">Select Country</option>
                                        <option value="US" <?php echo ($formData['country'] ?? '') === 'US' ? 'selected' : ''; ?>>United States</option>
                                        <option value="CA" <?php echo ($formData['country'] ?? '') === 'CA' ? 'selected' : ''; ?>>Canada</option>
                                        <option value="UK" <?php echo ($formData['country'] ?? '') === 'UK' ? 'selected' : ''; ?>>United Kingdom</option>
                                        <option value="AU" <?php echo ($formData['country'] ?? '') === 'AU' ? 'selected' : ''; ?>>Australia</option>
                                        <option value="VN" <?php echo ($formData['country'] ?? '') === 'VN' ? 'selected' : ''; ?>>Vietnam</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Payment Information -->
                        <div class="card mb-4">
                            <div class="card-header">
                                <h5 class="mb-0"><i class="fas fa-credit-card"></i> Payment Method</h5>
                            </div>
                            <div class="card-body">
                                <div class="payment-methods">
                                    <div class="form-check mb-3">
                                        <input class="form-check-input" type="radio" name="payment_method" 
                                               id="credit_card" value="credit_card" 
                                               <?php echo ($formData['payment_method'] ?? '') === 'credit_card' ? 'checked' : ''; ?> required>
                                        <label class="form-check-label" for="credit_card">
                                            <i class="fas fa-credit-card me-2"></i>
                                            Credit/Debit Card
                                        </label>
                                    </div>
                                    
                                    <div class="form-check mb-3">
                                        <input class="form-check-input" type="radio" name="payment_method" 
                                               id="paypal" value="paypal"
                                               <?php echo ($formData['payment_method'] ?? '') === 'paypal' ? 'checked' : ''; ?>>
                                        <label class="form-check-label" for="paypal">
                                            <i class="fab fa-paypal me-2"></i>
                                            PayPal
                                        </label>
                                    </div>
                                    
                                    <div class="form-check mb-3">
                                        <input class="form-check-input" type="radio" name="payment_method" 
                                               id="cash_on_delivery" value="cash_on_delivery"
                                               <?php echo ($formData['payment_method'] ?? '') === 'cash_on_delivery' ? 'checked' : ''; ?>>
                                        <label class="form-check-label" for="cash_on_delivery">
                                            <i class="fas fa-money-bill me-2"></i>
                                            Cash on Delivery
                                        </label>
                                    </div>
                                </div>

                                <div class="alert alert-info mt-3">
                                    <small>
                                        <i class="fas fa-info-circle"></i>
                                        <strong>Note:</strong> This is a demo store. No actual payment will be processed.
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Order Summary -->
                    <div class="col-lg-5">
                        <div class="card sticky-top" style="top: 20px;">
                            <div class="card-header">
                                <h5 class="mb-0"><i class="fas fa-shopping-bag"></i> Order Summary</h5>
                            </div>
                            <div class="card-body">
                                <!-- Cart Items -->
                                <div class="order-items mb-3">
                                    <?php foreach ($cart_items as $item): ?>
                                        <div class="d-flex align-items-center mb-3 pb-3 border-bottom">
                                            <img src="/Webgiay/uploads/<?php echo htmlspecialchars($item['image']); ?>" 
                                                 alt="<?php echo htmlspecialchars($item['name']); ?>" 
                                                 class="img-thumbnail me-3" style="width: 60px; height: 60px; object-fit: cover;">
                                            <div class="flex-grow-1">
                                                <h6 class="mb-1"><?php echo htmlspecialchars($item['name']); ?></h6>
                                                <small class="text-muted">Qty: <?php echo $item['quantity']; ?></small>
                                            </div>
                                            <div class="text-end">
                                                <?php 
                                                $price = ($item['sale_price'] > 0) ? $item['sale_price'] : $item['price'];
                                                echo '$' . number_format($price * $item['quantity'], 2);
                                                ?>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>

                                <!-- Order Totals -->
                                <div class="order-totals">
                                    <div class="d-flex justify-content-between mb-2">
                                        <span>Subtotal:</span>
                                        <span>$<?php echo number_format($cart_total, 2); ?></span>
                                    </div>
                                    <div class="d-flex justify-content-between mb-2">
                                        <span>Shipping:</span>
                                        <span>
                                            <?php if ($shipping_cost > 0): ?>
                                                $<?php echo number_format($shipping_cost, 2); ?>
                                            <?php else: ?>
                                                <span class="text-success">Free</span>
                                            <?php endif; ?>
                                        </span>
                                    </div>
                                    <hr>
                                    <div class="d-flex justify-content-between mb-3">
                                        <strong>Total:</strong>
                                        <strong class="text-primary">$<?php echo number_format($final_total, 2); ?></strong>
                                    </div>
                                </div>

                                <button type="submit" class="btn btn-dark btn-lg w-100" id="place-order-btn">
                                    <i class="fas fa-lock"></i> Place Order
                                </button>

                                <div class="text-center mt-3">
                                    <small class="text-muted">
                                        <i class="fas fa-shield-alt"></i> 
                                        Your order is secured with SSL encryption
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
.checkout-header {
    text-align: center;
}

.payment-methods .form-check {
    padding: 15px;
    border: 1px solid #dee2e6;
    border-radius: 8px;
    transition: all 0.3s;
}

.payment-methods .form-check:hover {
    border-color: #495057;
    background-color: #f8f9fa;
}

.payment-methods .form-check-input:checked + .form-check-label {
    color: #495057;
    font-weight: 500;
}

.order-items {
    max-height: 300px;
    overflow-y: auto;
}

.sticky-top {
    z-index: 1020;
}

@media (max-width: 991.98px) {
    .sticky-top {
        position: relative !important;
        top: auto !important;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('checkout-form');
    const submitBtn = document.getElementById('place-order-btn');
    
    form.addEventListener('submit', function(e) {
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Processing...';
        
        // Re-enable after 5 seconds in case of issues
        setTimeout(() => {
            submitBtn.disabled = false;
            submitBtn.innerHTML = '<i class="fas fa-lock"></i> Place Order';
        }, 5000);
    });

    // Form validation enhancement
    const requiredFields = form.querySelectorAll('[required]');
    
    requiredFields.forEach(field => {
        field.addEventListener('blur', function() {
            if (this.value.trim() === '') {
                this.classList.add('is-invalid');
            } else {
                this.classList.remove('is-invalid');
                this.classList.add('is-valid');
            }
        });
    });

    // Email validation
    const emailField = document.getElementById('email');
    emailField.addEventListener('blur', function() {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailRegex.test(this.value)) {
            this.classList.add('is-invalid');
        } else {
            this.classList.remove('is-invalid');
            this.classList.add('is-valid');
        }
    });
});
</script>
