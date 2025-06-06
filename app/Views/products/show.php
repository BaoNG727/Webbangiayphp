<?php if ($product): ?>
<div class="container py-4">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/Webgiay/">Home</a></li>
            <li class="breadcrumb-item"><a href="/Webgiay/products">Products</a></li>
            <li class="breadcrumb-item"><a href="/Webgiay/products?category=<?php echo urlencode($product['category']); ?>"><?php echo htmlspecialchars($product['category']); ?></a></li>
            <li class="breadcrumb-item active" aria-current="page"><?php echo htmlspecialchars($product['name']); ?></li>
        </ol>
    </nav>

    <div class="row">
        <!-- Product Image -->
        <div class="col-lg-6 mb-4">
            <div class="product-image-container">
                <img src="/Webgiay/uploads/<?php echo htmlspecialchars($product['image']); ?>" 
                     alt="<?php echo htmlspecialchars($product['name']); ?>" 
                     class="img-fluid rounded shadow-sm"
                     id="main-product-image">
                
                <?php if ($product['sale_price'] && $product['sale_price'] > 0): ?>
                    <span class="badge bg-danger position-absolute top-0 start-0 m-3 fs-6">
                        <?php echo round((($product['price'] - $product['sale_price']) / $product['price']) * 100); ?>% OFF
                    </span>
                <?php endif; ?>
            </div>
        </div>

        <!-- Product Details -->
        <div class="col-lg-6">
            <div class="product-details">
                <div class="mb-2">
                    <span class="badge bg-secondary"><?php echo htmlspecialchars($product['category']); ?></span>
                </div>
                
                <h1 class="h2 mb-3"><?php echo htmlspecialchars($product['name']); ?></h1>
                
                <div class="price-section mb-4">
                    <?php if ($product['sale_price'] && $product['sale_price'] > 0): ?>
                        <span class="h3 text-danger me-3">$<?php echo number_format($product['sale_price'], 2); ?></span>
                        <span class="h5 text-muted text-decoration-line-through">$<?php echo number_format($product['price'], 2); ?></span>
                        <div class="text-success">
                            <small>You save $<?php echo number_format($product['price'] - $product['sale_price'], 2); ?></small>
                        </div>
                    <?php else: ?>
                        <span class="h3 text-dark">$<?php echo number_format($product['price'], 2); ?></span>
                    <?php endif; ?>
                </div>

                <div class="stock-info mb-4">
                    <?php if ($product['stock'] > 0): ?>
                        <div class="text-success">
                            <i class="fas fa-check-circle"></i> 
                            <strong>In Stock</strong> (<?php echo $product['stock']; ?> available)
                        </div>
                    <?php else: ?>
                        <div class="text-danger">
                            <i class="fas fa-times-circle"></i> 
                            <strong>Out of Stock</strong>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="product-description mb-4">
                    <h5>Description</h5>
                    <p class="text-muted"><?php echo nl2br(htmlspecialchars($product['description'])); ?></p>
                </div>

                <!-- Add to Cart Section -->
                <?php if ($product['stock'] > 0): ?>
                    <div class="add-to-cart-section">
                        <?php if (isset($_SESSION['user_id'])): ?>
                            <form id="add-to-cart-form" class="mb-4">
                                <div class="row align-items-end">
                                    <div class="col-4">
                                        <label for="quantity" class="form-label">Quantity:</label>
                                        <div class="input-group">
                                            <button class="btn btn-outline-secondary" type="button" id="decrease-qty">-</button>
                                            <input type="number" class="form-control text-center" id="quantity" 
                                                   value="1" min="1" max="<?php echo $product['stock']; ?>">
                                            <button class="btn btn-outline-secondary" type="button" id="increase-qty">+</button>
                                        </div>
                                    </div>
                                    <div class="col-8">
                                        <button type="submit" class="btn btn-dark btn-lg w-100">
                                            <i class="fas fa-shopping-cart"></i> Add to Cart
                                        </button>
                                    </div>
                                </div>
                            </form>
                        <?php else: ?>
                            <div class="alert alert-info">
                                <a href="/Webgiay/login?redirect=<?php echo urlencode($_SERVER['REQUEST_URI']); ?>" class="btn btn-dark">
                                    Login to Purchase
                                </a>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>

                <!-- Product Features -->
                <div class="product-features">
                    <div class="row text-center">
                        <div class="col-4">
                            <div class="feature-item p-3">
                                <i class="fas fa-shipping-fast text-primary mb-2"></i>
                                <small class="d-block">Free Shipping</small>
                                <small class="text-muted">Orders over $50</small>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="feature-item p-3">
                                <i class="fas fa-undo text-primary mb-2"></i>
                                <small class="d-block">Easy Returns</small>
                                <small class="text-muted">30 days</small>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="feature-item p-3">
                                <i class="fas fa-shield-alt text-primary mb-2"></i>
                                <small class="d-block">Warranty</small>
                                <small class="text-muted">1 Year</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Related Products -->
    <?php if (!empty($related_products) && count($related_products) > 1): ?>
    <section class="related-products mt-5">
        <h3 class="mb-4">Related Products</h3>
        <div class="row">
            <?php foreach (array_slice($related_products, 0, 4) as $related): ?>
                <?php if ($related['id'] != $product['id']): ?>
                <div class="col-lg-3 col-md-6 mb-4">
                    <div class="card product-card h-100">
                        <div class="product-image">
                            <img src="/Webgiay/uploads/<?php echo htmlspecialchars($related['image']); ?>" 
                                 class="card-img-top" alt="<?php echo htmlspecialchars($related['name']); ?>">
                            <?php if ($related['sale_price'] && $related['sale_price'] > 0): ?>
                                <span class="badge bg-danger position-absolute top-0 start-0 m-2">Sale</span>
                            <?php endif; ?>
                        </div>
                        <div class="card-body d-flex flex-column">
                            <h6 class="card-title"><?php echo htmlspecialchars($related['name']); ?></h6>
                            <div class="price-section mt-auto">
                                <?php if ($related['sale_price'] && $related['sale_price'] > 0): ?>
                                    <span class="price-sale">$<?php echo number_format($related['sale_price'], 2); ?></span>
                                    <span class="price-original small">$<?php echo number_format($related['price'], 2); ?></span>
                                <?php else: ?>
                                    <span class="price">$<?php echo number_format($related['price'], 2); ?></span>
                                <?php endif; ?>
                            </div>
                            <div class="mt-2">
                                <a href="/Webgiay/product/<?php echo $related['id']; ?>" class="btn btn-outline-dark btn-sm w-100">
                                    View Details
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>
    </section>
    <?php endif; ?>
</div>

<!-- Success Modal -->
<div class="modal fade" id="addToCartModal" tabindex="-1" aria-labelledby="addToCartModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addToCartModalLabel">
                    <i class="fas fa-check-circle text-success"></i> Added to Cart
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="cart-message"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Continue Shopping</button>
                <a href="/Webgiay/cart" class="btn btn-dark">View Cart</a>
            </div>
        </div>
    </div>
</div>

<style>
.product-image-container {
    position: relative;
    background: #f8f9fa;
    border-radius: 8px;
    padding: 20px;
}

.product-image-container img {
    width: 100%;
    height: auto;
    max-height: 500px;
    object-fit: contain;
}

.price-sale {
    font-size: 1.5rem;
    font-weight: bold;
    color: #dc3545;
}

.price-original {
    color: #6c757d;
}

.feature-item {
    border: 1px solid #e9ecef;
    border-radius: 8px;
    transition: transform 0.2s;
}

.feature-item:hover {
    transform: translateY(-2px);
}

.product-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.15);
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Quantity controls
    const decreaseBtn = document.getElementById('decrease-qty');
    const increaseBtn = document.getElementById('increase-qty');
    const quantityInput = document.getElementById('quantity');
    const maxStock = <?php echo $product['stock']; ?>;

    if (decreaseBtn) {
        decreaseBtn.addEventListener('click', function() {
            let qty = parseInt(quantityInput.value);
            if (qty > 1) {
                quantityInput.value = qty - 1;
            }
        });
    }

    if (increaseBtn) {
        increaseBtn.addEventListener('click', function() {
            let qty = parseInt(quantityInput.value);
            if (qty < maxStock) {
                quantityInput.value = qty + 1;
            }
        });
    }

    // Add to cart form
    const addToCartForm = document.getElementById('add-to-cart-form');
    if (addToCartForm) {
        addToCartForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const quantity = quantityInput.value;
            const productId = <?php echo $product['id']; ?>;
            
            fetch('/Webgiay/product/add-to-cart', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `product_id=${productId}&quantity=${quantity}`
            })
            .then(response => response.json())
            .then(data => {
                const messageDiv = document.getElementById('cart-message');
                
                if (data.success) {
                    messageDiv.innerHTML = `
                        <div class="alert alert-success">
                            <strong>${quantity}x <?php echo htmlspecialchars($product['name']); ?></strong> added to your cart!
                        </div>
                    `;
                    
                    // Update cart count in navbar
                    const cartBadge = document.querySelector('.navbar .badge');
                    if (cartBadge) {
                        cartBadge.textContent = data.cart_count;
                    } else if (data.cart_count > 0) {
                        // Create badge if it doesn't exist
                        const cartLink = document.querySelector('.navbar a[href="/Webgiay/cart"]');
                        if (cartLink) {
                            cartLink.innerHTML += `<span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">${data.cart_count}</span>`;
                        }
                    }
                } else {
                    messageDiv.innerHTML = `<div class="alert alert-danger">${data.message}</div>`;
                }
                
                // Show modal
                const modal = new bootstrap.Modal(document.getElementById('addToCartModal'));
                modal.show();
            })
            .catch(error => {
                console.error('Error:', error);
                const messageDiv = document.getElementById('cart-message');
                messageDiv.innerHTML = `<div class="alert alert-danger">An error occurred. Please try again.</div>`;
                
                const modal = new bootstrap.Modal(document.getElementById('addToCartModal'));
                modal.show();
            });
        });
    }
});
</script>

<?php else: ?>
<div class="container py-5">
    <div class="text-center">
        <h1 class="h2 mb-3">Product Not Found</h1>
        <p class="text-muted mb-4">The product you're looking for doesn't exist or may have been removed.</p>
        <a href="/Webgiay/products" class="btn btn-dark">Browse All Products</a>
    </div>
</div>
<?php endif; ?>
