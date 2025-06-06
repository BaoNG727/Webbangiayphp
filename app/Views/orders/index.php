<?php if (empty($orders)): ?>
    <div class="container mx-auto px-4 py-8">
        <div class="max-w-4xl mx-auto">
            <h1 class="text-3xl font-bold text-gray-900 mb-8">My Orders</h1>
            
            <div class="bg-white rounded-lg shadow-md p-8 text-center">
                <div class="w-24 h-24 mx-auto mb-4 bg-gray-100 rounded-full flex items-center justify-center">
                    <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                    </svg>
                </div>
                <h2 class="text-xl font-semibold text-gray-900 mb-2">No orders yet</h2>
                <p class="text-gray-600 mb-6">You haven't placed any orders. Start shopping to see your order history here.</p>
                <a href="/Webgiay/products" class="inline-flex items-center px-6 py-3 bg-black text-white font-medium rounded-lg hover:bg-gray-800 transition-colors">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                    </svg>
                    Start Shopping
                </a>
            </div>
        </div>
    </div>
<?php else: ?>
    <div class="container mx-auto px-4 py-8">
        <div class="max-w-6xl mx-auto">
            <div class="flex items-center justify-between mb-8">
                <h1 class="text-3xl font-bold text-gray-900">My Orders</h1>
                <p class="text-gray-600"><?= count($orders) ?> order<?= count($orders) !== 1 ? 's' : '' ?></p>
            </div>

            <div class="space-y-6">
                <?php foreach ($orders as $order): ?>
                    <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow">
                        <div class="p-6">
                            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between mb-4">
                                <div class="flex flex-col sm:flex-row sm:items-center sm:space-x-4 mb-4 lg:mb-0">
                                    <h2 class="text-lg font-semibold text-gray-900">
                                        Order #<?= str_pad($order['id'], 6, '0', STR_PAD_LEFT) ?>
                                    </h2>
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                                        <?php if ($order['status'] === 'pending'): ?>
                                            bg-yellow-100 text-yellow-800
                                        <?php elseif ($order['status'] === 'processing'): ?>
                                            bg-blue-100 text-blue-800
                                        <?php elseif ($order['status'] === 'shipped'): ?>
                                            bg-green-100 text-green-800
                                        <?php elseif ($order['status'] === 'delivered'): ?>
                                            bg-purple-100 text-purple-800
                                        <?php else: ?>
                                            bg-gray-100 text-gray-800
                                        <?php endif; ?>">
                                        <?= ucfirst($order['status']) ?>
                                    </span>
                                </div>
                                <div class="text-right">
                                    <p class="text-2xl font-bold text-gray-900">$<?= number_format($order['total'], 2) ?></p>
                                    <p class="text-sm text-gray-500">
                                        Placed on <?= date('M j, Y', strtotime($order['created_at'])) ?>
                                    </p>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-4">
                                <div>
                                    <p class="text-sm font-medium text-gray-500">Shipping Address</p>
                                    <p class="text-sm text-gray-900"><?= htmlspecialchars($order['shipping_address']) ?></p>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-500">Payment Method</p>
                                    <p class="text-sm text-gray-900">
                                        <?php if ($order['payment_method'] === 'credit_card'): ?>
                                            Credit Card
                                        <?php elseif ($order['payment_method'] === 'paypal'): ?>
                                            PayPal
                                        <?php elseif ($order['payment_method'] === 'bank_transfer'): ?>
                                            Bank Transfer
                                        <?php else: ?>
                                            <?= ucfirst(str_replace('_', ' ', $order['payment_method'])) ?>
                                        <?php endif; ?>
                                    </p>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-500">Items</p>
                                    <p class="text-sm text-gray-900"><?= $order['item_count'] ?> item<?= $order['item_count'] !== 1 ? 's' : '' ?></p>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-500">Expected Delivery</p>
                                    <p class="text-sm text-gray-900">
                                        <?php if ($order['status'] === 'delivered'): ?>
                                            Delivered
                                        <?php else: ?>
                                            <?= date('M j, Y', strtotime($order['created_at'] . ' +5 days')) ?>
                                        <?php endif; ?>
                                    </p>
                                </div>
                            </div>

                            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between pt-4 border-t border-gray-200">
                                <div class="flex flex-wrap gap-2 mb-4 sm:mb-0">
                                    <?php if ($order['status'] === 'shipped'): ?>
                                        <span class="inline-flex items-center px-3 py-1 bg-green-50 text-green-700 text-sm font-medium rounded-lg">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            On the way
                                        </span>
                                    <?php elseif ($order['status'] === 'delivered'): ?>
                                        <span class="inline-flex items-center px-3 py-1 bg-purple-50 text-purple-700 text-sm font-medium rounded-lg">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            Delivered
                                        </span>
                                    <?php endif; ?>
                                </div>
                                
                                <div class="flex flex-wrap gap-2">
                                    <a href="/Webgiay/orders/<?= $order['id'] ?>" 
                                       class="inline-flex items-center px-4 py-2 bg-black text-white text-sm font-medium rounded-lg hover:bg-gray-800 transition-colors">
                                        View Details
                                        <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                        </svg>
                                    </a>
                                    
                                    <?php if ($order['status'] === 'delivered'): ?>
                                        <button class="inline-flex items-center px-4 py-2 bg-gray-100 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-200 transition-colors">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path>
                                            </svg>
                                            Download Invoice
                                        </button>
                                    <?php endif; ?>
                                    
                                    <?php if (in_array($order['status'], ['pending', 'processing'])): ?>
                                        <button class="inline-flex items-center px-4 py-2 bg-red-100 text-red-700 text-sm font-medium rounded-lg hover:bg-red-200 transition-colors">
                                            Cancel Order
                                        </button>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <!-- Pagination (if needed) -->
            <div class="mt-8 flex justify-center">
                <div class="flex items-center space-x-2">
                    <button class="px-3 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors disabled:opacity-50" disabled>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                        </svg>
                    </button>
                    <span class="px-4 py-2 bg-black text-white rounded-lg">1</span>
                    <button class="px-3 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors disabled:opacity-50" disabled>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>
