<?php if (!$order): ?>
    <div class="order-detail-container">
        <div class="empty-state-card">
            <div class="empty-icon">
                <svg class="w-16 h-16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                          d="M9.172 16.172a4 4 0 015.656 0M9 12h6m-6-4h6m2 5.291A7.962 7.962 0 0112 15c-2.34 0-4.29-1.29-5.324-3.209M7.76 7.76l8.48 8.48" />
                </svg>
            </div>
            <h1>Order Not Found</h1>
            <p>The order you're looking for doesn't exist or you don't have permission to view it.</p>
            <a href="/Webgiay/orders" class="nike-btn nike-btn-primary">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Back to Orders
            </a>
        </div>
    </div>
<?php else: ?>
    <div class="order-detail-container">
        <!-- Header Section -->
        <div class="order-detail-header">
            <!-- Breadcrumb -->
            <nav class="order-breadcrumb">
                <a href="/Webgiay/" class="breadcrumb-link">Home</a>
                <span class="breadcrumb-separator">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </span>
                <a href="/Webgiay/orders" class="breadcrumb-link">My Orders</a>
                <span class="breadcrumb-separator">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </span>
                <span class="breadcrumb-current">Order #<?= str_pad($order['id'], 6, '0', STR_PAD_LEFT) ?></span>
            </nav>

            <!-- Order Header Info -->
            <div class="order-header-info">
                <div class="order-title-section">
                    <h1 class="order-title">Order #<?= str_pad($order['id'], 6, '0', STR_PAD_LEFT) ?></h1>
                    <p class="order-date">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                  d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        Placed on <?= date('F j, Y \a\t g:i A', strtotime($order['created_at'])) ?>
                    </p>
                </div>
                
                <div class="order-status-section">
                    <div class="order-status order-status-<?= $order['status'] ?>">
                        <div class="status-icon">
                            <?php if ($order['status'] === 'pending'): ?>
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            <?php elseif ($order['status'] === 'processing'): ?>
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                            <?php elseif ($order['status'] === 'shipped'): ?>
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                                </svg>                            <?php elseif ($order['status'] === 'delivered'): ?>
                            <?php endif; ?>
                        </div>
                        <span><?= ucfirst($order['status']) ?></span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Progress Timeline -->        <div class="order-progress-card">            <h3 class="progress-title">
                Order Progress
            </h3>
            
            <div class="progress-timeline">
                <?php
                $statuses = [
                    'pending' => ['label' => 'Order Placed', 'icon' => 'M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z'],
                    'processing' => ['label' => 'Processing', 'icon' => 'M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z'],
                    'shipped' => ['label' => 'Shipped', 'icon' => 'M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4'],
                    'delivered' => ['label' => 'Delivered', 'icon' => 'M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4']
                ];
                
                $currentIndex = array_search($order['status'], array_keys($statuses));
                $isCompleted = function($index) use ($currentIndex) {
                    return $index <= $currentIndex;
                };
                $isCurrent = function($index) use ($currentIndex) {
                    return $index === $currentIndex;
                };
                ?>
                
                <?php foreach ($statuses as $index => $statusInfo): ?>
                    <?php $stepIndex = array_search($index, array_keys($statuses)); ?>
                    <div class="timeline-step <?= $isCompleted($stepIndex) ? 'completed' : '' ?> <?= $isCurrent($stepIndex) ? 'current' : '' ?>">
                        <div class="timeline-icon">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="<?= $statusInfo['icon'] ?>" />
                            </svg>
                        </div>
                        <div class="timeline-content">
                            <span class="timeline-label"><?= $statusInfo['label'] ?></span>
                            <?php if ($isCompleted($stepIndex)): ?>
                                <span class="timeline-date"><?= date('M j, Y', strtotime($order['created_at'])) ?></span>
                            <?php endif; ?>
                        </div>
                        <?php if ($stepIndex < count($statuses) - 1): ?>
                            <div class="timeline-connector <?= $isCompleted($stepIndex) ? 'completed' : '' ?>"></div>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="order-actions-card">
            <div class="action-buttons">
                <button class="nike-btn nike-btn-secondary" onclick="downloadInvoice(<?= $order['id'] ?>)">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    Download Invoice
                </button>
                
                <?php if ($order['status'] === 'shipped'): ?>
                    <button class="nike-btn nike-btn-accent" onclick="trackPackage('<?= $order['id'] ?>')">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        Track Package
                    </button>
                <?php endif; ?>
                
                <?php if (in_array($order['status'], ['pending', 'processing'])): ?>
                    <button class="nike-btn nike-btn-danger" onclick="cancelOrder(<?= $order['id'] ?>)">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                        Cancel Order
                    </button>
                <?php endif; ?>
                
                <button class="nike-btn nike-btn-secondary" onclick="reorder()">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                    </svg>
                    Reorder
                </button>
            </div>
        </div>

        <!-- Main Content -->
        <div class="order-content-grid">
            <!-- Order Items -->
            <div class="order-items-section">
                <div class="order-card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                            </svg>
                            Order Items
                        </h3>
                        <span class="items-count"><?= isset($order['items']) ? count($order['items']) : 0 ?> item(s)</span>
                    </div>
                    
                    <div class="items-container">
                        <?php if (isset($order['items']) && count($order['items']) > 0): ?>
                            <?php foreach ($order['items'] as $item): ?>
                                <div class="order-item">
                                    <div class="item-image">
                                        <img src="<?= htmlspecialchars($item['image_url'] ?? '/Webgiay/assets/images/placeholder.jpg') ?>" 
                                             alt="<?= htmlspecialchars($item['name']) ?>" 
                                             loading="lazy">
                                        <div class="item-quantity-badge"><?= $item['quantity'] ?></div>
                                    </div>
                                    
                                    <div class="item-details">
                                        <h4 class="item-name"><?= htmlspecialchars($item['name']) ?></h4>
                                        <div class="item-specs">
                                            <?php if (!empty($item['size'])): ?>
                                                <span class="spec-tag">Size: <?= htmlspecialchars($item['size']) ?></span>
                                            <?php endif; ?>
                                            <?php if (!empty($item['color'])): ?>
                                                <span class="spec-tag">Color: <?= htmlspecialchars($item['color']) ?></span>
                                            <?php endif; ?>
                                        </div>
                                        <div class="item-pricing">
                                            <span class="unit-price">$<?= number_format($item['price'], 2) ?> each</span>
                                            <span class="total-price">$<?= number_format($item['price'] * $item['quantity'], 2) ?></span>
                                        </div>
                                    </div>
                                    
                                    <div class="item-actions">
                                        <button class="action-btn" onclick="reviewProduct(<?= $item['id'] ?? 0 ?>)" title="Write Review">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                                            </svg>
                                        </button>
                                        <button class="action-btn" onclick="buyAgain(<?= $item['id'] ?? 0 ?>)" title="Buy Again">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4m0 0L7 13m0 0l-2.5 5M7 13l2.5 5M17 13v6a2 2 0 01-2 2H9a2 2 0 01-2-2v-6" />
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div class="empty-items">
                                <div class="empty-icon">
                                    <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                                    </svg>
                                </div>
                                <p>No items found for this order</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Order Summary & Info -->
            <div class="order-sidebar">                <!-- Order Summary -->
                <div class="order-card order-summary-card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                            </svg>
                            Order Summary
                        </h3>
                    </div>
                    
                    <div class="summary-content">
                        <div class="summary-row">
                            <span class="summary-label">Subtotal</span>
                            <span class="summary-value">$<?= number_format($order['subtotal'] ?? (($order['total'] ?? $order['total_amount'] ?? 0) - 10), 2) ?></span>
                        </div>
                        <div class="summary-row">
                            <span class="summary-label">Shipping</span>
                            <span class="summary-value">$10.00</span>
                        </div>
                        <div class="summary-row">
                            <span class="summary-label">Tax</span>
                            <span class="summary-value">$<?= number_format((($order['total'] ?? $order['total_amount'] ?? 0) - 10) * 0.08, 2) ?></span>
                        </div>
                        <div class="summary-divider"></div>                        <div class="summary-row summary-total">
                            <span class="summary-label">Total</span>
                            <span class="summary-value">$<?= number_format($order['total'] ?? $order['total_amount'] ?? 0, 2) ?></span>
                        </div>
                    </div>
                </div>

                <!-- Payment Method -->
                <div class="order-card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                            </svg>
                            Payment Method
                        </h3>
                    </div>
                    
                    <div class="card-content">
                        <div class="payment-method">
                            <div class="payment-icon">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                                </svg>
                            </div>                            <div class="payment-details">
                                <?php
                                // Extract payment method from notes field or use fallback
                                $paymentMethod = 'Credit Card';
                                if (isset($order['payment_method'])) {
                                    $paymentMethod = ucfirst(str_replace('_', ' ', $order['payment_method']));
                                } elseif (isset($order['notes']) && strpos($order['notes'], 'Payment method:') !== false) {
                                    $noteParts = explode('Payment method:', $order['notes']);
                                    if (count($noteParts) > 1) {
                                        $paymentMethod = ucfirst(str_replace('_', ' ', trim($noteParts[1])));
                                    }
                                }
                                ?>
                                <span class="payment-type"><?= $paymentMethod ?></span>
                                <span class="payment-last4">•••• •••• •••• <?= substr($order['card_last4'] ?? '1234', -4) ?></span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Customer Support -->
                <div class="order-card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192L5.636 18.364M12 2.25V4.5m0 15v2.25M21.75 12H19.5m-15 0H2.25" />
                            </svg>
                            Need Help?
                        </h3>
                    </div>
                    
                    <div class="card-content">
                        <div class="support-options">
                            <button class="support-btn" onclick="openLiveChat()">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                                </svg>
                                Live Chat
                            </button>
                            <a href="/Webgiay/contact" class="support-btn">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                </svg>
                                Contact Us
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div><!-- Order Detail JavaScript -->
    <script>
        // Download Invoice
        function downloadInvoice(orderId) {
            // Create a download link for the invoice
            const link = document.createElement('a');
            link.href = `/Webgiay/orders/${orderId}/invoice`;
            link.download = `invoice-${orderId}.pdf`;
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        }

        // Track Package
        function trackPackage(orderId) {
            // Open tracking modal or redirect to tracking page
            alert(`Track your package for Order #${orderId}\nTracking number: TRK${orderId}001\nCarrier: Nike Express`);
        }

        // Cancel Order
        function cancelOrder(orderId) {
            if (confirm('Are you sure you want to cancel this order?')) {
                fetch(`/Webgiay/orders/${orderId}/cancel`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        location.reload();
                    } else {
                        alert('Failed to cancel order: ' + (data.message || 'Unknown error'));
                    }
                })
                .catch(error => {
                    alert('Error canceling order: ' + error.message);
                });
            }
        }

        // Reorder
        function reorder() {
            if (confirm('Add all items from this order to your cart?')) {
                fetch(`/Webgiay/orders/<?= $order['id'] ?>/reorder`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Items added to cart successfully!');
                        window.location.href = '/Webgiay/cart';
                    } else {
                        alert('Failed to add items to cart: ' + (data.message || 'Unknown error'));
                    }
                })
                .catch(error => {
                    alert('Error adding items to cart: ' + error.message);
                });
            }
        }

        // Review Product
        function reviewProduct(productId) {
            window.location.href = `/Webgiay/products/${productId}#reviews`;
        }

        // Buy Again
        function buyAgain(productId) {
            window.location.href = `/Webgiay/products/${productId}`;
        }

        // Open Live Chat
        function openLiveChat() {
            // Implement live chat functionality
            alert('Live chat feature will be available soon!');
        }

        // Add hover effects and animations
        document.addEventListener('DOMContentLoaded', function() {
            // Add smooth transitions to buttons
            const buttons = document.querySelectorAll('.nike-btn, .action-btn');
            buttons.forEach(button => {
                button.addEventListener('mouseenter', function() {
                    this.style.transform = 'translateY(-2px)';
                });
                
                button.addEventListener('mouseleave', function() {
                    this.style.transform = 'translateY(0)';
                });
            });

            // Add progress animation
            const progressSteps = document.querySelectorAll('.timeline-step');
            progressSteps.forEach((step, index) => {
                setTimeout(() => {
                    step.style.opacity = '1';
                    step.style.transform = 'translateY(0)';
                }, index * 200);
            });
        });
    </script>
<?php endif; ?>
