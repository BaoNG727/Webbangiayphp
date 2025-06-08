<?php
// Minimal contact page test - bypass all framework overhead
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Test - Nike Store</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen bg-gradient-to-br from-black via-gray-900 to-black text-white">
    <!-- Header -->
    <div class="text-center py-20">
        <h1 class="text-6xl font-bold mb-4 text-red-500">LIÊN HỆ</h1>
        <p class="text-2xl text-gray-300">Nike Store Vietnam</p>
        <p class="text-lg text-green-400 mt-4">✅ This minimal contact page is working!</p>
    </div>

    <!-- Quick Test Content -->
    <div class="container mx-auto px-6 pb-20">
        <div class="max-w-4xl mx-auto text-center">
            <div class="bg-gray-800 p-8 rounded-lg mb-8">
                <h2 class="text-3xl font-bold mb-4 text-white">Contact Information</h2>
                <div class="grid md:grid-cols-3 gap-6">
                    <div class="bg-red-600 p-6 rounded-lg">
                        <h3 class="text-xl font-bold mb-2">📞 Hotline</h3>
                        <p class="text-lg">1900.1008</p>
                    </div>
                    <div class="bg-red-600 p-6 rounded-lg">
                        <h3 class="text-xl font-bold mb-2">📧 Email</h3>
                        <p class="text-lg">support@nike.vn</p>
                    </div>
                    <div class="bg-red-600 p-6 rounded-lg">
                        <h3 class="text-xl font-bold mb-2">🏪 Stores</h3>
                        <p class="text-lg">Hanoi & Ho Chi Minh</p>
                    </div>
                </div>
            </div>

            <div class="bg-gray-800 p-8 rounded-lg">
                <h2 class="text-2xl font-bold mb-4">Simple Contact Form</h2>
                <form class="space-y-4">
                    <div class="grid md:grid-cols-2 gap-4">
                        <input type="text" placeholder="Họ và tên" class="p-3 rounded bg-gray-700 text-white">
                        <input type="email" placeholder="Email" class="p-3 rounded bg-gray-700 text-white">
                    </div>
                    <input type="text" placeholder="Chủ đề" class="w-full p-3 rounded bg-gray-700 text-white">
                    <textarea placeholder="Tin nhắn của bạn..." rows="4" class="w-full p-3 rounded bg-gray-700 text-white"></textarea>
                    <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-bold py-3 px-8 rounded-lg transition-colors">
                        Gửi tin nhắn
                    </button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
