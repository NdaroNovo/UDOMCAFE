-- CIVE Cafeteria Management System Database
-- Created: June 2026

CREATE DATABASE IF NOT EXISTS cive_cafeteria CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE cive_cafeteria;

-- ============================================
-- TABLE: food_items (Vyakula Vilivyopo)
-- ============================================
CREATE TABLE food_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    name_sw VARCHAR(100) NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    category ENUM('main_dish', 'side_dish', 'drink', 'extra') DEFAULT 'main_dish',
    stock_quantity INT DEFAULT 0,
    stock_status ENUM('available', 'low', 'finished') DEFAULT 'available',
    low_stock_threshold INT DEFAULT 10,
    image_url VARCHAR(255) DEFAULT NULL,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- ============================================
-- TABLE: orders (Maagizo)
-- ============================================
CREATE TABLE orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_number VARCHAR(20) UNIQUE NOT NULL,
    customer_name VARCHAR(100) NOT NULL,
    customer_phone VARCHAR(20) DEFAULT NULL,
    total_amount DECIMAL(10,2) NOT NULL,
    status ENUM('pending', 'preparing', 'ready', 'completed', 'cancelled') DEFAULT 'pending',
    payment_status ENUM('unpaid', 'paid') DEFAULT 'unpaid',
    payment_method ENUM('cash', 'mobile_money', 'card') DEFAULT 'cash',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    completed_at TIMESTAMP NULL,
    notes TEXT DEFAULT NULL
);

-- ============================================
-- TABLE: order_items (Vitu vya Agizo)
-- ============================================
CREATE TABLE order_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    food_item_id INT NOT NULL,
    quantity INT NOT NULL DEFAULT 1,
    unit_price DECIMAL(10,2) NOT NULL,
    subtotal DECIMAL(10,2) NOT NULL,
    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
    FOREIGN KEY (food_item_id) REFERENCES food_items(id)
);

-- ============================================
-- TABLE: daily_sales (Mauzo ya Kila Siku)
-- ============================================
CREATE TABLE daily_sales (
    id INT AUTO_INCREMENT PRIMARY KEY,
    sale_date DATE NOT NULL,
    total_orders INT DEFAULT 0,
    total_revenue DECIMAL(12,2) DEFAULT 0.00,
    total_items_sold INT DEFAULT 0,
    UNIQUE KEY unique_date (sale_date)
);

-- ============================================
-- TABLE: stock_logs (Kumbukumbu za Stock)
-- ============================================
CREATE TABLE stock_logs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    food_item_id INT NOT NULL,
    quantity_change INT NOT NULL,
    change_type ENUM('addition', 'reduction', 'order_consumption') NOT NULL,
    reason VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (food_item_id) REFERENCES food_items(id)
);

-- ============================================
-- TABLE: users (Watumiaji wa System)
-- ============================================
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    full_name VARCHAR(100) NOT NULL,
    role ENUM('manager', 'cashier', 'cook', 'admin') NOT NULL,
    is_active BOOLEAN DEFAULT TRUE,
    last_login TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- ============================================
-- TABLE: feedback (Maoni ya Wateja)
-- ============================================
CREATE TABLE feedback (
    id INT AUTO_INCREMENT PRIMARY KEY,
    customer_name VARCHAR(100),
    message TEXT NOT NULL,
    rating INT CHECK (rating >= 1 AND rating <= 5),
    category ENUM('service', 'food', 'wait_time', 'other') DEFAULT 'other',
    is_resolved BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- ============================================
-- DEFAULT DATA
-- ============================================

-- Default Food Items (Vyakula vya Kawaida)
INSERT INTO food_items (name, name_sw, price, category, stock_quantity, low_stock_threshold) VALUES
('Rice with Beans', 'Wali na Maharage', 2500.00, 'main_dish', 50, 10),
('Rice with Beef', 'Wali na Nyama', 4500.00, 'main_dish', 30, 8),
('Rice with Chicken', 'Wali na Kuku', 5000.00, 'main_dish', 25, 5),
('Ugali with Beans', 'Ugali na Maharage', 2500.00, 'main_dish', 40, 10),
('Ugali with Beef', 'Ugali na Nyama', 4500.00, 'main_dish', 25, 5),
('Ugali with Chicken', 'Ugali na Kuku', 5000.00, 'main_dish', 20, 5),
('Chapati with Beans', 'Chapati na Maharage', 3000.00, 'main_dish', 35, 8),
('Chapati with Beef', 'Chapati na Nyama', 5000.00, 'main_dish', 20, 5),
('Vegetables', 'Mboga', 1000.00, 'side_dish', 60, 15),
('Salad', 'Saladi', 1500.00, 'side_dish', 40, 10),
('Bottled Water', 'Maji ya Chupa', 1000.00, 'drink', 100, 20),
('Soda', 'Soda', 1500.00, 'drink', 80, 15),
('Fresh Juice', 'Juice Mchanganyiko', 2000.00, 'drink', 30, 8),
('Tea', 'Chai', 800.00, 'drink', 50, 10),
('Coffee', 'Kahawa', 1000.00, 'drink', 40, 8),
('Samosa', 'Samosa', 1000.00, 'extra', 50, 10),
('Mandazi', 'Mandazi', 500.00, 'extra', 60, 15),
('Bread', 'Mkate', 800.00, 'extra', 40, 10),
('Boiled Egg', 'Yai la Kumwa', 800.00, 'extra', 50, 10),
('Fries', 'Chipsi', 2500.00, 'extra', 30, 8);

-- Default Users (Password: 'password123' hashed with bcrypt)
INSERT INTO users (username, password, full_name, role) VALUES
('manager', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Cafeteria Manager', 'manager'),
('cashier1', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Cashier One', 'cashier'),
('cashier2', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Cashier Two', 'cashier'),
('cook1', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Head Cook', 'cook'),
('cook2', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Assistant Cook', 'cook'),
('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'System Admin', 'admin');

-- Indexes for better performance
CREATE INDEX idx_orders_status ON orders(status);
CREATE INDEX idx_orders_created ON orders(created_at);
CREATE INDEX idx_food_active ON food_items(is_active);
CREATE INDEX idx_stock_status ON food_items(stock_status);
