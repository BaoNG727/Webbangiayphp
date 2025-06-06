<div class="bg-gray-50 min-h-screen">
    <div class="container mx-auto px-4 py-16">
        <div class="max-w-6xl mx-auto">
            <!-- Header -->
            <div class="text-center mb-16">
                <h1 class="text-4xl md:text-5xl font-bold text-gray-900 mb-6">Get in Touch</h1>
                <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                    Have questions about our products or need assistance with your order? 
                    We're here to help! Reach out to us using any of the methods below.
                </p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
                <!-- Contact Form -->
                <div class="bg-white rounded-lg shadow-md p-8">
                    <h2 class="text-2xl font-bold text-gray-900 mb-6">Send us a Message</h2>
                    
                    <?php if (isset($success)): ?>
                        <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-lg">
                            <div class="flex items-center">
                                <svg class="w-5 h-5 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <p class="text-green-700"><?= htmlspecialchars($success) ?></p>
                            </div>
                        </div>
                    <?php endif; ?>

                    <?php if (isset($errors) && !empty($errors)): ?>
                        <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-lg">
                            <div class="flex items-start">
                                <svg class="w-5 h-5 text-red-500 mr-2 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <div>
                                    <p class="text-red-700 font-medium mb-2">Please fix the following errors:</p>
                                    <ul class="text-red-700 text-sm list-disc list-inside">
                                        <?php foreach ($errors as $error): ?>
                                            <li><?= htmlspecialchars($error) ?></li>
                                        <?php endforeach; ?>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>

                    <form method="POST" action="/Webgiay/contact" class="space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                                    Full Name <span class="text-red-500">*</span>
                                </label>
                                <input type="text" 
                                       id="name" 
                                       name="name" 
                                       value="<?= htmlspecialchars($form_data['name'] ?? '') ?>"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-black focus:border-transparent transition-colors"
                                       placeholder="Enter your full name"
                                       required>
                            </div>
                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                                    Email Address <span class="text-red-500">*</span>
                                </label>
                                <input type="email" 
                                       id="email" 
                                       name="email" 
                                       value="<?= htmlspecialchars($form_data['email'] ?? '') ?>"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-black focus:border-transparent transition-colors"
                                       placeholder="Enter your email"
                                       required>
                            </div>
                        </div>

                        <div>
                            <label for="subject" class="block text-sm font-medium text-gray-700 mb-2">
                                Subject <span class="text-red-500">*</span>
                            </label>
                            <select id="subject" 
                                    name="subject" 
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-black focus:border-transparent transition-colors"
                                    required>
                                <option value="">Select a subject</option>
                                <option value="general" <?= isset($form_data['subject']) && $form_data['subject'] === 'general' ? 'selected' : '' ?>>General Inquiry</option>
                                <option value="order" <?= isset($form_data['subject']) && $form_data['subject'] === 'order' ? 'selected' : '' ?>>Order Support</option>
                                <option value="product" <?= isset($form_data['subject']) && $form_data['subject'] === 'product' ? 'selected' : '' ?>>Product Question</option>
                                <option value="return" <?= isset($form_data['subject']) && $form_data['subject'] === 'return' ? 'selected' : '' ?>>Returns & Exchanges</option>
                                <option value="technical" <?= isset($form_data['subject']) && $form_data['subject'] === 'technical' ? 'selected' : '' ?>>Technical Issue</option>
                                <option value="other" <?= isset($form_data['subject']) && $form_data['subject'] === 'other' ? 'selected' : '' ?>>Other</option>
                            </select>
                        </div>

                        <div>
                            <label for="message" class="block text-sm font-medium text-gray-700 mb-2">
                                Message <span class="text-red-500">*</span>
                            </label>
                            <textarea id="message" 
                                      name="message" 
                                      rows="6" 
                                      class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-black focus:border-transparent transition-colors resize-vertical"
                                      placeholder="Tell us how we can help you..."
                                      required><?= htmlspecialchars($form_data['message'] ?? '') ?></textarea>
                        </div>

                        <button type="submit" 
                                class="w-full bg-black text-white font-semibold py-3 px-6 rounded-lg hover:bg-gray-800 transition-colors focus:ring-2 focus:ring-offset-2 focus:ring-black">
                            Send Message
                        </button>
                    </form>
                </div>

                <!-- Contact Information -->
                <div class="space-y-8">
                    <!-- Quick Contact -->
                    <div class="bg-white rounded-lg shadow-md p-8">
                        <h2 class="text-2xl font-bold text-gray-900 mb-6">Quick Contact</h2>
                        <div class="space-y-6">
                            <div class="flex items-start space-x-4">
                                <div class="bg-black text-white p-3 rounded-lg">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="font-semibold text-gray-900">Phone Support</h3>
                                    <p class="text-gray-600">1-800-NIKE-STORE</p>
                                    <p class="text-sm text-gray-500">Mon-Fri: 9AM-8PM EST</p>
                                </div>
                            </div>

                            <div class="flex items-start space-x-4">
                                <div class="bg-black text-white p-3 rounded-lg">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="font-semibold text-gray-900">Email Support</h3>
                                    <p class="text-gray-600">support@nikestore.com</p>
                                    <p class="text-sm text-gray-500">We respond within 24 hours</p>
                                </div>
                            </div>

                            <div class="flex items-start space-x-4">
                                <div class="bg-black text-white p-3 rounded-lg">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="font-semibold text-gray-900">Live Chat</h3>
                                    <p class="text-gray-600">Chat with our team</p>
                                    <button class="text-sm text-black font-medium hover:underline">Start Chat →</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Store Location -->
                    <div class="bg-white rounded-lg shadow-md p-8">
                        <h2 class="text-2xl font-bold text-gray-900 mb-6">Visit Our Store</h2>
                        <div class="space-y-4">
                            <div class="flex items-start space-x-4">
                                <div class="bg-black text-white p-3 rounded-lg">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="font-semibold text-gray-900">Nike Shoe Store</h3>
                                    <p class="text-gray-600">
                                        123 Athletic Way<br>
                                        Sports City, SC 12345<br>
                                        United States
                                    </p>
                                </div>
                            </div>

                            <div class="pt-4 border-t border-gray-200">
                                <h4 class="font-semibold text-gray-900 mb-2">Store Hours</h4>
                                <div class="space-y-1 text-gray-600">
                                    <div class="flex justify-between">
                                        <span>Monday - Friday</span>
                                        <span>10:00 AM - 9:00 PM</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span>Saturday</span>
                                        <span>10:00 AM - 10:00 PM</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span>Sunday</span>
                                        <span>11:00 AM - 7:00 PM</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- FAQ -->
                    <div class="bg-white rounded-lg shadow-md p-8">
                        <h2 class="text-2xl font-bold text-gray-900 mb-6">Frequently Asked Questions</h2>
                        <div class="space-y-4">
                            <div>
                                <h3 class="font-semibold text-gray-900">What is your return policy?</h3>
                                <p class="text-gray-600 text-sm mt-1">
                                    We offer 30-day returns for unworn items in original packaging.
                                </p>
                            </div>
                            <div>
                                <h3 class="font-semibold text-gray-900">How long does shipping take?</h3>
                                <p class="text-gray-600 text-sm mt-1">
                                    Standard shipping takes 3-5 business days. Express options available.
                                </p>
                            </div>
                            <div>
                                <h3 class="font-semibold text-gray-900">Do you offer size exchanges?</h3>
                                <p class="text-gray-600 text-sm mt-1">
                                    Yes! Free size exchanges within 30 days of purchase.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
