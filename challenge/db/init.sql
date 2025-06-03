SET GLOBAL time_zone = 'Europe/Vienna';
-- Users table with extended profile information
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    email VARCHAR(100),
    profile_image VARCHAR(255),
    role VARCHAR(20) DEFAULT 'user',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Coffee products table
CREATE TABLE products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    description TEXT,
    price DECIMAL(10,2) NOT NULL,
    image VARCHAR(255),
    category VARCHAR(50),
    in_stock BOOLEAN DEFAULT TRUE
);

-- Orders table
CREATE TABLE orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NULL,
    guest_email VARCHAR(100) NULL,
    total_amount DECIMAL(10,2) NOT NULL,
    status VARCHAR(20) DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id)
);

-- Order items
CREATE TABLE order_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    product_id INT NOT NULL,
    quantity INT NOT NULL,
    price_at_time DECIMAL(10,2) NOT NULL,
    FOREIGN KEY (order_id) REFERENCES orders(id),
    FOREIGN KEY (product_id) REFERENCES products(id)
);

-- reviews
create table coffee_reviews
(
    id          int auto_increment
        primary key,
    user_id     int                                 null,
    review_text text                                null,
    created_at  timestamp default CURRENT_TIMESTAMP not null,
    constraint coffee_reviews_ibfk_1
        foreign key (user_id) references users (id)
);

-- Insert test data
INSERT INTO users (username, password, email, role, profile_image) VALUES

('john_doe', 'VGhpc0lzTm90QUZsYWc=', 'john@example.com', 'user', 'user2.png'),
('HeadOfCoffee', 'Q1RGe1N1cGVyU2VjcmV0QWRtaW5Qd2QxMjMhfQ==', 'admin@coffee.local', 'admin','user1.png'),
('jane_smith', 'Tk8tRkxBRw==', 'jane@example.com', 'user', 'user3.png'),
('kira_teagan','Tk9ULWNvcnJlY3Qh','kira@example.com','user','user4.png');

INSERT INTO products (name, description, price, category) VALUES
('Espresso', 'Strong Italian coffee', 2.50, 'hot'),
('Cappuccino', 'Espresso with steamed milk', 3.50, 'hot'),
('Iced Latte', 'Cold coffee with milk', 4.00, 'cold'),
('Mocha', 'Coffee with chocolate', 4.50, 'hot'),
('Cold Brew', '24h brewed cold coffee', 3.75, 'cold');

INSERT INTO coffee_reviews (user_id, review_text) VALUES
(1, 'Espresso is my go-to coffee! Strong, bold, and always consistent. Highly recommended for anyone who loves a quick caffeine boost.'),
(2, 'Iced Latte is great for warm afternoons. Smooth and refreshing, though it could use a bit more coffee flavor.'),
(3, 'Cappuccino is the perfect balance of espresso and milk. The foam is always delightful. Definitely my favorite morning pick-me-up.'),
(4, 'Mocha is a dream come true for chocolate and coffee lovers! It’s like dessert in a cup.');

-- Insert sample orders for users 1 and 3
INSERT INTO orders (user_id, total_amount, status) VALUES
(1, 12.50, 'completed'), -- Order for user 1
(3, 11.75, 'completed');  -- Order for user 3

-- Insert order items for user 1's order (Order ID 1)
INSERT INTO order_items (order_id, product_id, quantity, price_at_time) VALUES
(1, 1, 2, 2.50), -- 2x Espresso at $2.50 each
(1, 2, 1, 3.50), -- 1x Cappuccino at $3.50 each
(1, 4, 1, 4.50); -- 1x Mocha at $4.50 each

-- Insert order items for user 3's order (Order ID 2)
INSERT INTO order_items (order_id, product_id, quantity, price_at_time) VALUES
(2, 3, 2, 4.00), -- 2x Iced Latte at $4.00 each
(2, 5, 1, 3.75); -- 1x Cold Brew at $3.75 each