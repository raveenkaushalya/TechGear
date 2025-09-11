-- Create database and select it
CREATE DATABASE IF NOT EXISTS `techgear`
  DEFAULT CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;
USE `techgear`;

-- TechGear products table schema
CREATE TABLE IF NOT EXISTS `products` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(255) NOT NULL,
  `description` TEXT NULL,
  `price` DECIMAL(10,2) NOT NULL DEFAULT 0.00,
  `image` VARCHAR(512) NULL,
  `category` VARCHAR(50) NULL,
  `status` ENUM('active','hidden') NOT NULL DEFAULT 'active',
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Seed data for products
INSERT INTO `products` (`name`, `description`, `price`, `image`, `category`, `status`) VALUES
-- Keyboards
('RGB Mechanical Keyboard', 'Customizable RGB Mechanical Gaming Keyboard with Blue Switches', 89.99, '../assets/images/k1.jpg', 'keyboards', 'active'),
('Wireless Mechanical Keyboard', 'Low-latency Wireless Mechanical Keyboard with Brown Switches', 129.99, '../assets/images/k2.jpg', 'keyboards', 'active'),
('Compact Mechanical Keyboard', '60% compact mechanical keyboard ideal for portability and gaming', 79.99, '../assets/images/k4.jpg', 'keyboards', 'active'),
('Low Profile Mechanical Keyboard', 'Slim, low-profile mechanical keyboard for modern setups', 109.99, '../assets/images/k5.jpg', 'keyboards', 'active'),

-- Mice
('Cyberpunk Edition Mouse', 'Exclusive Cyberpunk RGB Wireless Gaming Mouse – Only while stocks last!', 129.99, '../assets/images/m1-cyberpunk.jpg', 'mice', 'active'),
('Wireless Gaming Mouse', 'Precision wireless gaming mouse with adjustable DPI', 59.99, '../assets/images/m2.jpg', 'mice', 'active'),
('Ergonomic Office Mouse', 'Ergonomic mouse designed for long hours and productivity', 39.99, '../assets/images/m3.jpg', 'mice', 'active'),
('Pro Gaming Mouse', 'Lightweight pro-grade gaming mouse with ultra-fast sensor', 79.99, '../assets/images/m4.jpg', 'mice', 'active'),
('Silent Click Mouse', 'Silent clicking mouse suitable for office environments', 69.99, '../assets/images/m5.jpg', 'mice', 'active'),
('Ambidextrous Gaming Mouse', 'Ambidextrous design for left- and right-handed gamers', 89.99, '../assets/images/m6.jpg', 'mice', 'active'),

-- Monitors
('144Hz Gaming Monitor', '27-inch 1440p 144Hz IPS Gaming Monitor with 1ms Response Time', 299.99, '../assets/images/mn1.jpg', 'monitors', 'active'),
('165Hz QHD Gaming Monitor', '27-inch QHD 165Hz gaming monitor with adaptive sync', 349.99, '../assets/images/mn2.jpg', 'monitors', 'active'),
('UltraWide Productivity Monitor', '34-inch UltraWide monitor perfect for multitasking', 499.99, '../assets/images/mn3.jpg', 'monitors', 'active'),
('4K UHD Monitor', 'Crisp 4K UHD monitor for content creation and entertainment', 399.99, '../assets/images/mn4.jpg', 'monitors', 'active'),
('1080p 75Hz Monitor', 'Budget-friendly 24-inch 1080p monitor with 75Hz refresh rate', 179.99, '../assets/images/mn5.jpg', 'monitors', 'active'),

-- Headsets / Headphones
('Wireless Gaming Headset', 'Low-latency Wireless Gaming Headset with 7.1 Surround Sound', 129.99, '../assets/images/h1.jpg', 'headphones', 'active'),
('Noise Cancelling Headphones', 'Over-ear active noise cancelling headphones for immersive audio', 149.99, '../assets/images/h2.jpg', 'headphones', 'active'),
('Surround Sound Headset', '7.1 surround sound headset with detachable mic', 99.99, '../assets/images/h3.jpg', 'headphones', 'active'),
('Lightweight Esports Headset', 'Comfortable, lightweight headset tuned for esports', 79.99, '../assets/images/h4.jpg', 'headphones', 'active');
