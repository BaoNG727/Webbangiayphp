<!-- Individual Order Card Component -->
<div class="order-card bg-white rounded-3 shadow-sm border border-light mb-4 position-relative overflow-hidden" data-order-id="<?= $order['id'] ?>">
    <!-- Order Status Progress Bar -->
    <div class="order-progress-bar">
        <?php
        $statuses = ['pending', 'processing', 'shipped', 'delivered'];
        $currentStatusIndex = array_search($order['status'], $statuses);
        $progressPercentage = $currentStatusIndex !== false ? (($currentStatusIndex + 1) / count($statuses)) * 100 : 25;
        ?>
        <div class="progress-track">
            <div class="progress-fill" style="width: <?= $progressPercentage ?>%"></div>
        </div>
    </div>

    <div class="card-body p-4">
        <!-- Order Header -->
        <div class="order-header d-flex justify-content-between align-items-start mb-4">
            <div class="order-info">
                <div class="d-flex align-items-center mb-2">
                    <h5 class="order-number fw-bold text-dark mb-0 me-3">
                        #<?= str_pad($order['id'], 6, '0', STR_PAD_LEFT) ?>
                    </h5>
                    <?php
                    $statusConfig = [
                        'pending' => ['color' => 'warning', 'icon' => 'clock', 'text' => 'Order Placed'],
                        'processing' => ['color' => 'info', 'icon' => 'cogs', 'text' => 'Processing'],
                        'shipped' => ['color' => 'primary', 'icon' => 'truck', 'text' => 'On the Way'],
                        'delivered' => ['color' => 'success', 'icon' => 'check-circle', 'text' => 'Delivered']
                    ];
                    $status = $statusConfig[$order['status']] ?? $statusConfig['pending'];
                    ?>
                    <span class="badge bg-<?= $status['color'] ?> bg-gradient px-3 py-2 rounded-pill">
                        <i class="fas fa-<?= $status['icon'] ?> me-1"></i>
                        <?= $status['text'] ?>
                    </span>
                </div>
                <p class="order-date text-muted small mb-0">
                    <i class="far fa-calendar me-1"></i>
                    Ordered on <?= date('M j, Y \a\t g:i A', strtotime($order['created_at'])) ?>
                </p>
            </div>
            <div class="order-total text-end">
                <div class="total-amount h4 fw-bold text-dark mb-1">
                    $<?= number_format($order['total'] ?? $order['total_amount'] ?? 0, 2) ?>
                </div>
                <div class="item-count text-muted small">
                    <?= ($order['item_count'] ?? 0) ?> item<?= ($order['item_count'] ?? 0) !== 1 ? 's' : '' ?>
                </div>
            </div>
        </div>

        <!-- Order Status Timeline -->
        <div class="order-timeline mb-4">
            <div class="timeline-container">
                <?php foreach ($statuses as $index => $status): ?>
                    <?php
                    $isActive = $index <= $currentStatusIndex;
                    $isCurrent = $index === $currentStatusIndex;
                    $timelineConfig = [
                        'pending' => ['icon' => 'shopping-cart', 'title' => 'Order Placed', 'desc' => 'We received your order'],
                        'processing' => ['icon' => 'cogs', 'title' => 'Processing', 'desc' => 'Preparing your items'],
                        'shipped' => ['icon' => 'truck', 'title' => 'Shipped', 'desc' => 'On the way to you'],
                        'delivered' => ['icon' => 'home', 'title' => 'Delivered', 'desc' => 'Order completed']
                    ];
                    $config = $timelineConfig[$status];
                    ?>
                    <div class="timeline-step <?= $isActive ? 'active' : '' ?> <?= $isCurrent ? 'current' : '' ?>">
                        <div class="step-icon">
                            <i class="fas fa-<?= $config['icon'] ?>"></i>
                        </div>
                        <div class="step-content">
                            <div class="step-title"><?= $config['title'] ?></div>
                            <div class="step-desc"><?= $config['desc'] ?></div>
                        </div>
                        <?php if ($index < count($statuses) - 1): ?>
                            <div class="step-connector <?= $index < $currentStatusIndex ? 'completed' : '' ?>"></div>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Order Details Grid -->
        <div class="order-details-grid mb-4">
            <div class="row g-3">
                <div class="col-md-6">
                    <div class="detail-card p-3 bg-light rounded-3">
                        <div class="detail-header d-flex align-items-center mb-2">
                            <i class="fas fa-map-marker-alt text-nike-red me-2"></i>
                            <span class="detail-title fw-semibold">Shipping Address</span>
                        </div>                        <div class="detail-content text-muted small">
                            <?= htmlspecialchars(isset($order['shipping_address']) ? $order['shipping_address'] : ($order['name'] . "\n" . $order['address'] . "\n" . $order['city'] . "\n" . $order['phone'])) ?>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="detail-card p-3 bg-light rounded-3">
                        <div class="detail-header d-flex align-items-center mb-2">
                            <i class="fas fa-credit-card text-nike-red me-2"></i>
                            <span class="detail-title fw-semibold">Payment Method</span>
                        </div>                        <div class="detail-content text-muted small">
                            <?php
                            $paymentMethods = [
                                'credit_card' => 'Credit Card',
                                'paypal' => 'PayPal',
                                'bank_transfer' => 'Bank Transfer',
                                'cash_on_delivery' => 'Cash on Delivery'
                            ];
                            
                            // Extract payment method from notes field or use fallback
                            $paymentMethod = 'N/A';
                            if (isset($order['payment_method'])) {
                                $paymentMethod = $paymentMethods[$order['payment_method']] ?? ucfirst(str_replace('_', ' ', $order['payment_method']));
                            } elseif (isset($order['notes']) && strpos($order['notes'], 'Payment method:') !== false) {
                                // Extract from notes field
                                $noteParts = explode('Payment method:', $order['notes']);
                                if (count($noteParts) > 1) {
                                    $extractedMethod = trim($noteParts[1]);
                                    $paymentMethod = $paymentMethods[$extractedMethod] ?? ucfirst(str_replace('_', ' ', $extractedMethod));
                                }
                            }
                            echo $paymentMethod;
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Expected Delivery Info -->
        <?php if ($order['status'] !== 'delivered'): ?>
            <div class="delivery-info p-3 bg-gradient bg-primary bg-opacity-10 rounded-3 mb-4">
                <div class="d-flex align-items-center">
                    <div class="delivery-icon me-3">
                        <i class="fas fa-truck text-primary"></i>
                    </div>
                    <div class="delivery-content">
                        <div class="delivery-title fw-semibold text-dark">
                            Expected Delivery
                        </div>
                        <div class="delivery-date text-primary fw-bold">
                            <?= date('l, M j, Y', strtotime($order['created_at'] . ' +5 days')) ?>
                        </div>
                    </div>
                    <?php if ($order['status'] === 'shipped'): ?>
                        <div class="ms-auto">
                            <span class="badge bg-success bg-gradient">
                                <i class="fas fa-truck me-1"></i>
                                En Route
                            </span>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        <?php else: ?>
            <div class="delivery-info p-3 bg-gradient bg-success bg-opacity-10 rounded-3 mb-4">
                <div class="d-flex align-items-center">
                    <div class="delivery-icon me-3">
                        <i class="fas fa-check-circle text-success"></i>
                    </div>
                    <div class="delivery-content">
                        <div class="delivery-title fw-semibold text-dark">
                            Successfully Delivered
                        </div>
                        <div class="delivery-date text-success fw-bold">
                            Thank you for choosing Nike!
                        </div>
                    </div>
                    <div class="ms-auto">
                        <span class="badge bg-success bg-gradient">
                            <i class="fas fa-star me-1"></i>
                            Complete
                        </span>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <!-- Order Actions -->
        <div class="order-actions d-flex flex-wrap gap-2 justify-content-between align-items-center pt-3 border-top">
            <div class="action-buttons d-flex flex-wrap gap-2">
                <a href="/Webgiay/orders/<?= $order['id'] ?>" 
                   class="btn btn-dark btn-sm rounded-pill px-3">
                    <i class="fas fa-eye me-1"></i>
                    View Details
                </a>
                
                <?php if ($order['status'] === 'shipped'): ?>
                    <button class="btn btn-outline-primary btn-sm rounded-pill px-3" onclick="trackOrder(<?= $order['id'] ?>)">
                        <i class="fas fa-map-marked-alt me-1"></i>
                        Track Package
                    </button>
                <?php endif; ?>
                
                <?php if ($order['status'] === 'delivered'): ?>
                    <button class="btn btn-outline-secondary btn-sm rounded-pill px-3" onclick="downloadInvoice(<?= $order['id'] ?>)">
                        <i class="fas fa-download me-1"></i>
                        Invoice
                    </button>
                    <button class="btn btn-outline-warning btn-sm rounded-pill px-3" onclick="reorderItems(<?= $order['id'] ?>)">
                        <i class="fas fa-redo me-1"></i>
                        Reorder
                    </button>
                <?php endif; ?>
            </div>
            
            <?php if (in_array($order['status'], ['pending', 'processing'])): ?>
                <div class="cancel-action">
                    <button class="btn btn-outline-danger btn-sm rounded-pill px-3" onclick="cancelOrder(<?= $order['id'] ?>)">
                        <i class="fas fa-times me-1"></i>
                        Cancel Order
                    </button>
                </div>
            <?php else: ?>
                <div class="additional-actions">
                    <button class="btn btn-outline-secondary btn-sm rounded-pill px-3" onclick="needHelp(<?= $order['id'] ?>)">
                        <i class="fas fa-question-circle me-1"></i>
                        Need Help?
                    </button>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Order Card Hover Effect -->
    <div class="order-card-overlay"></div>
</div>
