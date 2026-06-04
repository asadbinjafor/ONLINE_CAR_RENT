-- Online Car Rent — database name matches project folder: project5
-- Import this whole file in phpMyAdmin (Import tab).

CREATE DATABASE IF NOT EXISTS project5 CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE project5;

SET FOREIGN_KEY_CHECKS = 0;
DROP TABLE IF EXISTS payments;
DROP TABLE IF EXISTS orders;
DROP TABLE IF EXISTS blogs;
DROP TABLE IF EXISTS cars;
DROP TABLE IF EXISTS users;
SET FOREIGN_KEY_CHECKS = 1;

CREATE TABLE users (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(120) NOT NULL,
    email VARCHAR(180) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    role ENUM('admin', 'member') NOT NULL DEFAULT 'member',
    profile_picture VARCHAR(255) DEFAULT NULL,
    address VARCHAR(255) DEFAULT NULL,
    phone VARCHAR(30) DEFAULT NULL,
    remember_token VARCHAR(255) DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE cars (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(160) NOT NULL,
    model VARCHAR(120) NOT NULL,
    type VARCHAR(80) NOT NULL,
    price_per_day DECIMAL(10,2) NOT NULL,
    availability_status ENUM('available', 'unavailable', 'maintenance') NOT NULL DEFAULT 'available',
    image_path VARCHAR(255) DEFAULT NULL,
    description TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE orders (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id INT UNSIGNED NOT NULL,
    car_id INT UNSIGNED NOT NULL,
    start_date DATE NOT NULL,
    end_date DATE NOT NULL,
    total_cost DECIMAL(10,2) NOT NULL,
    status ENUM('pending', 'confirmed', 'cancelled') NOT NULL DEFAULT 'pending',
    payment_method VARCHAR(50) DEFAULT NULL,
    order_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_order_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    CONSTRAINT fk_order_car FOREIGN KEY (car_id) REFERENCES cars(id) ON DELETE RESTRICT
) ENGINE=InnoDB;

CREATE TABLE payments (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    order_id INT UNSIGNED NOT NULL,
    amount DECIMAL(10,2) NOT NULL,
    payment_method VARCHAR(50) NOT NULL,
    transaction_id VARCHAR(120) DEFAULT NULL,
    payment_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_payment_order FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE blogs (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id INT UNSIGNED NOT NULL,
    title VARCHAR(200) NOT NULL,
    content TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT fk_blog_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- Default password for both accounts: password123
INSERT INTO users (name, email, password_hash, role, address, phone) VALUES
('Admin User', 'admin@carrent.com', '$2y$10$k9TSikQB6boyMVqElv3mwue71wjlO/MqvQxtTMCt1TlkTONsTs0JG', 'admin', 'Dhaka, Bangladesh', '01700000001'),
('John Member', 'member@carrent.com', '$2y$10$k9TSikQB6boyMVqElv3mwue71wjlO/MqvQxtTMCt1TlkTONsTs0JG', 'member', 'Chittagong, Bangladesh', '01800000002');

INSERT INTO cars (name, model, type, price_per_day, availability_status, description) VALUES
('Toyota Corolla', '2023', 'Private car', 3500.00, 'available', 'Comfortable sedan ideal for city driving and short trips.'),
('Hyundai Elantra', '2022', 'Sedan', 3200.00, 'available', 'Fuel-efficient sedan with modern features.'),
('Toyota Hiace', '2021', 'Microbus', 8500.00, 'available', 'Spacious microbus for group travel up to 14 passengers.'),
('Mitsubishi Pickup', '2020', 'Pick-up', 5500.00, 'available', 'Reliable pick-up for cargo and rough roads.'),
('Range Rover Sport', '2024', 'Luxury', 15000.00, 'available', 'Premium SUV with leather interior and advanced safety.'),
('Toyota Fortuner', '2023', 'SUV', 9000.00, 'available', 'Powerful SUV perfect for family adventures.'),
('Nissan Micra', '2019', 'Private car', 2800.00, 'maintenance', 'Compact car for budget-friendly rentals.');

INSERT INTO blogs (user_id, title, content) VALUES
(2, 'Great experience renting a sedan', 'I rented the Toyota Corolla for a weekend trip. Smooth ride, fair pricing, and easy booking process. Highly recommend!');
