<?php if (!$order): ?>
    <div class="container mx-auto px-4 py-8">
        <div class="max-w-4xl mx-auto text-center">
            <h1 class="text-3xl font-bold text-gray-900 mb-4">Order Not Found</h1>
            <p class="text-gray-600 mb-8">The order you're looking for doesn't exist or you don't have permission to view it.</p>
            <a href="/Webgiay/orders" class="inline-flex items-center px-6 py-3 bg-black text-white font-medium rounded-lg hover:bg-gray-800 transition-colors">
                View All Orders
            </a>
        </div>
    </div>
<?php else: ?>
    <div class="container mx-auto px-4 py-8">
        <div class="max-w-6xl mx-auto">
            <!-- Breadcrumb -->
            <nav class="flex items-center space-x-2 text-sm text-gray-500 mb-8">
                <a href="/Webgiay/" class="hover:text-gray-700">Home</a>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
                <a href="/Webgiay/orders" class="hover:text-gray-700">My Orders</a>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
                <span class="text-gray-900">Order #<?= str_pad($order['id'], 6, '0', STR_PAD_LEFT) ?></span>
            </nav>

            <!-- Order Header -->
            <div class="bg-white rounded-lg shadow-md p-6 mb-8">
                <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between mb-6">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900 mb-2">
                            Order #<?= str_pad($order['id'], 6, '0', STR_PAD_LEFT) ?>
                        </h1>
                        <p class="text-gray-600">
                            Placed on <?= date('F j, Y \a\t g:i A', strtotime($order['created_at'])) ?>
                        </p>
                    </div>
                    <div class="mt-4 lg:mt-0">
                        <span class="inline-flex items-center px-4 py-2 rounded-full text-lg font-medium
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
                </div>

                <!-- Order Progress -->
                <div class="mb-6">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-sm font-medium text-gray-900">Order Progress</span>
                        <span class="text-sm text-gray-500">
                            <?php
                            $statuses = ['pending', 'processing', 'shipped', 'delivered'];
                            $currentIndex = array_search($order['status'], $statuses);
                            $progress = $currentIndex !== false ? (($currentIndex + 1) / count($statuses)) * 100 : 25;
                            echo round($progress) . '%';
                            ?>
                        </span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-black h-2 rounded-full transition-all duration-300" style="width: <?= $progress ?>%"></div>
                    </div>
                    <div class="flex justify-between mt-2 text-xs text-gray-500">
                        <span class="<?= $order['status'] !== 'pending' ? 'text-black font-medium' : '' ?>">Order Placed</span>
                        <span class="<?= in_array($order['status'], ['processing', 'shipped', 'delivered']) ? 'text-black font-medium' : '' ?>">Processing</span>
                        <span class="<?= in_array($order['status'], ['shipped', 'delivered']) ? 'text-black font-medium' : '' ?>">Shipped</span>
                        <span class="<?= $order['status'] === 'delivered' ? 'text-black font-medium' : '' ?>">Delivered</span>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="flex flex-wrap gap-3">
                    <button class="inline-flex items-center px-4 py-2 bg-gray-100 text-gray-700 font-medium rounded-lg hover:bg-gray-200 transition-colors">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path>
                        </svg>
                        Download Invoice
                    </button>
                    <?php if ($order['status'] === 'shipped'): ?>
                        <button class="inline-flex items-center px-4 py-2 bg-blue-100 text-blue-700 font-medium rounded-lg hover:bg-blue-200 transition-colors">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                            Track Package
                        </button>
                    <?php endif; ?>
                    <?php if (in_array($order['status'], ['pending', 'processing'])): ?>
                        <button class="inline-flex items-center px-4 py-2 bg-red-100 text-red-700 font-medium rounded-lg hover:bg-red-200 transition-colors">
                            Cancel Order
                        </button>
                    <?php endif; ?>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Order Items -->
                <div class="lg:col-span-2">
                    <div class="bg-white rounded-lg shadow-md">
                        <div class="p-6 border-b border-gray-200">
                            <h2 class="text-xl font-semibold text-gray-900">Order Items</h2>
                        </div>
                        <div class="divide-y divide-gray-200">
                            <?php if (isset($order['items'])): ?>
                                <?php foreach ($order['items'] as $item): ?>
                                    <div class="p-6 flex items-center space-x-4">
                                        <div class="flex-shrink-0">
                                            <img src="<?= htmlspecialchars($item['image_url'] ?? '/Webgiay/assets/images/placeholder.jpg') ?>" 
                                                 alt="<?= htmlspecialchars($item['name']) ?>" 
                                                 class="w-20 h-20 object-cover rounded-lg">
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <h3 class="text-lg font-medium text-gray-900 truncate">
                                                <?= htmlspecialchars($item['name']) ?>
                                            </h3>
                                            <p class="text-sm text-gray-500">
                                                Size: <?= htmlspecialchars($item['size'] ?? 'N/A') ?> | 
                                                Color: <?= htmlspecialchars($item['color'] ?? 'N/A') ?>
                                            </p>
                                            <p class="text-sm text-gray-500">Quantity: <?= $item['quantity'] ?></p>
                                        </div>
                                        <div class="text-right">
                                            <p class="text-lg font-semibold text-gray-900">
                                                $<?= number_format($item['price'] * $item['quantity'], 2) ?>
                                            </p>
                                            <p class="text-sm text-gray-500">
                                                $<?= number_format($item['price'], 2) ?> each
                                            </p>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <div class="p-6 text-center text-gray-500">
                                    No items found for this order.
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- Order Summary & Details -->
                <div class="space-y-6">
                    <!-- Order Summary -->
                    <div class="bg-white rounded-lg shadow-md p-6">
                        <h2 class="text-xl font-semibold text-gray-900 mb-4">Order Summary</h2>
                        <div class="space-y-3">
                            <div class="flex justify-between">
                                <span class="text-gray-600">Subtotal</span>
                                <span class="font-medium">$<?= number_format($order['subtotal'] ?? $order['total'] - 10, 2) ?></span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Shipping</span>
                                <span class="font-medium">$10.00</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Tax</span>
                                <span class="font-medium">$<?= number_format(($order['total'] - 10) * 0.08, 2) ?></span>
                            </div>
                            <hr class="my-3">
                            <div class="flex justify-between text-lg font-semibold">
                                <span>Total</span>
                                <span>$<?= number_format($order['total'], 2) ?></span>
                            </div>
                        </div>
                    </div>

                    <!-- Shipping Information -->
                    <div class="bg-white rounded-lg shadow-md p-6">
                        <h2 class="text-xl font-semibold text-gray-900 mb-4">Shipping Information</h2>
                        <div class="space-y-3">
                            <div>
                                <p class="text-sm font-medium text-gray-500">Shipping Address</p>
                                <div class="mt-1 text-gray-900">
                                    <?= nl2br(htmlspecialchars($order['shipping_address'])) ?>
                                </div>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-500">Estimated Delivery</p>
                                <p class="mt-1 text-gray-900">
                                    <?php if ($order['status'] === 'delivered'): ?>
                                        Delivered
                                    <?php else: ?>
                                        <?= date('M j, Y', strtotime($order['created_at'] . ' +5 days')) ?>
                                    <?php endif; ?>
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Payment Information -->
                    <div class="bg-white rounded-lg shadow-md p-6">
                        <h2 class="text-xl font-semibold text-gray-900 mb-4">Payment Information</h2>
                        <div class="space-y-3">
                            <div>
                                <p class="text-sm font-medium text-gray-500">Payment Method</p>
                                <p class="mt-1 text-gray-900">
                                    <?php if ($order['payment_method'] === 'credit_card'): ?>
                                        Credit Card ending in ****
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
                                <p class="text-sm font-medium text-gray-500">Payment Status</p>
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    Paid
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Customer Support -->
                    <div class="bg-gray-50 rounded-lg p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">Need Help?</h3>
                        <p class="text-gray-600 text-sm mb-4">
                            Have questions about your order? Our customer support team is here to help.
                        </p>
                        <div class="space-y-2">
                            <a href="/Webgiay/contact" 
                               class="block w-full text-center px-4 py-2 bg-black text-white font-medium rounded-lg hover:bg-gray-800 transition-colors">
                                Contact Support
                            </a>
                            <button class="block w-full text-center px-4 py-2 bg-white text-gray-700 font-medium rounded-lg border border-gray-300 hover:bg-gray-50 transition-colors">
                                Live Chat
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>
