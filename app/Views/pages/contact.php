<?php if (isset($data['success'])): ?>
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
        <?= htmlspecialchars($data['success']) ?>
    </div>
<?php endif; ?>

<?php if (isset($data['errors']) && !empty($data['errors'])): ?>
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
        <ul class="list-disc list-inside">
            <?php foreach ($data['errors'] as $error): ?>
                <li><?= htmlspecialchars($error) ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

<!-- Hero Section -->
<section class="bg-gradient-to-r from-red-600 via-red-700 to-red-800 text-white py-20">
    <div class="container mx-auto px-4 text-center">
        <h1 class="text-6xl font-bold mb-4 text-red-500">LIÊN HỆ</h1>
        <p class="text-xl mb-8">Chúng tôi luôn sẵn sàng hỗ trợ bạn</p>
    </div>
</section>

<!-- Contact Methods -->
<section class="py-16 bg-gray-50">
    <div class="container mx-auto px-4">
        <div class="grid md:grid-cols-3 gap-8 mb-16">
            <!-- Hotline -->
            <div class="bg-white rounded-lg shadow-md p-8 text-center hover:shadow-xl transition-shadow">
                <h3 class="text-xl font-bold mb-2">HOTLINE</h3>
                <p class="text-3xl font-bold text-red-600 mb-2">1900.1008</p>
                <p class="text-gray-600">Hỗ trợ 24/7</p>
            </div>

            <!-- Email -->
            <div class="bg-white rounded-lg shadow-md p-8 text-center hover:shadow-xl transition-shadow">
                <h3 class="text-xl font-bold mb-2">EMAIL</h3>
                <p class="text-lg font-semibold text-red-600 mb-2">support@nike.vn</p>
                <p class="text-gray-600">Phản hồi trong 24h</p>
            </div>

            <!-- Store Locations -->
            <div class="bg-white rounded-lg shadow-md p-8 text-center hover:shadow-xl transition-shadow">
                <h3 class="text-xl font-bold mb-2">CỬA HÀNG</h3>
                <p class="text-lg font-semibold text-red-600 mb-2">2 Chi nhánh</p>
                <p class="text-gray-600">Hà Nội & TP.HCM</p>
            </div>
        </div>
    </div>
</section>

<!-- Contact Form -->
<section class="py-16">
    <div class="container mx-auto px-4">
        <div class="max-w-4xl mx-auto">
            <div class="grid md:grid-cols-2 gap-12">
                <!-- Contact Form -->
                <div>
                    <h2 class="text-3xl font-bold mb-6">Gửi tin nhắn cho chúng tôi</h2>
                    <form method="POST" action="/contact" class="space-y-6">
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Họ và tên *</label>
                            <input type="text" id="name" name="name" required 
                                   value="<?= htmlspecialchars($data['form_data']['name'] ?? '') ?>"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500">
                        </div>

                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email *</label>
                            <input type="email" id="email" name="email" required 
                                   value="<?= htmlspecialchars($data['form_data']['email'] ?? '') ?>"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500">
                        </div>

                        <div>
                            <label for="subject" class="block text-sm font-medium text-gray-700 mb-2">Chủ đề *</label>
                            <select id="subject" name="subject" required 
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500">
                                <option value="">Chọn chủ đề</option>
                                <option value="product_inquiry" <?= ($data['form_data']['subject'] ?? '') === 'product_inquiry' ? 'selected' : '' ?>>Hỏi về sản phẩm</option>
                                <option value="order_support" <?= ($data['form_data']['subject'] ?? '') === 'order_support' ? 'selected' : '' ?>>Hỗ trợ đơn hàng</option>
                                <option value="return_exchange" <?= ($data['form_data']['subject'] ?? '') === 'return_exchange' ? 'selected' : '' ?>>Đổi trả hàng</option>                                <option value="complaint" <?= ($data['form_data']['subject'] ?? '') === 'complaint' ? 'selected' : '' ?>>Khiếu nại</option>
                                <option value="suggestion" <?= ($data['form_data']['subject'] ?? '') === 'suggestion' ? 'selected' : '' ?>>Góp ý</option>
                                <option value="other" <?= ($data['form_data']['subject'] ?? '') === 'other' ? 'selected' : '' ?>>Khác</option>
                            </select>
                        </div>

                        <div>
                            <label for="message" class="block text-sm font-medium text-gray-700 mb-2">Nội dung *</label>
                            <textarea id="message" name="message" rows="6" required 
                                      class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500"
                                      placeholder="Nhập nội dung tin nhắn của bạn..."><?= htmlspecialchars($data['form_data']['message'] ?? '') ?></textarea>
                        </div>

                        <button type="submit" 
                                class="w-full bg-red-600 text-white py-3 px-6 rounded-lg font-semibold hover:bg-red-700 transition-colors">
                            Gửi tin nhắn
                        </button>
                    </form>
                </div>

                <!-- Store Information -->
                <div>
                    <h2 class="text-3xl font-bold mb-6">Thông tin cửa hàng</h2>
                    
                    <!-- Hanoi Store -->
                    <div class="bg-gray-50 rounded-lg p-6 mb-6">
                        <h3 class="text-xl font-bold mb-3 text-red-600">Chi nhánh Hà Nội</h3>
                        <div class="space-y-2">
                            <p>123 Phố Huế, Hai Bà Trưng, Hà Nội</p>
                            <p>(024) 3333 4444</p>
                            <p>8:00 - 21:00 (Thứ 2 - CN)</p>
                        </div>
                    </div>

                    <!-- Ho Chi Minh Store -->
                    <div class="bg-gray-50 rounded-lg p-6 mb-6">
                        <h3 class="text-xl font-bold mb-3 text-red-600">Chi nhánh TP.HCM</h3>
                        <div class="space-y-2">
                            <p>456 Nguyễn Trãi, Quận 1, TP.HCM</p>
                            <p>(028) 5555 6666</p>
                            <p>8:00 - 21:00 (Thứ 2 - CN)</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- FAQ Section -->
<section class="py-16 bg-gray-50">
    <div class="container mx-auto px-4">
        <h2 class="text-3xl font-bold text-center mb-12">Câu hỏi thường gặp</h2>
        <div class="max-w-4xl mx-auto">
            <div class="space-y-6">
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="text-lg font-semibold mb-2">Thời gian giao hàng bao lâu?</h3>
                    <p class="text-gray-600">Chúng tôi giao hàng trong vòng 1-3 ngày làm việc tại Hà Nội và TP.HCM, 3-7 ngày tại các tỉnh thành khác.</p>
                </div>
                
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="text-lg font-semibold mb-2">Chính sách đổi trả như thế nào?</h3>
                    <p class="text-gray-600">Bạn có thể đổi trả sản phẩm trong vòng 30 ngày kể từ ngày mua với điều kiện sản phẩm còn nguyên tem, hộp và chưa qua sử dụng.</p>
                </div>
                
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="text-lg font-semibold mb-2">Làm sao để biết size giày phù hợp?</h3>
                    <p class="text-gray-600">Chúng tôi có bảng size chi tiết cho từng dòng sản phẩm. Bạn cũng có thể đến cửa hàng để thử trực tiếp hoặc liên hệ hotline để được tư vấn.</p>
                </div>
                
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="text-lg font-semibold mb-2">Sản phẩm có chính hãng không?</h3>
                    <p class="text-gray-600">Tất cả sản phẩm tại cửa hàng đều là chính hãng 100% với đầy đủ tem nhãn, hóa đơn và bảo hành từ Nike.</p>
                </div>
            </div>
        </div>
    </div>
</section>
