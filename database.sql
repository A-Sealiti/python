CREATE DATABASE IF NOT EXISTS product_reviews;
USE product_reviews;

CREATE TABLE IF NOT EXISTS products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    description TEXT,
    price DECIMAL(10,2) NOT NULL,
    image_url VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS reviews (
    id INT AUTO_INCREMENT PRIMARY KEY,
    product_id INT NOT NULL,
    user_name VARCHAR(100) NOT NULL,
    rating INT NOT NULL CHECK (rating >= 1 AND rating <= 5),
    comment TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (product_id) REFERENCES products(id)
);

-- Insert sample products
INSERT INTO products (name, description, price, image_url) VALUES
('Smartphone X', 'Latest smartphone with amazing features', 699.99, 'images/phone.jpg'),
('Laptop Pro', 'Professional laptop for all your needs', 1299.99, 'images/laptop.jpg'),
('Wireless Earbuds', 'High-quality wireless earbuds with noise cancellation', 149.99, 'images/earbuds.jpg'); 