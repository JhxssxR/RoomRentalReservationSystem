<?php

define('DB_HOST', 'localhost');
define('DB_NAME', 'room_rental_db');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_CHARSET', 'utf8mb4');

// PDO options
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

// Create PDO connection
try {
    $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET;
    $pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
} catch (PDOException $e) {
    // Log error and show user-friendly message
    error_log("Database Connection Error: " . $e->getMessage());
    die("Database connection failed. Please try again later.");
}

$createTablesSQL = "
-- Customers table
CREATE TABLE IF NOT EXISTS customers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    phone VARCHAR(20),
    password VARCHAR(255) NOT NULL,
    address TEXT,
    role ENUM('customer', 'admin') DEFAULT 'customer',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Rooms table
CREATE TABLE IF NOT EXISTS rooms (
    id INT AUTO_INCREMENT PRIMARY KEY,
    room_number VARCHAR(20) UNIQUE NOT NULL,
    name VARCHAR(100) NOT NULL,
    type ENUM('Standard', 'Deluxe', 'Suite', 'Family') DEFAULT 'Standard',
    description TEXT,
    price_per_night DECIMAL(10, 2) NOT NULL,
    capacity INT DEFAULT 2,
    amenities TEXT,
    image VARCHAR(255),
    status ENUM('available', 'occupied', 'maintenance') DEFAULT 'available',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Reservations table
CREATE TABLE IF NOT EXISTS reservations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    customer_id INT NOT NULL,
    room_id INT NOT NULL,
    check_in_date DATE NOT NULL,
    check_out_date DATE NOT NULL,
    guests INT DEFAULT 1,
    total_price DECIMAL(10, 2) NOT NULL,
    notes TEXT,
    status ENUM('pending', 'pending_payment', 'confirmed', 'cancelled', 'completed') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (customer_id) REFERENCES customers(id) ON DELETE CASCADE,
    FOREIGN KEY (room_id) REFERENCES rooms(id) ON DELETE CASCADE
);

-- Payments table
CREATE TABLE IF NOT EXISTS payments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    reservation_id INT NOT NULL,
    customer_id INT NOT NULL,
    amount DECIMAL(10, 2) NOT NULL,
    payment_method ENUM('cash', 'gcash', 'bank_transfer', 'credit_card') NOT NULL,
    transaction_id VARCHAR(50),
    receipt_path VARCHAR(255),
    status ENUM('pending', 'approved', 'rejected') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (reservation_id) REFERENCES reservations(id) ON DELETE CASCADE,
    FOREIGN KEY (customer_id) REFERENCES customers(id) ON DELETE CASCADE
);

-- Insert default admin user (password: admin123)
INSERT IGNORE INTO customers (name, email, password, role) VALUES 
('Admin', 'admin@example.com', '\$2y\$10\$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin');

-- Insert sample rooms
INSERT IGNORE INTO rooms (room_number, name, type, description, price_per_night, capacity, amenities, status) VALUES 
('101', 'Cozy Standard', 'Standard', 'A comfortable standard room with basic amenities.', 1500.00, 2, 'WiFi, AC, TV', 'available'),
('102', 'Standard Plus', 'Standard', 'Standard room with extra space and amenities.', 1800.00, 2, 'WiFi, AC, TV, Mini Fridge', 'available'),
('201', 'Deluxe Room', 'Deluxe', 'Spacious deluxe room with premium amenities.', 2500.00, 3, 'WiFi, AC, TV, Mini Fridge, Coffee Maker', 'available'),
('202', 'Deluxe King', 'Deluxe', 'Deluxe room with king-sized bed and city view.', 3000.00, 2, 'WiFi, AC, Smart TV, Mini Bar, Coffee Maker', 'available'),
('301', 'Executive Suite', 'Suite', 'Luxurious suite with separate living area.', 4500.00, 4, 'WiFi, AC, Smart TV, Mini Bar, Jacuzzi, Kitchen', 'available'),
('401', 'Family Room', 'Family', 'Large room perfect for families with children.', 3500.00, 5, 'WiFi, AC, TV, Mini Fridge, Extra Beds', 'available');
";

// Uncomment to create tables (run once)
// try {
//     $pdo->exec($createTablesSQL);
//     echo "Database tables created successfully!";
// } catch (PDOException $e) {
//     echo "Error creating tables: " . $e->getMessage();
// }
