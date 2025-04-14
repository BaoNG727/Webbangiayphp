<?php
include_once "includes/header.php";

// Initialize filters
$category = isset($_GET['category']) ? $_GET['category'] : '';
$sale = isset($_GET['sale']) ? true : false;
$sort = isset($_GET['sort']) ? $_GET['sort'] : 'newest';
$search = isset($_GET['search']) ? $_GET['search'] : '';

// Build the query based on filters
$query = "SELECT * FROM products WHERE stock > 0";

if (!empty($category)) {
    $category = mysqli_real_escape_string($conn, $category);
    $query .= " AND category = '$category'";
}

if ($sale) {
    $query .= " AND sale_price IS NOT NULL AND sale_price > 0";
}

if (!empty($search)) {
    $search = mysqli_real_escape_string($conn, $search);
    $query .= " AND (name LIKE '%$search%' OR description LIKE '%$search%' OR category LIKE '%$search%')";
}

// Add sorting
switch ($sort) {
    case 'price_low':
        $query .= " ORDER BY COALESCE(sale_price, price) ASC";
        break;
    case 'price_high':
        $query .= " ORDER BY COALESCE(sale_price, price) DESC";
        break;
    case 'name_asc':
        $query .= " ORDER BY name ASC";
        break;
    case 'name_desc':
        $query .= " ORDER BY name DESC";
        break;
    default: // newest
        $query .= " ORDER BY id DESC";
}

$result = mysqli_query($conn, $query);
$product_count = mysqli_num_rows($result);

// Get all categories for filter
$category_query = "SELECT DISTINCT category FROM products WHERE category IS NOT NULL AND category != '' ORDER BY category";
$category_result = mysqli_query($conn, $category_query);
?>

<div class="container py-4">
    <div class="row">
        <!-- Filters Sidebar -->
        <div class="col-lg-3 mb-4">
            <div class="card">
                <div class="card-header bg-dark text-white">
                    <h5 class="mb-0">Filters</h5>
                </div>
                <div class="card-body">
                    <form action="products.php" method="get" id="filter-form">
                        <?php if (!empty($search)): ?>
                            <input type="hidden" name="search" value="<?php echo htmlspecialchars($search); ?>">
                        <?php endif; ?>
                        
                        <div class="mb-4">
                            <h6>Categories</h6>
                            <div class="form-check">
                                <input class="form-check-input filter-option" type="radio" name="category" id="category-all" value="" <?php echo empty($category) ? 'checked' : ''; ?>>
                                <label class="form-check-label" for="category-all">All Categories</label>
                            </div>
                            <?php while ($cat = mysqli_fetch_assoc($category_result)): ?>
                                <div class="form-check">
                                    <input class="form-check-input filter-option" type="radio" name="category" id="category-<?php echo htmlspecialchars($cat['category']); ?>" value="<?php echo htmlspecialchars($cat['category']); ?>" <?php echo $category == $cat['category'] ? 'checked' : ''; ?>>
                                    <label class="form-check-label" for="category-<?php echo htmlspecialchars($cat['category']); ?>"><?php echo htmlspecialchars($cat['category']); ?></label>
                                </div>
                            <?php endwhile; ?>
                        </div>
                        
                        <div class="mb-4">
                            <h6>Price</h6>
                            <div class="form-check">
                                <input class="form-check-input filter-option" type="checkbox" name="sale" id="sale-only" value="1" <?php echo $sale ? 'checked' : ''; ?>>
                                <label class="form-check-label" for="sale-only">On Sale Only</label>
                            </div>
                        </div>
                        
                        <div class="mb-4">
                            <h6>Sort By</h6>
                            <select name="sort" class="form-select filter-option">
                                <option value="newest" <?php echo $sort == 'newest' ? 'selected' : ''; ?>>Newest</option>
                                <option value="price_low" <?php echo $sort == 'price_low' ? 'selected' : ''; ?>>Price: Low to High</option>
                                <option value="price_high" <?php echo $sort == 'price_high' ? 'selected' : ''; ?>>Price: High to Low</option>
                                <option value="name_asc" <?php echo $sort == 'name_asc' ? 'selected' : ''; ?>>Name: A to Z</option>
                                <option value="name_desc" <?php echo $sort == 'name_desc' ? 'selected' : ''; ?>>Name: Z to A</option>
                            </select>
                        </div>
                        
                        <button type="submit" class="btn btn-dark w-100">Apply Filters</button>
                    </form>
                </div>
            </div>
        </div>
        
        <!-- Products Grid -->
        <div class="col-lg-9">
            <!-- Search and Results Info -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2>
                    <?php if (!empty($search)): ?>
                        Search Results for "<?php echo htmlspecialchars($search); ?>"
                    <?php elseif (!empty($category)): ?>
                        <?php echo htmlspecialchars($category); ?> Shoes
                    <?php else: ?>
                        All Products
                    <?php endif; ?>
                </h2>
                <p><?php echo $product_count; ?> products found</p>
            </div>
            
            <!-- Search Form -->
            <div class="mb-4">
                <form action="products.php" method="get" class="d-flex">
                    <input type="text" name="search" class="form-control me-2" placeholder="Search products..." value="<?php echo htmlspecialchars($search); ?>">
                    <button type="submit" class="btn btn-dark">Search</button>
                </form>
            </div>
            
            <!-- Products -->
            <?php if ($product_count > 0): ?>
                <div class="row">
                    <?php while ($product = mysqli_fetch_assoc($result)): ?>
                        <div class="col-6 col-md-4 col-lg-3 mb-4">
                            <div class="card product-card h-100">
                                <?php if ($product['sale_price']): ?>
                                    <div class="badge-sale">Sale</div>
                                <?php endif; ?>
                                <button class="btn-wishlist" data-product-id="<?php echo $product['id']; ?>" title="Add to wishlist">
                                    <i class="far fa-heart"></i>
                                </button>
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
            <?php else: ?>
                <div class="alert alert-info">No products found. Try different search criteria.</div>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-submit form when filter options change
    const filterOptions = document.querySelectorAll('.filter-option');
    filterOptions.forEach(option => {
        option.addEventListener('change', function() {
            document.getElementById('filter-form').submit();
        });
    });
});
</script>

<?php include_once "includes/footer.php"; ?>
