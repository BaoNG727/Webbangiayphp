<!-- Hero Section -->
<section class="hero-section">
    <div class="container">
        <div id="heroCarousel" class="carousel slide" data-bs-ride="carousel">
            <div class="carousel-indicators">
                <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
                <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="1" aria-label="Slide 2"></button>
                <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="2" aria-label="Slide 3"></button>
            </div>
            <div class="carousel-inner rounded">
                <div class="carousel-item active">
                    <div class="row align-items-center">
                        <div class="col-lg-6">
                            <h1 class="hero-title">Just Do It. <br>With Style.</h1>
                            <p class="hero-subtitle">Discover the latest Nike innovations, top performance shoes, and premium sportswear.</p>
                            <a href="/Webgiay/products" class="btn btn-dark">Shop Now</a>
                        </div>
                        <div class="col-lg-6">
                            <img src="/Webgiay/assets/images/hero-shoe.jpg" alt="Featured Nike Shoe" class="img-fluid rounded">
                        </div>
                    </div>
                </div>
                <div class="carousel-item">
                    <div class="row align-items-center">
                        <div class="col-lg-6">
                            <h1 class="hero-title">Run Further.<br>Go Faster.</h1>
                            <p class="hero-subtitle">Experience ultimate comfort with the new Nike Air collection.</p>
                            <a href="/Webgiay/products?category=Running" class="btn btn-dark">Shop Running</a>
                        </div>
                        <div class="col-lg-6">
                            <img src="/Webgiay/assets/images/hero-shoe.jpg" alt="Nike Running Shoes" class="img-fluid rounded">
                        </div>
                    </div>
                </div>
                <div class="carousel-item">
                    <div class="row align-items-center">
                        <div class="col-lg-6">
                            <h1 class="hero-title">Court Ready.<br>Game On.</h1>
                            <p class="hero-subtitle">Dominate the game with the latest Nike basketball shoes.</p>
                            <a href="/Webgiay/products?category=Basketball" class="btn btn-dark">Shop Basketball</a>
                        </div>
                        <div class="col-lg-6">
                            <img src="/Webgiay/assets/images/hero-shoe.jpg" alt="Nike Basketball Shoes" class="img-fluid rounded">
                        </div>
                    </div>
                </div>
            </div>
            <button class="carousel-control-prev" type="button" data-bs-target="#heroCarousel" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#heroCarousel" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
            </button>
        </div>
    </div>
</section>

<!-- Categories Section -->
<section class="categories-section py-5">
    <div class="container">
        <h2 class="text-center mb-5">Shop by Category</h2>
        <div class="row">
            <div class="col-md-3 mb-4">
                <div class="category-card">
                    <a href="/Webgiay/products?category=Running" class="text-decoration-none">
                        <div class="category-image">
                            <img src="/Webgiay/assets/images/category-running.jpg" alt="Running Shoes" class="img-fluid rounded">
                        </div>
                        <h5 class="category-title text-center mt-3">Running</h5>
                    </a>
                </div>
            </div>
            <div class="col-md-3 mb-4">
                <div class="category-card">
                    <a href="/Webgiay/products?category=Basketball" class="text-decoration-none">
                        <div class="category-image">
                            <img src="/Webgiay/assets/images/category-basketball.jpg" alt="Basketball Shoes" class="img-fluid rounded">
                        </div>
                        <h5 class="category-title text-center mt-3">Basketball</h5>
                    </a>
                </div>
            </div>
            <div class="col-md-3 mb-4">
                <div class="category-card">
                    <a href="/Webgiay/products?category=Casual" class="text-decoration-none">
                        <div class="category-image">
                            <img src="/Webgiay/assets/images/category-casual.jpg" alt="Casual Shoes" class="img-fluid rounded">
                        </div>
                        <h5 class="category-title text-center mt-3">Casual</h5>
                    </a>
                </div>
            </div>
            <div class="col-md-3 mb-4">
                <div class="category-card">
                    <a href="/Webgiay/products?category=Training" class="text-decoration-none">
                        <div class="category-image">
                            <img src="/Webgiay/assets/images/category-training.jpg" alt="Training Shoes" class="img-fluid rounded">
                        </div>
                        <h5 class="category-title text-center mt-3">Training</h5>
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Featured Products -->
<section class="featured-section py-5 bg-light">
    <div class="container">
        <h2 class="text-center mb-5">Featured Products</h2>
        <div class="row">
            <?php if (!empty($featured_products)): ?>
                <?php foreach ($featured_products as $product): ?>
                    <div class="col-lg-3 col-md-6 mb-4">
                        <div class="card product-card h-100">
                            <div class="product-image">
                                <img src="/Webgiay/uploads/<?php echo htmlspecialchars($product['image']); ?>" 
                                     class="card-img-top" alt="<?php echo htmlspecialchars($product['name']); ?>">
                                <?php if ($product['sale_price'] && $product['sale_price'] > 0): ?>
                                    <span class="badge bg-danger position-absolute top-0 start-0 m-2">Sale</span>
                                <?php endif; ?>
                            </div>
                            <div class="card-body d-flex flex-column">
                                <h5 class="card-title"><?php echo htmlspecialchars($product['name']); ?></h5>
                                <p class="card-text text-muted small flex-grow-1">
                                    <?php echo htmlspecialchars(substr($product['description'], 0, 100)) . '...'; ?>
                                </p>
                                <div class="price-section">
                                    <?php if ($product['sale_price'] && $product['sale_price'] > 0): ?>
                                        <span class="price-sale">$<?php echo number_format($product['sale_price'], 2); ?></span>
                                        <span class="price-original">$<?php echo number_format($product['price'], 2); ?></span>
                                    <?php else: ?>
                                        <span class="price">$<?php echo number_format($product['price'], 2); ?></span>
                                    <?php endif; ?>
                                </div>
                                <div class="mt-3">
                                    <a href="/Webgiay/product/<?php echo $product['id']; ?>" class="btn btn-dark w-100">View Details</a>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="col-12 text-center">
                    <p>No featured products available.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>

<!-- Sale Products -->
<?php if (!empty($sale_products)): ?>
<section class="sale-section py-5">
    <div class="container">
        <h2 class="text-center mb-5">Sale Products</h2>
        <div class="row">
            <?php foreach ($sale_products as $product): ?>
                <div class="col-lg-3 col-md-6 mb-4">
                    <div class="card product-card h-100">
                        <div class="product-image">
                            <img src="/Webgiay/uploads/<?php echo htmlspecialchars($product['image']); ?>" 
                                 class="card-img-top" alt="<?php echo htmlspecialchars($product['name']); ?>">
                            <span class="badge bg-danger position-absolute top-0 start-0 m-2">Sale</span>
                        </div>
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title"><?php echo htmlspecialchars($product['name']); ?></h5>
                            <p class="card-text text-muted small flex-grow-1">
                                <?php echo htmlspecialchars(substr($product['description'], 0, 100)) . '...'; ?>
                            </p>
                            <div class="price-section">
                                <span class="price-sale">$<?php echo number_format($product['sale_price'], 2); ?></span>
                                <span class="price-original">$<?php echo number_format($product['price'], 2); ?></span>
                            </div>
                            <div class="mt-3">
                                <a href="/Webgiay/product/<?php echo $product['id']; ?>" class="btn btn-dark w-100">View Details</a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- Features Section -->
<section class="features-section py-5 bg-light">
    <div class="container">
        <div class="row text-center">
            <div class="col-md-3 mb-3 mb-md-0">
                <div class="feature-item">
                    <i class="fas fa-shipping-fast fa-2x mb-2"></i>
                    <h5>Free Shipping</h5>
                    <p class="mb-0 small">On orders over $50</p>
                </div>
            </div>
            <div class="col-md-3 mb-3 mb-md-0">
                <div class="feature-item">
                    <i class="fas fa-undo fa-2x mb-2"></i>
                    <h5>Easy Returns</h5>
                    <p class="mb-0 small">30-day return policy</p>
                </div>
            </div>
            <div class="col-md-3 mb-3 mb-md-0">
                <div class="feature-item">
                    <i class="fas fa-lock fa-2x mb-2"></i>
                    <h5>Secure Checkout</h5>
                    <p class="mb-0 small">100% protected payments</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="feature-item">
                    <i class="fas fa-headset fa-2x mb-2"></i>
                    <h5>24/7 Support</h5>
                    <p class="mb-0 small">Customer support available</p>
                </div>
            </div>
        </div>
    </div>
</section>
