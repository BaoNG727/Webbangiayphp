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

<?php include_once "includes/footer.php"; ?>
