<?php
include_once "includes/header.php";

// Check if product ID is provided
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: products.php");
    exit;
}

$product_id = intval($_GET['id']);

// Get product details
$query = "SELECT * FROM products WHERE id = $product_id";
$result = mysqli_query($conn, $query);

if (mysqli_num_rows($result) == 0) {
    header("Location: products.php");
    exit;
}

$product = mysqli_fetch_assoc($result);

// Get related products
$category = mysqli_real_escape_string($conn, $product['category']);
$related_query = "SELECT * FROM products WHERE category = '$category' AND id != $product_id AND stock > 0 LIMIT 4";
$related_result = mysqli_query($conn, $related_query);
?>

<div class="container py-4">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="index.php">Home</a></li>
            <li class="breadcrumb-item"><a href="products.php">Products</a></li>
            <li class="breadcrumb-item"><a href="products.php?category=<?php echo urlencode($product['category']); ?>"><?php echo htmlspecialchars($product['category']); ?></a></li>
            <li class="breadcrumb-item active" aria-current="page"><?php echo htmlspecialchars($product['name']); ?></li>
        </ol>
    </nav>
    
    <div class="row mb-5">
        <!-- Product Image -->
        <div class="col-md-6 mb-4">
            <img src="uploads/<?php echo $product['image']; ?>" alt="<?php echo htmlspecialchars($product['name']); ?>" class="img-fluid product-detail-img">
        </div>
        
        <!-- Product Info -->
        <div class="col-md-6">
            <h1 class="product-detail-title"><?php echo htmlspecialchars($product['name']); ?></h1>
            <p class="text-muted mb-3"><?php echo htmlspecialchars($product['category']); ?></p>
            
            <!-- Product Price -->
            <div class="mb-4">
                <?php if ($product['sale_price']): ?>
                    <span class="product-detail-price">$<?php echo number_format($product['sale_price'], 2); ?></span>
                    <span class="text-muted text-decoration-line-through ms-2">$<?php echo number_format($product['price'], 2); ?></span>
                    <?php
                    $discount = round((($product['price'] - $product['sale_price']) / $product['price']) * 100);
                    ?>
                    <span class="badge bg-danger ms-2">Save <?php echo $discount; ?>%</span>
                <?php else: ?>
                    <span class="product-detail-price">$<?php echo number_format($product['price'], 2); ?></span>
                <?php endif; ?>
            </div>
            
            <!-- Stock Status -->
            <div class="mb-4">
                <?php if ($product['stock'] > 0): ?>
                    <span class="badge bg-success">In Stock</span>
                <?php else: ?>
                    <span class="badge bg-danger">Out of Stock</span>
                <?php endif; ?>
            </div>
            
            <!-- Product Description -->
            <div class="mb-4">
                <h5>Description</h5>
                <p><?php echo nl2br(htmlspecialchars($product['description'])); ?></p>
            </div>
            
            <!-- Quantity Selector and Add to Cart -->
            <?php if ($product['stock'] > 0): ?>
                <form class="mb-4">
                    <div class="row">
                        <div class="col-md-4">
                            <label for="quantity" class="form-label">Quantity</label>
                            <div class="quantity-selector">
                                <button type="button" class="btn-minus">-</button>
                                <input type="number" id="quantity" name="quantity" value="1" min="1" max="<?php echo $product['stock']; ?>" readonly>
                                <button type="button" class="btn-plus">+</button>
                            </div>
                        </div>
                    </div>
                    <button type="button" class="btn btn-dark btn-lg mt-3 add-to-cart" data-product-id="<?php echo $product['id']; ?>">
                        <i class="fas fa-shopping-cart me-2"></i> Add to Cart
                    </button>
                </form>
            <?php endif; ?>
            
            <!-- Additional Info -->
            <div class="mb-4">
                <div class="accordion" id="productAccordion">
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="headingShipping">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseShipping" aria-expanded="false" aria-controls="collapseShipping">
                                Shipping & Returns
                            </button>
                        </h2>
                        <div id="collapseShipping" class="accordion-collapse collapse" aria-labelledby="headingShipping" data-bs-parent="#productAccordion">
                            <div class="accordion-body">
                                <p>Free standard shipping on orders over $50.</p>
                                <p>Free returns within 30 days of delivery.</p>
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="headingSize">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseSize" aria-expanded="false" aria-controls="collapseSize">
                                Size & Fit
                            </button>
                        </h2>
                        <div id="collapseSize" class="accordion-collapse collapse" aria-labelledby="headingSize" data-bs-parent="#productAccordion">
                            <div class="accordion-body">
                                <p>Fits true to size, take your normal size.</p>
                                <p>If you're between sizes, order the next size up.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Related Products -->
    <?php if (mysqli_num_rows($related_result) > 0): ?>
        <section>
            <h3 class="mb-4">You May Also Like</h3>
            <div class="row">
                <?php while ($related = mysqli_fetch_assoc($related_result)): ?>
                    <div class="col-6 col-md-3 mb-4">
                        <div class="card product-card h-100">
                            <?php if ($related['sale_price']): ?>
                                <div class="badge-sale">Sale</div>
                            <?php endif; ?>
                            <a href="product.php?id=<?php echo $related['id']; ?>">
                                <img src="uploads/<?php echo $related['image']; ?>" class="card-img-top" alt="<?php echo $related['name']; ?>">
                            </a>
                            <div class="card-body">
                                <p class="product-category"><?php echo $related['category']; ?></p>
                                <h5 class="product-title">
                                    <a href="product.php?id=<?php echo $related['id']; ?>" class="text-decoration-none text-dark">
                                        <?php echo $related['name']; ?>
                                    </a>
                                </h5>
                                <div class="d-flex align-items-center">
                                    <?php if ($related['sale_price']): ?>
                                        <span class="product-price">$<?php echo number_format($related['sale_price'], 2); ?></span>
                                        <span class="product-price-old">$<?php echo number_format($related['price'], 2); ?></span>
                                    <?php else: ?>
                                        <span class="product-price">$<?php echo number_format($related['price'], 2); ?></span>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="card-footer bg-white border-top-0">
                                <a href="#" class="btn btn-dark w-100 add-to-cart" data-product-id="<?php echo $related['id']; ?>">Add to Cart</a>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        </section>
    <?php endif; ?>
</div>

<?php include_once "includes/footer.php"; ?>
