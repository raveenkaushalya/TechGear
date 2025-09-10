-- TechGear Database Structure
-- This script creates the necessary tables for the TechGear website

-- Create the database if it doesn't exist
CREATE DATABASE IF NOT EXISTS techgear;

-- Use the techgear database
USE techgear;

-- Drop tables if they exist
DROP TABLE IF EXISTS product_images;
DROP TABLE IF EXISTS products;
DROP TABLE IF EXISTS categories;

-- Categories table
CREATE TABLE categories (
    id VARCHAR(50) PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Products table
CREATE TABLE products (
    id VARCHAR(50) PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    description TEXT,
    price DECIMAL(10, 2) NOT NULL,
    quantity INT NOT NULL DEFAULT 0,
    category_id VARCHAR(50) NOT NULL,
    status ENUM('available', 'out_of_stock', 'discontinued') NOT NULL DEFAULT 'available',
    featured BOOLEAN DEFAULT FALSE,
    limited_edition BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE CASCADE
);

-- Product Images table
CREATE TABLE product_images (
    id INT AUTO_INCREMENT PRIMARY KEY,
    product_id VARCHAR(50) NOT NULL,
    image_path VARCHAR(255) NOT NULL,
    is_primary BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
);

-- Insert sample categories data
INSERT INTO categories (id, name, description) VALUES
('keyboards', 'Keyboards', 'Discover our selection of premium mechanical keyboards, perfect for gaming and productivity.'),
('mice', 'Mice', 'Find the perfect gaming and productivity mice with precision sensors and ergonomic designs.'),
('monitors', 'Monitors', 'Upgrade your visual experience with our high-performance gaming and professional monitors.'),
('headphones', 'Headphones', 'Experience superior audio with our range of gaming headsets and professional headphones.');

-- Insert sample products data
INSERT INTO products (id, name, description, price, quantity, category_id, status, featured, limited_edition) VALUES
('k1', 'RGB Mechanical Keyboard', 'Customizable RGB Mechanical Gaming Keyboard with Blue Switches', 89.99, 50, 'keyboards', 'available', TRUE, FALSE),
('k2', 'Wireless Mechanical Keyboard', 'Low-latency Wireless Mechanical Keyboard with Brown Switches', 129.99, 30, 'keyboards', 'available', FALSE, FALSE),
('k3', 'Compact 60% Keyboard', 'Compact 60% Layout Mechanical Keyboard with PBT Keycaps', 79.99, 25, 'keyboards', 'available', FALSE, FALSE),
('k4', 'Premium Mechanical Keyboard', 'Premium Aluminum Frame Keyboard with Hot-swappable Switches', 149.99, 15, 'keyboards', 'available', FALSE, FALSE),
('k5', 'Silent Mechanical Keyboard', 'Ultra-quiet Mechanical Keyboard for Office and Gaming', 99.99, 20, 'keyboards', 'available', FALSE, FALSE),

('m1', 'Cyberpunk Edition Mouse', 'Exclusive Cyberpunk RGB Wireless Gaming Mouse – Only while stocks last!', 129.99, 10, 'mice', 'available', TRUE, TRUE),
('m2', 'Wireless Gaming Mouse', 'Ultra-lightweight Wireless Gaming Mouse with 20,000 DPI Sensor', 69.99, 40, 'mice', 'available', FALSE, FALSE),
('m3', 'Ergonomic Mouse', 'Vertical Ergonomic Mouse for Reduced Wrist Strain', 49.99, 35, 'mice', 'available', FALSE, FALSE),
('m4', 'MMO Gaming Mouse', 'MMO Gaming Mouse with 12 Programmable Side Buttons', 79.99, 25, 'mice', 'available', FALSE, FALSE),
('m5', 'Premium Gaming Mouse', 'Ultralight Gaming Mouse with PTFE Feet and Paracord Cable', 89.99, 20, 'mice', 'available', FALSE, FALSE),
('m6', 'Classic Mouse', 'Reliable Wired Mouse for Everyday Use', 29.99, 60, 'mice', 'available', FALSE, FALSE),

('mn1', '144Hz Gaming Monitor', '27-inch 1440p 144Hz IPS Gaming Monitor with 1ms Response Time', 299.99, 15, 'monitors', 'available', TRUE, FALSE),
('mn2', 'Ultrawide Monitor', '34-inch Curved Ultrawide Monitor with 21:9 Aspect Ratio', 449.99, 10, 'monitors', 'available', FALSE, FALSE),
('mn3', '4K Professional Monitor', '32-inch 4K Professional Monitor with 99% Adobe RGB Coverage', 599.99, 5, 'monitors', 'available', FALSE, FALSE),
('mn4', '240Hz Esports Monitor', '24.5-inch 1080p 240Hz TN Monitor for Competitive Gaming', 349.99, 12, 'monitors', 'available', FALSE, FALSE),
('mn5', 'Budget Gaming Monitor', '24-inch 1080p 75Hz Monitor with FreeSync Technology', 179.99, 20, 'monitors', 'available', FALSE, FALSE),

('h1', 'Wireless Gaming Headset', 'Low-latency Wireless Gaming Headset with 7.1 Surround Sound', 129.99, 25, 'headphones', 'available', TRUE, FALSE),
('h2', 'Studio Headphones', 'Professional Studio Monitoring Headphones with Flat Response', 149.99, 15, 'headphones', 'available', FALSE, FALSE),
('h3', 'Noise Cancelling Headphones', 'Wireless Noise Cancelling Headphones with 30-hour Battery Life', 199.99, 20, 'headphones', 'available', FALSE, FALSE),
('h4', 'Budget Gaming Headset', 'Affordable Gaming Headset with RGB Lighting and Microphone', 49.99, 40, 'headphones', 'available', FALSE, FALSE);

-- Insert product images
INSERT INTO product_images (product_id, image_path, is_primary) VALUES
('k1', '../assets/images/k1.jpg', TRUE),
('k2', '../assets/images/k2.jpg', TRUE),
('k3', '../assets/images/k3.jpg', TRUE),
('k4', '../assets/images/k4.jpg', TRUE),
('k5', '../assets/images/k5.jpg', TRUE),

('m1', '../assets/images/m1-cyberpunk.jpg', TRUE),
('m2', '../assets/images/m2.jpg', TRUE),
('m3', '../assets/images/m3.jpg', TRUE),
('m4', '../assets/images/m4.jpg', TRUE),
('m5', '../assets/images/m5.jpg', TRUE),
('m6', '../assets/images/m6.jpg', TRUE),

('mn1', '../assets/images/mn1.jpg', TRUE),
('mn2', '../assets/images/mn2.jpg', TRUE),
('mn3', '../assets/images/mn3.jpg', TRUE),
('mn4', '../assets/images/mn4.jpg', TRUE),
('mn5', '../assets/images/mn5.jpg', TRUE),

('h1', '../assets/images/h1.jpg', TRUE),
('h2', '../assets/images/h2.jpg', TRUE),
('h3', '../assets/images/h3.jpg', TRUE),
('h4', '../assets/images/h4.jpg', TRUE);
