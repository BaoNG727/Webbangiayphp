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
                
                // Add loading effect to button
                this.classList.add('adding');
                this.textContent = 'Adding...';
                
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
                    // Remove loading effect
                    this.classList.remove('adding');
                    this.textContent = 'Add to Cart';
                    
                    if (data.success) {
                        // Update cart icon
                        const cartBadge = document.querySelector('.fa-shopping-cart + .badge');
                        if (cartBadge) {
                            cartBadge.textContent = data.cart_count;
                            cartBadge.style.display = data.cart_count > 0 ? 'block' : 'none';
                        }
                        
                        // Show success message
                        showNotification('Product added to cart', 'success');
                        
                        // Show quick confirmation with product image
                        showCartConfirmation(productId, data.product_name, data.product_image);
                    } else {
                        showNotification(data.message || 'Failed to add product to cart', 'error');
                    }
                })
                .catch(error => {
                    // Remove loading effect
                    this.classList.remove('adding');
                    this.textContent = 'Add to Cart';
                    
                    console.error('Error:', error);
                    showNotification('An error occurred. Please try again.', 'error');
                });
            });
        });
    }
    
    // Wishlist functionality
    const wishlistButtons = document.querySelectorAll('.btn-wishlist');
    
    if (wishlistButtons.length > 0) {
        // Get wishlist from localStorage
        let wishlist = JSON.parse(localStorage.getItem('wishlist')) || [];
        
        // Update UI based on stored wishlist
        wishlistButtons.forEach(button => {
            const productId = button.getAttribute('data-product-id');
            if (wishlist.includes(productId)) {
                button.classList.add('active');
                button.querySelector('.fa-heart').classList.remove('far');
                button.querySelector('.fa-heart').classList.add('fas');
            }
        });
        
        // Add click event to wishlist buttons
        wishlistButtons.forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                
                const productId = this.getAttribute('data-product-id');
                const heartIcon = this.querySelector('.fa-heart');
                
                // Check if product is already in wishlist
                const inWishlist = wishlist.includes(productId);
                
                if (inWishlist) {
                    // Remove from wishlist
                    wishlist = wishlist.filter(id => id !== productId);
                    this.classList.remove('active');
                    heartIcon.classList.remove('fas');
                    heartIcon.classList.add('far');
                    showNotification('Product removed from wishlist', 'info');
                } else {
                    // Add to wishlist
                    wishlist.push(productId);
                    this.classList.add('active');
                    heartIcon.classList.remove('far');
                    heartIcon.classList.add('fas');
                    
                    // Add heart beat animation
                    heartIcon.style.animation = 'heart-beat 0.8s';
                    setTimeout(() => {
                        heartIcon.style.animation = '';
                    }, 800);
                    
                    showNotification('Product added to wishlist', 'success');
                }
                
                // Save wishlist to localStorage
                localStorage.setItem('wishlist', JSON.stringify(wishlist));
                
                // Update wishlist count in header if exists
                const wishlistCount = document.querySelector('.wishlist-count');
                if (wishlistCount) {
                    wishlistCount.textContent = wishlist.length;
                    wishlistCount.style.display = wishlist.length > 0 ? 'block' : 'none';
                }
            });
        });
    }
    
    // Product Image Gallery
    const thumbnails = document.querySelectorAll('.thumbnail');
    const mainProductImage = document.getElementById('mainProductImage');
    
    if (thumbnails.length > 0 && mainProductImage) {
        thumbnails.forEach(thumbnail => {
            thumbnail.addEventListener('click', function() {
                // Remove active class from all thumbnails
                thumbnails.forEach(t => t.classList.remove('active'));
                
                // Add active class to clicked thumbnail
                this.classList.add('active');
                
                // Update main product image
                const newImageSrc = this.getAttribute('data-img');
                mainProductImage.src = newImageSrc;
                
                // Add fade effect
                mainProductImage.style.opacity = '0.5';
                setTimeout(() => {
                    mainProductImage.style.opacity = '1';
                }, 200);
            });
        });
    }
    
    // Back to Top Button
    const backToTopButton = document.getElementById('backToTop');
    
    if (backToTopButton) {
        window.addEventListener('scroll', () => {
            if (window.pageYOffset > 300) {
                backToTopButton.classList.add('show');
            } else {
                backToTopButton.classList.remove('show');
            }
        });
        
        backToTopButton.addEventListener('click', () => {
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
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
    
    // Cart confirmation popup
    function showCartConfirmation(productId, productName, productImage) {
        const confirmation = document.createElement('div');
        confirmation.className = 'cart-confirmation';
        confirmation.style.cssText = `
            position: fixed;
            top: 100px;
            right: 20px;
            background: white;
            border-radius: 8px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
            width: 320px;
            z-index: 1001;
            overflow: hidden;
            animation: slideIn 0.5s forwards;
        `;
        
        confirmation.innerHTML = `
            <div class="p-3 bg-success text-white d-flex justify-content-between align-items-center">
                <div><i class="fas fa-check-circle me-2"></i> Added to Cart</div>
                <button class="btn-close btn-close-white" aria-label="Close"></button>
            </div>
            <div class="p-3">
                <div class="d-flex">
                    <div class="me-3">
                        <img src="/Webgiay/uploads/${productImage}" alt="${productName}" width="70" height="70" class="object-fit-cover rounded">
                    </div>
                    <div>
                        <h6 class="mb-1">${productName}</h6>
                        <p class="mb-3 text-muted small">Added to your cart successfully!</p>
                        <div class="d-flex">
                            <a href="/Webgiay/cart.php" class="btn btn-sm btn-dark me-2">View Cart</a>
                            <a href="/Webgiay/checkout.php" class="btn btn-sm btn-outline-dark">Checkout</a>
                        </div>
                    </div>
                </div>
            </div>
        `;
        
        document.body.appendChild(confirmation);
        
        // Add animation styles
        const style = document.createElement('style');
        style.textContent = `
            @keyframes slideIn {
                from { transform: translateX(100%); }
                to { transform: translateX(0); }
            }
            
            @keyframes slideOut {
                from { transform: translateX(0); }
                to { transform: translateX(100%); }
            }
        `;
        document.head.appendChild(style);
        
        // Close button functionality
        const closeBtn = confirmation.querySelector('.btn-close');
        if (closeBtn) {
            closeBtn.addEventListener('click', function() {
                confirmation.style.animation = 'slideOut 0.5s forwards';
                setTimeout(() => {
                    document.body.removeChild(confirmation);
                }, 500);
            });
        }
        
        // Auto close after 5 seconds
        setTimeout(() => {
            if (document.body.contains(confirmation)) {
                confirmation.style.animation = 'slideOut 0.5s forwards';
                setTimeout(() => {
                    if (document.body.contains(confirmation)) {
                        document.body.removeChild(confirmation);
                    }
                }, 500);
            }
        }, 5000);
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
