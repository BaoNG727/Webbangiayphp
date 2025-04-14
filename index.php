<?php
include_once "includes/header.php";

// Get featured products
$featured_query = "SELECT * FROM products WHERE stock > 0 ORDER BY id DESC LIMIT 4";
$featured_result = mysqli_query($conn, $featured_query);

// Get sale products
$sale_query = "SELECT * FROM products WHERE sale_price IS NOT NULL AND sale_price > 0 AND stock > 0 ORDER BY (price - sale_price) DESC LIMIT 4";
$sale_result = mysqli_query($conn, $sale_query);
?>

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
                            <a href="products.php" class="btn btn-dark">Shop Now</a>
                        </div>
                        <div class="col-lg-6">
                            <img src="assets/images/hero-shoe.jpg" alt="Featured Nike Shoe" class="img-fluid rounded">
                        </div>
                    </div>
                </div>
                <div class="carousel-item">
                    <div class="row align-items-center">
                        <div class="col-lg-6">
                            <h1 class="hero-title">Run Further.<br>Go Faster.</h1>
                            <p class="hero-subtitle">Experience ultimate comfort with the new Nike Air collection.</p>
                            <a href="products.php?category=Running" class="btn btn-dark">Shop Running</a>
                        </div>
                        <div class="col-lg-6">
                            <img src="assets/images/hero-shoe-2.jpg" alt="Nike Running Shoes" class="img-fluid rounded">
                        </div>
                    </div>
                </div>
                <div class="carousel-item">
                    <div class="row align-items-center">
                        <div class="col-lg-6">
                            <h1 class="hero-title">Court Ready.<br>Game On.</h1>
                            <p class="hero-subtitle">Dominate the game with the latest Nike basketball shoes.</p>
                            <a href="products.php?category=Basketball" class="btn btn-dark">Shop Basketball</a>
                        </div>
                        <div class="col-lg-6">
                            <img src="assets/images/hero-shoe-3.jpg" alt="Nike Basketball Shoes" class="img-fluid rounded">
                        </div>
                    </div>
                </div>
            </div>
            <button class="carousel-control-prev" type="button" data-bs-target="#heroCarousel" data-bs-slide="prev">
                <span class="carousel-control-prev-icon bg-dark rounded" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#heroCarousel" data-bs-slide="next">
                <span class="carousel-control-next-icon bg-dark rounded" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
            </button>
        </div>
    </div>
</section>

<!-- Featured Products Section -->
<section class="container mb-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Featured Products</h2>
        <a href="products.php" class="btn btn-outline-dark btn-sm">View All</a>
    </div>
    
    <div class="row">
        <?php while ($product = mysqli_fetch_assoc($featured_result)): ?>
            <div class="col-6 col-md-3">
                <div class="card product-card h-100">
                    <?php if ($product['sale_price']): ?>
                        <div class="badge-sale">Sale</div>
                    <?php endif; ?>
                    <a href="product.php?id=<?php echo $product['id']; ?>">
                        <img src="uploads/<?php echo $product['image']; ?>" class="card-img-top" alt="<?php echo $product['name']; ?>">
                        <div class="product-quick-view">
                            <i class="fas fa-eye"></i> Quick View
                        </div>
                    </a>
                    <div class="card-body">
                        <p class="product-category"><?php echo $product['category']; ?></p>
                        <h5 class="product-title">
                            <a href="product.php?id=<?php echo $product['id']; ?>" class="text-decoration-none text-dark">
                                <?php echo $product['name']; ?>
                            </a>
                        </h5>
                        <div class="d-flex align-items-center">
                            <?php if ($product['sale_price']): ?>
                                <span class="product-price">$<?php echo number_format($product['sale_price'], 2); ?></span>
                                <span class="product-price-old">$<?php echo number_format($product['price'], 2); ?></span>
                            <?php else: ?>
                                <span class="product-price">$<?php echo number_format($product['price'], 2); ?></span>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="card-footer bg-white border-top-0">
                        <a href="#" class="btn btn-dark w-100 add-to-cart" data-product-id="<?php echo $product['id']; ?>">Add to Cart</a>
                    </div>
                </div>
            </div>
        <?php endwhile; ?>
    </div>
</section>

<!-- Features Banner -->
<section class="container-fluid bg-dark text-white py-4 mb-5">
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
                    <p class="mb-0 small">Dedicated customer service</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Sale Products Section -->
<section class="container mb-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>On Sale</h2>
        <a href="products.php?sale=1" class="btn btn-outline-dark btn-sm">View All Sales</a>
    </div>
    
    <div class="row">
        <?php while ($product = mysqli_fetch_assoc($sale_result)): ?>
            <div class="col-6 col-md-3">
                <div class="card product-card h-100">
                    <div class="badge-sale">Sale</div>
                    <a href="product.php?id=<?php echo $product['id']; ?>">
                        <img src="uploads/<?php echo $product['image']; ?>" class="card-img-top" alt="<?php echo $product['name']; ?>">
                        <div class="product-quick-view">
                            <i class="fas fa-eye"></i> Quick View
                        </div>
                    </a>
                    <div class="card-body">
                        <p class="product-category"><?php echo $product['category']; ?></p>
                        <h5 class="product-title">
                            <a href="product.php?id=<?php echo $product['id']; ?>" class="text-decoration-none text-dark">
                                <?php echo $product['name']; ?>
                            </a>
                        </h5>
                        <div class="d-flex align-items-center">
                            <span class="product-price">$<?php echo number_format($product['sale_price'], 2); ?></span>
                            <span class="product-price-old">$<?php echo number_format($product['price'], 2); ?></span>
                        </div>
                    </div>
                    <div class="card-footer bg-white border-top-0">
                        <a href="#" class="btn btn-dark w-100 add-to-cart" data-product-id="<?php echo $product['id']; ?>">Add to Cart</a>
                    </div>
                </div>
            </div>
        <?php endwhile; ?>
    </div>
</section>

<!-- Categories Section -->
<section class="container mb-5">
    <h2 class="mb-4">Shop by Category</h2>
    <div class="row">
        <div class="col-6 col-md-3 mb-4">
            <a href="products.php?category=Running" class="text-decoration-none">
                <div class="card h-100">
                    <img src="assets/images/category-running.jpg" class="card-img-top" alt="Running Shoes">
                    <div class="card-body text-center">
                        <h5 class="mb-0">Running</h5>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-6 col-md-3 mb-4">
            <a href="products.php?category=Basketball" class="text-decoration-none">
                <div class="card h-100">
                    <img src="assets/images/category-basketball.jpg" class="card-img-top" alt="Basketball Shoes">
                    <div class="card-body text-center">
                        <h5 class="mb-0">Basketball</h5>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-6 col-md-3 mb-4">
            <a href="products.php?category=Training" class="text-decoration-none">
                <div class="card h-100">
                    <img src="assets/images/category-training.jpg" class="card-img-top" alt="Training Shoes">
                    <div class="card-body text-center">
                        <h5 class="mb-0">Training</h5>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-6 col-md-3 mb-4">
            <a href="products.php?category=Casual" class="text-decoration-none">
                <div class="card h-100">
                    <img src="assets/images/category-casual.jpg" class="card-img-top" alt="Casual Shoes">
                    <div class="card-body text-center">
                        <h5 class="mb-0">Casual</h5>
                    </div>
                </div>
            </a>
        </div>
    </div>
</section>

<!-- Testimonials Section -->
<section class="py-5 bg-light mb-5">
    <div class="container">
        <h2 class="text-center mb-5">What Our Customers Say</h2>
        <div class="row">
            <div class="col-md-4 mb-4 mb-md-0">
                <div class="card border-0 h-100 shadow-sm">
                    <div class="card-body p-4">
                        <div class="mb-3 text-warning">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                        </div>
                        <p class="card-text mb-4">"These Nike Air Max are the most comfortable shoes I've ever owned. I wear them for running and daily use, and they still look brand new after 6 months."</p>
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <img src="https://randomuser.me/api/portraits/men/32.jpg" alt="Customer" width="50" height="50" class="rounded-circle">
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h6 class="mb-0">John Cooper</h6>
                                <div class="text-muted">Running Enthusiast</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-4 mb-md-0">
                <div class="card border-0 h-100 shadow-sm">
                    <div class="card-body p-4">
                        <div class="mb-3 text-warning">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                        </div>
                        <p class="card-text mb-4">"I bought the Nike Revolution for my daily workouts and I'm impressed with the quality. Great ankle support and they're incredibly lightweight. Highly recommend!"</p>
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <img src="https://randomuser.me/api/portraits/women/44.jpg" alt="Customer" width="50" height="50" class="rounded-circle">
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h6 class="mb-0">Sarah Williams</h6>
                                <div class="text-muted">Fitness Trainer</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border-0 h-100 shadow-sm">
                    <div class="card-body p-4">
                        <div class="mb-3 text-warning">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star-half-alt"></i>
                        </div>
                        <p class="card-text mb-4">"The shipping was super fast and the customer service was exceptional. When I had sizing issues, they helped me exchange them without any hassle. Will shop here again."</p>
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <img src="https://randomuser.me/api/portraits/men/67.jpg" alt="Customer" width="50" height="50" class="rounded-circle">
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h6 class="mb-0">Michael Johnson</h6>
                                <div class="text-muted">Basketball Player</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include_once "includes/footer.php"; ?>
