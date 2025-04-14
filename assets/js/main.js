// Main JavaScript for Nike Shoe Store

document.addEventListener('DOMContentLoaded', function() {
    // Product quantity selector
    const quantityInputs = document.querySelectorAll('.quantity-selector input');
    
    if (quantityInputs.length > 0) {
        quantityInputs.forEach(input => {
            const decrementBtn = input.previousElementSibling;
            const incrementBtn = input.nextElementSibling;
            
            decrementBtn.addEventListener('click', () => {
                const currentValue = parseInt(input.value);
                if (currentValue > 1) {
                    input.value = currentValue - 1;
                    // If in cart page, update the quantity
                    if (input.hasAttribute('data-product-id')) {
                        updateCartQuantity(input.getAttribute('data-product-id'), input.value);
                    }
                }
            });
            
            incrementBtn.addEventListener('click', () => {
                const currentValue = parseInt(input.value);
                input.value = currentValue + 1;
                // If in cart page, update the quantity
                if (input.hasAttribute('data-product-id')) {
                    updateCartQuantity(input.getAttribute('data-product-id'), input.value);
                }
            });
        });
    }
    
    // Add to cart functionality
    const addToCartButtons = document.querySelectorAll('.add-to-cart');
    
    if (addToCartButtons.length > 0) {
        addToCartButtons.forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                
                const productId = this.getAttribute('data-product-id');
                const quantityInput = document.querySelector('.quantity-selector input');
                const quantity = quantityInput ? parseInt(quantityInput.value) : 1;
                
                // Add to cart AJAX request
                fetch('/Webgiay/includes/cart_actions.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: `action=add&product_id=${productId}&quantity=${quantity}`
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Update cart icon
                        const cartBadge = document.querySelector('.fa-shopping-cart + .badge');
                        if (cartBadge) {
                            cartBadge.textContent = data.cart_count;
                            cartBadge.style.display = data.cart_count > 0 ? 'block' : 'none';
                        }
                        
                        // Show success message
                        showNotification('Product added to cart', 'success');
                    } else {
                        showNotification(data.message || 'Failed to add product to cart', 'error');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showNotification('An error occurred. Please try again.', 'error');
                });
            });
        });
    }
    
    // Update cart quantity
    function updateCartQuantity(productId, quantity) {
        fetch('/Webgiay/includes/cart_actions.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `action=update&product_id=${productId}&quantity=${quantity}`
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Update subtotal
                const subtotalElement = document.querySelector(`#subtotal-${productId}`);
                if (subtotalElement) {
                    subtotalElement.textContent = `$${data.subtotal}`;
                }
                
                // Update cart total
                const cartTotalElement = document.querySelector('#cart-total');
                if (cartTotalElement) {
                    cartTotalElement.textContent = `$${data.cart_total}`;
                }
            } else {
                showNotification(data.message || 'Failed to update cart', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('An error occurred. Please try again.', 'error');
        });
    }
    
    // Remove from cart
    const removeButtons = document.querySelectorAll('.remove-from-cart');
    
    if (removeButtons.length > 0) {
        removeButtons.forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                
                const productId = this.getAttribute('data-product-id');
                
                fetch('/Webgiay/includes/cart_actions.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: `action=remove&product_id=${productId}`
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Remove the cart item from DOM
                        const cartItem = document.querySelector(`#cart-item-${productId}`);
                        if (cartItem) {
                            cartItem.remove();
                        }
                        
                        // Update cart total
                        const cartTotalElement = document.querySelector('#cart-total');
                        if (cartTotalElement) {
                            cartTotalElement.textContent = `$${data.cart_total}`;
                        }
                        
                        // Update cart count
                        const cartBadge = document.querySelector('.fa-shopping-cart + .badge');
                        if (cartBadge) {
                            cartBadge.textContent = data.cart_count;
                            cartBadge.style.display = data.cart_count > 0 ? 'block' : 'none';
                        }
                        
                        // Check if cart is empty
                        if (data.cart_count === 0) {
                            const cartContainer = document.querySelector('#cart-container');
                            if (cartContainer) {
                                cartContainer.innerHTML = '<div class="alert alert-info">Your cart is empty. <a href="/Webgiay/products.php">Continue shopping</a></div>';
                            }
                        }
                        
                        showNotification('Product removed from cart', 'success');
                    } else {
                        showNotification(data.message || 'Failed to remove product from cart', 'error');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showNotification('An error occurred. Please try again.', 'error');
                });
            });
        });
    }
    
    // Notification function
    function showNotification(message, type) {
        const notification = document.createElement('div');
        notification.className = `alert alert-${type === 'success' ? 'success' : 'danger'} notification`;
        notification.style.position = 'fixed';
        notification.style.top = '20px';
        notification.style.right = '20px';
        notification.style.zIndex = '1000';
        notification.style.padding = '15px 25px';
        notification.style.borderRadius = '5px';
        notification.style.boxShadow = '0 4px 8px rgba(0,0,0,0.1)';
        notification.textContent = message;
        
        document.body.appendChild(notification);
        
        setTimeout(() => {
            notification.style.opacity = '0';
            notification.style.transition = 'opacity 0.5s ease';
            setTimeout(() => {
                document.body.removeChild(notification);
            }, 500);
        }, 3000);
    }
    
    // Form validation
    const forms = document.querySelectorAll('.needs-validation');
    
    if (forms.length > 0) {
        Array.prototype.slice.call(forms).forEach(function(form) {
            form.addEventListener('submit', function(event) {
                if (!form.checkValidity()) {
                    event.preventDefault();
                    event.stopPropagation();
                }
                form.classList.add('was-validated');
            }, false);
        });
    }
});
