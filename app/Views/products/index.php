<div class="container py-4">
    <div class="row">
        <!-- Sidebar Filters -->
        <div class="col-lg-3 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Filters</h5>
                </div>
                <div class="card-body">                    <form id="filter-form" method="GET" action="/Webgiay/products">
                        <!-- Hidden input for page reset -->
                        <input type="hidden" name="page" value="1">
                        
                        <!-- Search -->
                        <div class="mb-3">
                            <label class="form-label">Search</label>
                            <input type="text" class="form-control" name="search" 
                                   value="<?php echo htmlspecialchars($current_filters['search'] ?? ''); ?>" 
                                   placeholder="Search products...">
                        </div>
                        
                        <!-- Categories -->
                        <div class="mb-3">
                            <label class="form-label">Category</label>
                            <select class="form-select" name="category">
                                <option value="">All Categories</option>
                                <?php if (!empty($categories)): ?>
                                    <?php foreach ($categories as $cat): ?>
                                        <option value="<?php echo htmlspecialchars($cat['category']); ?>" 
                                                <?php echo ($current_filters['category'] === $cat['category']) ? 'selected' : ''; ?>>
                                            <?php echo htmlspecialchars($cat['category']); ?>
                                        </option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                        </div>
                        
                        <!-- Sale Items -->
                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="sale" value="1" 
                                       <?php echo !empty($current_filters['sale']) ? 'checked' : ''; ?>>
                                <label class="form-check-label">Sale Items Only</label>
                            </div>
                        </div>
                        
                        <!-- Sort -->
                        <div class="mb-3">
                            <label class="form-label">Sort By</label>
                            <select class="form-select" name="sort">
                                <option value="newest" <?php echo ($current_filters['sort'] === 'newest') ? 'selected' : ''; ?>>Newest</option>
                                <option value="price_low" <?php echo ($current_filters['sort'] === 'price_low') ? 'selected' : ''; ?>>Price: Low to High</option>
                                <option value="price_high" <?php echo ($current_filters['sort'] === 'price_high') ? 'selected' : ''; ?>>Price: High to Low</option>
                                <option value="name_asc" <?php echo ($current_filters['sort'] === 'name_asc') ? 'selected' : ''; ?>>Name: A to Z</option>
                                <option value="name_desc" <?php echo ($current_filters['sort'] === 'name_desc') ? 'selected' : ''; ?>>Name: Z to A</option>
                            </select>
                        </div>
                        
                        <button type="submit" class="btn btn-dark w-100">Apply Filters</button>
                        <a href="/Webgiay/products" class="btn btn-outline-secondary w-100 mt-2">Clear Filters</a>
                    </form>
                </div>
            </div>
        </div>        <!-- Products Grid -->
        <div class="col-lg-9">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1>Products</h1>
                <div class="d-flex align-items-center">
                    <span class="text-muted me-3"><?php echo $product_count; ?> products found</span>
                    <?php if ($pagination['total_pages'] > 1): ?>
                        <small class="text-muted">
                            Page <?php echo $pagination['current_page']; ?> of <?php echo $pagination['total_pages']; ?>
                        </small>
                    <?php endif; ?>
                </div>
            </div><?php if (!empty($products)): ?>
                <div class="row">
                    <?php foreach ($products as $product): ?>
                        <div class="col-lg-4 col-md-6 mb-4">
                            <div class="card product-card h-100">
                                <div class="product-image">
                                    <img src="/Webgiay/uploads/<?php echo htmlspecialchars($product['image']); ?>" 
                                         class="card-img-top" alt="<?php echo htmlspecialchars($product['name']); ?>">
                                    <?php if ($product['sale_price'] && $product['sale_price'] > 0): ?>
                                        <span class="badge bg-danger position-absolute top-0 start-0 m-2">Sale</span>
                                    <?php endif; ?>
                                    
                                    <!-- Quick Actions -->
                                    <div class="product-actions">
                                        <a href="/Webgiay/product/<?php echo $product['id']; ?>" 
                                           class="btn btn-sm btn-light" title="View Details">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </div>
                                </div>
                                
                                <div class="card-body d-flex flex-column">
                                    <h5 class="card-title"><?php echo htmlspecialchars($product['name']); ?></h5>
                                    <p class="card-text text-muted small flex-grow-1">
                                        <?php echo htmlspecialchars(substr($product['description'], 0, 100)) . '...'; ?>
                                    </p>
                                    
                                    <div class="price-section mb-2">
                                        <?php if ($product['sale_price'] && $product['sale_price'] > 0): ?>
                                            <span class="price-sale">$<?php echo number_format($product['sale_price'], 2); ?></span>
                                            <span class="price-original">$<?php echo number_format($product['price'], 2); ?></span>
                                        <?php else: ?>
                                            <span class="price">$<?php echo number_format($product['price'], 2); ?></span>
                                        <?php endif; ?>
                                    </div>
                                    
                                    <div class="stock-info mb-3">
                                        <?php if ($product['stock'] > 0): ?>
                                            <small class="text-success">
                                                <i class="fas fa-check-circle"></i> In Stock (<?php echo $product['stock']; ?>)
                                            </small>
                                        <?php else: ?>
                                            <small class="text-danger">
                                                <i class="fas fa-times-circle"></i> Out of Stock
                                            </small>
                                        <?php endif; ?>
                                    </div>
                                    
                                    <div class="mt-auto">
                                        <a href="/Webgiay/product/<?php echo $product['id']; ?>" 
                                           class="btn btn-dark w-100">View Details</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <!-- Pagination -->
                <?php if ($pagination['total_pages'] > 1): ?>
                    <nav aria-label="Product pagination" class="mt-4">
                        <ul class="pagination justify-content-center">
                            <!-- Previous Page -->
                            <?php if ($pagination['has_prev']): ?>
                                <li class="page-item">
                                    <a class="page-link" href="/Webgiay/products?page=<?php echo $pagination['prev_page']; ?><?php echo $pagination['query_string'] ? '&' . $pagination['query_string'] : ''; ?>">
                                        <i class="fas fa-chevron-left"></i> Previous
                                    </a>
                                </li>
                            <?php else: ?>
                                <li class="page-item disabled">
                                    <span class="page-link"><i class="fas fa-chevron-left"></i> Previous</span>
                                </li>
                            <?php endif; ?>

                            <!-- Page Numbers -->
                            <?php
                            $start_page = max(1, $pagination['current_page'] - 2);
                            $end_page = min($pagination['total_pages'], $pagination['current_page'] + 2);
                            
                            // Always show first page
                            if ($start_page > 1): ?>
                                <li class="page-item">
                                    <a class="page-link" href="/Webgiay/products?page=1<?php echo $pagination['query_string'] ? '&' . $pagination['query_string'] : ''; ?>">1</a>
                                </li>
                                <?php if ($start_page > 2): ?>
                                    <li class="page-item disabled">
                                        <span class="page-link">...</span>
                                    </li>
                                <?php endif; ?>
                            <?php endif; ?>

                            <!-- Current page range -->
                            <?php for ($i = $start_page; $i <= $end_page; $i++): ?>
                                <li class="page-item <?php echo ($i == $pagination['current_page']) ? 'active' : ''; ?>">
                                    <a class="page-link" href="/Webgiay/products?page=<?php echo $i; ?><?php echo $pagination['query_string'] ? '&' . $pagination['query_string'] : ''; ?>">
                                        <?php echo $i; ?>
                                    </a>
                                </li>
                            <?php endfor; ?>

                            <!-- Always show last page -->
                            <?php if ($end_page < $pagination['total_pages']): ?>
                                <?php if ($end_page < $pagination['total_pages'] - 1): ?>
                                    <li class="page-item disabled">
                                        <span class="page-link">...</span>
                                    </li>
                                <?php endif; ?>
                                <li class="page-item">
                                    <a class="page-link" href="/Webgiay/products?page=<?php echo $pagination['total_pages']; ?><?php echo $pagination['query_string'] ? '&' . $pagination['query_string'] : ''; ?>">
                                        <?php echo $pagination['total_pages']; ?>
                                    </a>
                                </li>
                            <?php endif; ?>

                            <!-- Next Page -->
                            <?php if ($pagination['has_next']): ?>
                                <li class="page-item">
                                    <a class="page-link" href="/Webgiay/products?page=<?php echo $pagination['next_page']; ?><?php echo $pagination['query_string'] ? '&' . $pagination['query_string'] : ''; ?>">
                                        Next <i class="fas fa-chevron-right"></i>
                                    </a>
                                </li>
                            <?php else: ?>
                                <li class="page-item disabled">
                                    <span class="page-link">Next <i class="fas fa-chevron-right"></i></span>
                                </li>
                            <?php endif; ?>
                        </ul>
                    </nav>

                    <!-- Pagination Info -->
                    <div class="text-center mt-3">
                        <small class="text-muted">
                            Showing <?php echo (($pagination['current_page'] - 1) * $pagination['per_page']) + 1; ?> 
                            to <?php echo min($pagination['current_page'] * $pagination['per_page'], $pagination['total_items']); ?> 
                            of <?php echo $pagination['total_items']; ?> products
                        </small>
                    </div>
                <?php endif; ?>
            <?php else: ?>
                <div class="text-center py-5">
                    <i class="fas fa-search fa-3x text-muted mb-3"></i>
                    <h3>No products found</h3>
                    <p class="text-muted">Try adjusting your filters or search terms.</p>
                    <a href="/Webgiay/products" class="btn btn-dark">View All Products</a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Add to Cart Modal -->
<div class="modal fade" id="addToCartModal" tabindex="-1" aria-labelledby="addToCartModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addToCartModalLabel">Add to Cart</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="cart-message"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <a href="/Webgiay/cart" class="btn btn-dark">View Cart</a>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Add to cart functionality
    const addToCartButtons = document.querySelectorAll('.add-to-cart-btn');
    
    addToCartButtons.forEach(button => {
        button.addEventListener('click', function() {
            const productId = this.getAttribute('data-product-id');
            
            fetch('/Webgiay/product/add-to-cart', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `product_id=${productId}&quantity=1`
            })
            .then(response => response.json())
            .then(data => {
                const messageDiv = document.getElementById('cart-message');
                
                if (data.success) {
                    messageDiv.innerHTML = `<div class="alert alert-success">${data.message}</div>`;
                    
                    // Update cart count in navbar
                    const cartBadge = document.querySelector('.navbar .badge');
                    if (cartBadge) {
                        cartBadge.textContent = data.cart_count;
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
    });
});
</script>
