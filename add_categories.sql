-- Create categories table
CREATE TABLE IF NOT EXISTS categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Add category_id to products table
ALTER TABLE products
ADD COLUMN category_id INT,
ADD FOREIGN KEY (category_id) REFERENCES categories(id);

-- Insert categories
INSERT INTO categories (name, description) VALUES
('Computers', 'Desktop computers, laptops, and accessories'),
('Mobile', 'Smartphones, tablets, and mobile accessories'),
('Audio', 'Headphones, speakers, and audio equipment'),
('Gaming', 'Gaming peripherals and accessories'),
('Storage', 'Hard drives, SSDs, and storage solutions'),
('Accessories', 'General computer and tech accessories');

-- Update existing products with categories
UPDATE products SET category_id = (SELECT id FROM categories WHERE name = 'Mobile') 
WHERE name IN ('Smartphone X', 'Tablet Pro', 'Smart Watch');

UPDATE products SET category_id = (SELECT id FROM categories WHERE name = 'Computers') 
WHERE name IN ('Laptop Pro', 'Graphics Card', 'RAM Kit', 'CPU Cooler');

UPDATE products SET category_id = (SELECT id FROM categories WHERE name = 'Audio') 
WHERE name IN ('Wireless Earbuds', 'Bluetooth Speaker', 'Gaming Headset');

UPDATE products SET category_id = (SELECT id FROM categories WHERE name = 'Gaming') 
WHERE name IN ('Gaming Mouse', 'Mechanical Keyboard');

UPDATE products SET category_id = (SELECT id FROM categories WHERE name = 'Storage') 
WHERE name IN ('External SSD');

UPDATE products SET category_id = (SELECT id FROM categories WHERE name = 'Accessories') 
WHERE name IN ('USB-C Hub', 'Wireless Mouse', 'Webcam HD', 'Power Bank', '4K Monitor'); 