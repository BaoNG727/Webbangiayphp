-- ========================================
-- Nike Shoe Store Database Setup
-- Database: nike_store
-- ========================================

-- Create database if not exists
CREATE DATABASE IF NOT EXISTS `nike_store` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `nike_store`;

-- ========================================
-- Table structure for `categories`
-- ========================================
CREATE TABLE `categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ========================================
-- Table structure for `products`
-- ========================================
CREATE TABLE `products` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `brand` varchar(100) DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `description` text DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `category_id` int(11) DEFAULT NULL,
  `size` varchar(50) DEFAULT NULL,
  `color` varchar(50) DEFAULT NULL,
  `stock_quantity` int(11) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `stock` int(11) DEFAULT 0,
  `sale_price` decimal(10,2) DEFAULT NULL,
  `category` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_products_category` (`category_id`),
  CONSTRAINT `fk_products_category` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ========================================
-- Table structure for `shoes`
-- ========================================
CREATE TABLE `shoes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `size` varchar(50) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `featured` tinyint(1) DEFAULT 0,
  `category_id` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `fk_shoes_category` (`category_id`),
  CONSTRAINT `fk_shoes_category` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ========================================
-- Table structure for `users`
-- ========================================
CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `username` varchar(100) DEFAULT NULL,
  `first_name` varchar(100) DEFAULT NULL,
  `last_name` varchar(100) DEFAULT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','customer') NOT NULL DEFAULT 'customer',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ========================================
-- Table structure for `orders`
-- ========================================
CREATE TABLE `orders` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `total_amount` decimal(10,2) NOT NULL,
  `status` enum('pending','processing','shipped','delivered','cancelled') NOT NULL DEFAULT 'pending',
  `name` varchar(100) NOT NULL,
  `address` varchar(255) NOT NULL,
  `city` varchar(100) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `notes` text DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `fk_orders_user` (`user_id`),
  CONSTRAINT `fk_orders_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ========================================
-- Table structure for `order_items`
-- ========================================
CREATE TABLE `order_items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `order_id` int(11) NOT NULL,
  `shoe_id` int(11) NOT NULL,
  `product_id` int(11) DEFAULT NULL,
  `quantity` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `subtotal` decimal(10,2) NOT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `fk_order_items_order` (`order_id`),
  KEY `fk_order_items_shoe` (`shoe_id`),
  CONSTRAINT `fk_order_items_order` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_order_items_shoe` FOREIGN KEY (`shoe_id`) REFERENCES `shoes` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ========================================
-- Table structure for `cart`
-- ========================================
CREATE TABLE `cart` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `fk_cart_user` (`user_id`),
  KEY `fk_cart_product` (`product_id`),
  CONSTRAINT `fk_cart_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_cart_product` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ========================================
-- Table structure for `reviews`
-- ========================================
CREATE TABLE `reviews` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `shoe_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `rating` int(11) NOT NULL,
  `review` text NOT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `fk_reviews_shoe` (`shoe_id`),
  KEY `fk_reviews_user` (`user_id`),
  CONSTRAINT `fk_reviews_shoe` FOREIGN KEY (`shoe_id`) REFERENCES `shoes` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_reviews_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ========================================
-- Sample Data
-- ========================================

-- Insert sample categories
INSERT INTO `categories` (`name`, `description`) VALUES
('Giày chạy bộ', 'Giày thể thao dành cho chạy bộ và tập luyện'),
('Giày bóng rổ', 'Giày dành cho chơi bóng rổ'),
('Giày thời trang', 'Giày thể thao thời trang hàng ngày'),
('Giày tennis', 'Giày dành cho môn tennis');

-- Insert sample admin user (password: password)
INSERT INTO `users` (`name`, `username`, `email`, `password`, `role`) VALUES
('Admin', 'admin', 'admin@nikestore.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin');

-- Insert sample products
INSERT INTO `products` (`name`, `brand`, `price`, `description`, `image`, `category_id`, `size`, `color`, `stock_quantity`, `stock`, `category`) VALUES
('Nike Air Max 90', 'Nike', 2500000, 'Giày thể thao Nike Air Max 90 classic', 'nike-air-max-90.jpg', 1, '42', 'Trắng', 50, 50, 'Giày chạy bộ'),
('Nike Air Force 1', 'Nike', 2200000, 'Giày Nike Air Force 1 trắng cổ điển', 'nike-air-force-1.jpg', 3, '41', 'Trắng', 30, 30, 'Giày thời trang'),
('Nike Dunk Low', 'Nike', 2000000, 'Giày Nike Dunk Low retro', 'nike-air-force-1-black.jpg', 3, '43', 'Đen', 25, 25, 'Giày thời trang'),
('Nike Zoom Freak', 'Nike', 2800000, 'Giày bóng rổ Nike Zoom Freak', 'nike-basketball-court.jpg', 2, '44', 'Xanh', 20, 20, 'Giày bóng rổ'),
('Nike Revolution 6', 'Nike', 1800000, 'Giày chạy bộ Nike Revolution 6', 'nike-revolution-6.jpg', 1, '40', 'Xanh dương', 35, 35, 'Giày chạy bộ'),
('Nike Training Pro', 'Nike', 2100000, 'Giày tập luyện đa năng', 'nike-training-gym.jpg', 4, '42', 'Đen cam', 28, 28, 'Giày tennis'),
('Nike Air Max 270', 'Nike', 2600000, 'Nike Air Max 270 với đệm khí lớn', 'nike-air-max-270.jpg', 1, '41', 'Đen trắng', 22, 22, 'Giày chạy bộ'),
('Nike Blazer Mid', 'Nike', 2300000, 'Nike Blazer Mid phong cách vintage', 'nike-blazer-mid.jpg', 3, '43', 'Trắng đen', 18, 18, 'Giày thời trang');

-- Insert sample shoes (duplicate of products for compatibility)
INSERT INTO `shoes` (`name`, `description`, `price`, `size`, `image`, `featured`, `category_id`) VALUES
('Nike Air Max 90', 'Giày thể thao Nike Air Max 90 classic với công nghệ đệm khí hiện đại', 2500000, '42', 'nike-air-max-90.jpg', 1, 1),
('Nike Air Force 1', 'Giày Nike Air Force 1 trắng cổ điển, biểu tượng của văn hóa streetwear', 2200000, '41', 'nike-air-force-1.jpg', 1, 3),
('Nike Dunk Low', 'Giày Nike Dunk Low retro với thiết kế vintage đầy cuốn hút', 2000000, '43', 'nike-air-force-1-black.jpg', 0, 3),
('Nike Zoom Freak', 'Giày bóng rổ Nike Zoom Freak với công nghệ Zoom Air responsive', 2800000, '44', 'nike-basketball-court.jpg', 1, 2),
('Nike Revolution 6', 'Giày chạy bộ thoải mái cho người mới bắt đầu', 1800000, '40', 'nike-revolution-6.jpg', 0, 1),
('Nike Training Pro', 'Giày tập luyện đa năng cho mọi hoạt động thể thao', 2100000, '42', 'nike-training-gym.jpg', 0, 4),
('Nike Air Max 270', 'Giày lifestyle với đệm khí Max Air lớn nhất từng có', 2600000, '41', 'nike-air-max-270.jpg', 1, 1),
('Nike Blazer Mid', 'Giày thời trang phong cách retro từ những năm 70', 2300000, '43', 'nike-blazer-mid.jpg', 0, 3);

-- ========================================
-- End of database.sql
-- ========================================

