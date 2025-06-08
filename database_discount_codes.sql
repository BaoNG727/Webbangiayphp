-- ========================================
-- Discount Codes System for Nike Shoe Store
-- ========================================

USE `nike_store`;

-- ========================================
-- Table structure for `discount_codes`
-- ========================================
CREATE TABLE `discount_codes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `code` varchar(50) NOT NULL UNIQUE,
  `description` varchar(255) DEFAULT NULL,
  `type` enum('percentage','fixed') NOT NULL DEFAULT 'percentage',
  `value` decimal(10,2) NOT NULL,
  `minimum_order_amount` decimal(10,2) DEFAULT 0.00,
  `maximum_discount_amount` decimal(10,2) DEFAULT NULL,
  `usage_limit` int(11) DEFAULT NULL,
  `usage_count` int(11) DEFAULT 0,
  `user_usage_limit` int(11) DEFAULT NULL,
  `valid_from` datetime NOT NULL,
  `valid_until` datetime NOT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `created_by` int(11) NOT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_code` (`code`),
  KEY `fk_discount_codes_user` (`created_by`),
  KEY `idx_code_active` (`code`, `is_active`),
  KEY `idx_validity` (`valid_from`, `valid_until`),
  CONSTRAINT `fk_discount_codes_user` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ========================================
-- Table structure for `discount_code_usage`
-- ========================================
CREATE TABLE `discount_code_usage` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `discount_code_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `discount_amount` decimal(10,2) NOT NULL,
  `used_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `fk_usage_discount_code` (`discount_code_id`),
  KEY `fk_usage_user` (`user_id`),
  KEY `fk_usage_order` (`order_id`),
  KEY `idx_user_code` (`user_id`, `discount_code_id`),
  CONSTRAINT `fk_usage_discount_code` FOREIGN KEY (`discount_code_id`) REFERENCES `discount_codes` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_usage_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_usage_order` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ========================================
-- Add discount tracking to orders table
-- ========================================
ALTER TABLE `orders` 
ADD COLUMN `discount_code` varchar(50) DEFAULT NULL AFTER `total_amount`,
ADD COLUMN `discount_amount` decimal(10,2) DEFAULT 0.00 AFTER `discount_code`,
ADD COLUMN `subtotal_amount` decimal(10,2) DEFAULT 0.00 AFTER `discount_amount`;

-- ========================================
-- Sample discount codes
-- ========================================
INSERT INTO `discount_codes` (
    `code`, 
    `description`, 
    `type`, 
    `value`, 
    `minimum_order_amount`, 
    `maximum_discount_amount`, 
    `usage_limit`, 
    `user_usage_limit`, 
    `valid_from`, 
    `valid_until`, 
    `created_by`
) VALUES 
(
    'WELCOME10', 
    'Welcome discount for new customers', 
    'percentage', 
    10.00, 
    500000.00, 
    200000.00, 
    100, 
    1, 
    NOW(), 
    DATE_ADD(NOW(), INTERVAL 30 DAY),
    (SELECT id FROM users WHERE role = 'admin' LIMIT 1)
),
(
    'NIKE2024', 
    'Nike 2024 Special Promotion', 
    'percentage', 
    15.00, 
    1000000.00, 
    300000.00, 
    50, 
    2, 
    NOW(), 
    DATE_ADD(NOW(), INTERVAL 60 DAY),
    (SELECT id FROM users WHERE role = 'admin' LIMIT 1)
),
(
    'SAVE50K', 
    'Fixed 50K discount', 
    'fixed', 
    50000.00, 
    300000.00, 
    NULL, 
    200, 
    3, 
    NOW(), 
    DATE_ADD(NOW(), INTERVAL 45 DAY),
    (SELECT id FROM users WHERE role = 'admin' LIMIT 1)
),
(
    'STUDENT20', 
    'Student discount 20%', 
    'percentage', 
    20.00, 
    200000.00, 
    150000.00, 
    NULL, 
    1, 
    NOW(), 
    DATE_ADD(NOW(), INTERVAL 90 DAY),
    (SELECT id FROM users WHERE role = 'admin' LIMIT 1)
),
(
    'FLASH100K', 
    'Flash sale 100K off', 
    'fixed', 
    100000.00, 
    800000.00, 
    NULL, 
    25, 
    1, 
    NOW(), 
    DATE_ADD(NOW(), INTERVAL 7 DAY),
    (SELECT id FROM users WHERE role = 'admin' LIMIT 1)
);

-- ========================================
-- Update existing orders with subtotal
-- ========================================
UPDATE `orders` SET `subtotal_amount` = `total_amount` WHERE `subtotal_amount` = 0.00;

-- ========================================
-- End of discount codes database setup
-- ========================================
