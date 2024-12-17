CREATE DATABASE IF NOT EXISTS ecommerce;
USE ecommerce;

CREATE TABLE products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100),
    price DECIMAL(10, 2)
);

INSERT INTO products (name, price) VALUES 
('Product 1', 19.99),
('Product 2', 29.99),
('Product 3', 9.99),
('Product 4', 49.99);