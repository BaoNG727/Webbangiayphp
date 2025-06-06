<div class="container py-4">
    <h1 class="mb-4">Shopping Cart</h1>

    <?php if (!empty($cart_items)): ?>
        <div class="row">
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Product</th>
                                        <th>Price</th>
                                        <th>Quantity</th>
                                        <th>Total</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($cart_items as $item): ?>
                                        <tr data-product-id="<?php echo $item['product_id']; ?>">
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <img src="/Webgiay/uploads/<?php echo htmlspecialchars($item['image']); ?>" 
                                                         alt="<?php echo htmlspecialchars($item['name']); ?>" 
                                                         class="img-thumbnail me-3" style="width: 80px; height: 80px; object-fit: cover;">
                                                    <div>
                                                        <h6 class="mb-0"><?php echo htmlspecialchars($item['name']); ?></h6>
                                                        <small class="text-muted">
                                                            Stock: <?php echo $item['stock']; ?>
                                                        </small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <?php 
                                                $price = ($item['sale_price'] > 0) ? $item['sale_price'] : $item['price'];
                                                echo '$' . number_format($price, 2);
                                                ?>
                                            </td>
                                            <td>
                                                <div class="input-group" style="width: 120px;">
                                                    <button class="btn btn-outline-secondary btn-sm quantity-btn" 
                                                            type="button" data-action="decrease">-</button>
                                                    <input type="number" class="form-control form-control-sm text-center quantity-input" 
                                                           value="<?php echo $item['quantity']; ?>" 
                                                           min="1" max="<?php echo $item['stock']; ?>">
                                                    <button class="btn btn-outline-secondary btn-sm quantity-btn" 
                                                            type="button" data-action="increase">+</button>
                                                </div>
                                            </td>
                                            <td class="item-total">
                                                $<?php echo number_format($price * $item['quantity'], 2); ?>
                                            </td>
                                            <td>
                                                <button class="btn btn-danger btn-sm remove-item" 
                                                        data-product-id="<?php echo $item['product_id']; ?>">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="mt-3">
                    <a href="/Webgiay/products" class="btn btn-outline-dark">
                        <i class="fas fa-arrow-left"></i> Continue Shopping
                    </a>
                    <button class="btn btn-outline-danger ms-2" id="clear-cart">
                        <i class="fas fa-trash"></i> Clear Cart
                    </button>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Order Summary</h5>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between mb-2">
                            <span>Subtotal:</span>
                            <span id="cart-subtotal">$<?php echo number_format($cart_total, 2); ?></span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Shipping:</span>
                            <span>
                                <?php if ($cart_total >= 50): ?>
                                    <span class="text-success">Free</span>
                                <?php else: ?>
                                    $5.99
                                <?php endif; ?>
                            </span>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between mb-3">
                            <strong>Total:</strong>
                            <strong id="cart-total">
                                $<?php echo number_format($cart_total + ($cart_total >= 50 ? 0 : 5.99), 2); ?>
                            </strong>
                        </div>
                        
                        <div class="d-grid">
                            <a href="/Webgiay/checkout" class="btn btn-dark">Proceed to Checkout</a>
                        </div>
                        
                        <div class="text-center mt-3">
                            <small class="text-muted">
                                <i class="fas fa-lock"></i> Secure checkout guaranteed
                            </small>
                        </div>
                    </div>
                </div>

                <!-- Shipping Info -->
                <div class="card mt-3">
                    <div class="card-body">
                        <h6><i class="fas fa-shipping-fast text-primary"></i> Free Shipping</h6>
                        <p class="small text-muted mb-0">
                            Free shipping on orders over $50. 
                            Add $<?php echo number_format(max(0, 50 - $cart_total), 2); ?> more to qualify.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    <?php else: ?>
        <div class="text-center py-5">
            <i class="fas fa-shopping-cart fa-3x text-muted mb-3"></i>
            <h3>Your cart is empty</h3>
            <p class="text-muted">Looks like you haven't added any items to your cart yet.</p>
            <a href="/Webgiay/products" class="btn btn-dark">Start Shopping</a>
        </div>
    <?php endif; ?>
</div>

<!-- Alert Modal -->
<div class="modal fade" id="alertModal" tabindex="-1" aria-labelledby="alertModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="alertModalLabel">Cart Update</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="alert-message">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Quantity update functionality
    document.querySelectorAll('.quantity-btn').forEach(button => {
        button.addEventListener('click', function() {
            const action = this.getAttribute('data-action');
            const input = action === 'increase' 
                ? this.previousElementSibling 
                : this.nextElementSibling;
            const row = this.closest('tr');
            const productId = row.getAttribute('data-product-id');
            
            let quantity = parseInt(input.value);
            const max = parseInt(input.getAttribute('max'));
            
            if (action === 'increase' && quantity < max) {
                quantity++;
            } else if (action === 'decrease' && quantity > 1) {
                quantity--;
            }
            
            input.value = quantity;
            updateCartItem(productId, quantity);
        });
    });

    // Direct quantity input
    document.querySelectorAll('.quantity-input').forEach(input => {
        input.addEventListener('change', function() {
            const quantity = parseInt(this.value);
            const productId = this.closest('tr').getAttribute('data-product-id');
            const max = parseInt(this.getAttribute('max'));
            
            if (quantity > max) {
                this.value = max;
                updateCartItem(productId, max);
            } else if (quantity < 1) {
                this.value = 1;
                updateCartItem(productId, 1);
            } else {
                updateCartItem(productId, quantity);
            }
        });
    });

    // Remove item functionality
    document.querySelectorAll('.remove-item').forEach(button => {
        button.addEventListener('click', function() {
            const productId = this.getAttribute('data-product-id');
            
            if (confirm('Are you sure you want to remove this item?')) {
                removeCartItem(productId);
            }
        });
    });

    // Clear cart functionality
    const clearCartBtn = document.getElementById('clear-cart');
    if (clearCartBtn) {
        clearCartBtn.addEventListener('click', function() {
            if (confirm('Are you sure you want to clear your entire cart?')) {
                clearCart();
            }
        });
    }

    function updateCartItem(productId, quantity) {
        fetch('/Webgiay/cart/update', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `product_id=${productId}&quantity=${quantity}`
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload(); // Reload to update totals
            } else {
                showAlert(data.message, 'danger');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showAlert('An error occurred. Please try again.', 'danger');
        });
    }

    function removeCartItem(productId) {
        fetch('/Webgiay/cart/remove', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `product_id=${productId}`
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                showAlert(data.message, 'danger');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showAlert('An error occurred. Please try again.', 'danger');
        });
    }

    function clearCart() {
        fetch('/Webgiay/cart/clear', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                showAlert(data.message, 'danger');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showAlert('An error occurred. Please try again.', 'danger');
        });
    }

    function showAlert(message, type) {
        const alertMessage = document.getElementById('alert-message');
        alertMessage.innerHTML = `<div class="alert alert-${type}">${message}</div>`;
        
        const modal = new bootstrap.Modal(document.getElementById('alertModal'));
        modal.show();
    }
});
</script>
