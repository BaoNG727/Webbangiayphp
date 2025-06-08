<?php if (empty($orders)): ?>
    <div class="container my-5">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <!-- Page Header -->
                <div class="orders-header text-center mb-5">
                    <h1 class="display-4 fw-bold text-dark mb-3">
                        <i class="fas fa-shopping-bag me-3 text-nike-red"></i>My Orders
                    </h1>
                    <p class="lead text-muted">Track your Nike purchases and order history</p>
                </div>
                
                <!-- Empty State -->
                <div class="empty-orders-state bg-white rounded-4 shadow-lg p-5 text-center">
                    <div class="empty-state-icon mb-4">
                        <div class="empty-icon-container">
                            <i class="fas fa-shopping-bag empty-bag-icon"></i>
                            <div class="empty-sparkles">
                                <i class="fas fa-sparkles sparkle sparkle-1"></i>
                                <i class="fas fa-sparkles sparkle sparkle-2"></i>
                                <i class="fas fa-sparkles sparkle sparkle-3"></i>
                            </div>
                        </div>
                    </div>
                    <h3 class="fw-bold text-dark mb-3">Your Order Journey Starts Here</h3>
                    <p class="text-muted mb-4 px-3">
                        Ready to step into something amazing? Browse our collection of premium Nike footwear 
                        and start your first order today.
                    </p>
                    <div class="empty-state-features mb-4">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <div class="feature-item">
                                    <i class="fas fa-truck text-nike-red mb-2"></i>
                                    <small class="d-block text-muted">Free Shipping</small>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="feature-item">
                                    <i class="fas fa-shield-check text-nike-red mb-2"></i>
                                    <small class="d-block text-muted">Authentic Products</small>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="feature-item">
                                    <i class="fas fa-undo text-nike-red mb-2"></i>
                                    <small class="d-block text-muted">Easy Returns</small>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="empty-state-actions">
                        <a href="/Webgiay/products" class="btn btn-dark btn-lg px-5 py-3 rounded-pill">
                            <i class="fas fa-shopping-bag me-2"></i>
                            Start Shopping
                            <i class="fas fa-arrow-right ms-2"></i>
                        </a>
                        <div class="mt-3">
                            <a href="/Webgiay/products?category=Running" class="btn btn-outline-secondary me-2 rounded-pill">
                                <i class="fas fa-running me-1"></i>Running
                            </a>
                            <a href="/Webgiay/products?category=Basketball" class="btn btn-outline-secondary me-2 rounded-pill">
                                <i class="fas fa-basketball-ball me-1"></i>Basketball
                            </a>
                            <a href="/Webgiay/products?sale=1" class="btn btn-outline-danger rounded-pill">
                                <i class="fas fa-tag me-1"></i>Sale
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php else: ?>    <div class="container my-5">
        <!-- Page Header with Stats -->
        <div class="orders-header mb-5">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <h1 class="display-4 fw-bold text-dark mb-3">
                        <i class="fas fa-shopping-bag me-3 text-nike-red"></i>My Orders
                    </h1>
                    <p class="lead text-muted mb-0">Track your Nike purchases and delivery status</p>
                </div>
                <div class="col-lg-6">
                    <div class="orders-stats-mini">
                        <div class="row g-3">
                            <div class="col-6 col-sm-3">
                                <div class="stat-mini text-center">
                                    <div class="stat-number text-dark fw-bold"><?= count($orders) ?></div>
                                    <div class="stat-label text-muted small">Total Orders</div>
                                </div>
                            </div>
                            <div class="col-6 col-sm-3">
                                <div class="stat-mini text-center">
                                    <div class="stat-number text-success fw-bold">
                                        <?= count(array_filter($orders, function($o) { return $o['status'] === 'delivered'; })) ?>
                                    </div>
                                    <div class="stat-label text-muted small">Delivered</div>
                                </div>
                            </div>
                            <div class="col-6 col-sm-3">
                                <div class="stat-mini text-center">
                                    <div class="stat-number text-info fw-bold">
                                        <?= count(array_filter($orders, function($o) { return in_array($o['status'], ['pending', 'processing', 'shipped']); })) ?>
                                    </div>
                                    <div class="stat-label text-muted small">In Progress</div>
                                </div>
                            </div>
                            <div class="col-6 col-sm-3">
                                <div class="stat-mini text-center">
                                    <div class="stat-number text-dark fw-bold">
                                        <?= array_sum(array_map(function($o) { return $o['item_count'] ?? 0; }, $orders)) ?>
                                    </div>
                                    <div class="stat-label text-muted small">Items</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Orders Filter Tabs -->
        <div class="orders-filter-tabs mb-4">
            <ul class="nav nav-pills justify-content-center" id="orderTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="all-orders-tab" data-bs-toggle="pill" data-bs-target="#all-orders" type="button" role="tab">
                        <i class="fas fa-list me-2"></i>All Orders
                        <span class="badge bg-secondary ms-2"><?= count($orders) ?></span>
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="in-progress-tab" data-bs-toggle="pill" data-bs-target="#in-progress" type="button" role="tab">
                        <i class="fas fa-clock me-2"></i>In Progress
                        <span class="badge bg-info ms-2">
                            <?= count(array_filter($orders, function($o) { return in_array($o['status'], ['pending', 'processing', 'shipped']); })) ?>
                        </span>
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="delivered-tab" data-bs-toggle="pill" data-bs-target="#delivered" type="button" role="tab">
                        <i class="fas fa-check-circle me-2"></i>Delivered
                        <span class="badge bg-success ms-2">
                            <?= count(array_filter($orders, function($o) { return $o['status'] === 'delivered'; })) ?>
                        </span>
                    </button>
                </li>
            </ul>
        </div>

        <!-- Orders Content -->
        <div class="tab-content" id="orderTabsContent">
            <!-- All Orders Tab -->
            <div class="tab-pane fade show active" id="all-orders" role="tabpanel">
                <div class="orders-grid">
                    <?php foreach ($orders as $order): ?>
                        <?php include 'order_card.php'; ?>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- In Progress Tab -->
            <div class="tab-pane fade" id="in-progress" role="tabpanel">
                <div class="orders-grid">
                    <?php foreach (array_filter($orders, function($o) { return in_array($o['status'], ['pending', 'processing', 'shipped']); }) as $order): ?>
                        <?php include 'order_card.php'; ?>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Delivered Tab -->
            <div class="tab-pane fade" id="delivered" role="tabpanel">
                <div class="orders-grid">
                    <?php foreach (array_filter($orders, function($o) { return $o['status'] === 'delivered'; }) as $order): ?>
                        <?php include 'order_card.php'; ?>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

        <!-- Quick Actions Footer -->
        <div class="orders-footer mt-5 pt-4 border-top">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <div class="quick-actions">
                        <h6 class="fw-bold text-dark mb-3">Need Help?</h6>
                        <div class="d-flex flex-wrap gap-2">
                            <a href="/Webgiay/contact" class="btn btn-outline-secondary btn-sm rounded-pill">
                                <i class="fas fa-headset me-1"></i>Contact Support
                            </a>
                            <a href="#" class="btn btn-outline-secondary btn-sm rounded-pill">
                                <i class="fas fa-question-circle me-1"></i>Track Order
                            </a>
                            <a href="#" class="btn btn-outline-secondary btn-sm rounded-pill">
                                <i class="fas fa-undo me-1"></i>Return Policy
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 text-lg-end">
                    <div class="continue-shopping">
                        <p class="text-muted mb-2">Want to explore more?</p>
                        <a href="/Webgiay/products" class="btn btn-dark btn-lg rounded-pill">
                            <i class="fas fa-shopping-bag me-2"></i>
                            Continue Shopping
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>
