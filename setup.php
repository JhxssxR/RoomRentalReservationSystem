<?php
/**
 * Database Setup Script
 * Run this file once to create all database tables
 * Access: http://localhost/RoomRentalReservation/setup.php
 */

// Database credentials
$host = 'localhost';
$dbname = 'room_rental_db';
$username = 'root';
$password = '';

echo "<h1>Room Rental Database Setup</h1>";

try {
    // First connect without database to create it if needed
    $pdo = new PDO("mysql:host=$host", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Create database if not exists
    $pdo->exec("CREATE DATABASE IF NOT EXISTS `$dbname` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
    echo "<p style='color:green'>✓ Database '$dbname' created/verified</p>";
    
    // Connect to the database
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Create customers table
    $pdo->exec("
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
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
    ");
    echo "<p style='color:green'>✓ Table 'customers' created</p>";
    
    // Create rooms table
    $pdo->exec("
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
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
    ");
    echo "<p style='color:green'>✓ Table 'rooms' created</p>";
    
    // Create reservations table
    $pdo->exec("
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
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
    ");
    echo "<p style='color:green'>✓ Table 'reservations' created</p>";
    
    // Create payments table
    $pdo->exec("
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
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
    ");
    echo "<p style='color:green'>✓ Table 'payments' created</p>";
    
    // Insert default admin user (password: admin123)
    $adminPassword = password_hash('admin123', PASSWORD_DEFAULT);
    $stmt = $pdo->prepare("INSERT IGNORE INTO customers (name, email, password, role) VALUES (?, ?, ?, ?)");
    $stmt->execute(['Admin', 'admin@example.com', $adminPassword, 'admin']);
    echo "<p style='color:green'>✓ Default admin user created (email: admin@example.com, password: admin123)</p>";
    
    // Insert sample rooms
    $rooms = [
        ['101', 'Cozy Standard', 'Standard', 'A comfortable standard room with basic amenities. Perfect for solo travelers or couples looking for a budget-friendly option.', 1500.00, 2, 'WiFi, AC, TV, Private Bathroom'],
        ['102', 'Standard Plus', 'Standard', 'Standard room with extra space and amenities. Includes a work desk and mini refrigerator.', 1800.00, 2, 'WiFi, AC, TV, Mini Fridge, Work Desk'],
        ['201', 'Deluxe Room', 'Deluxe', 'Spacious deluxe room with premium amenities and city view. Ideal for business travelers.', 2500.00, 3, 'WiFi, AC, Smart TV, Mini Fridge, Coffee Maker, City View'],
        ['202', 'Deluxe King', 'Deluxe', 'Deluxe room featuring a king-sized bed with stunning city view and premium bathroom.', 3000.00, 2, 'WiFi, AC, Smart TV, Mini Bar, Coffee Maker, Bathtub'],
        ['301', 'Executive Suite', 'Suite', 'Luxurious suite with separate living area, premium amenities, and panoramic views.', 4500.00, 4, 'WiFi, AC, Smart TV, Mini Bar, Jacuzzi, Kitchen, Living Room'],
        ['401', 'Family Room', 'Family', 'Large room perfect for families with children. Features multiple beds and kid-friendly amenities.', 3500.00, 5, 'WiFi, AC, TV, Mini Fridge, Extra Beds, Kids Area']
    ];
    
    $stmt = $pdo->prepare("INSERT IGNORE INTO rooms (room_number, name, type, description, price_per_night, capacity, amenities, status) VALUES (?, ?, ?, ?, ?, ?, ?, 'available')");
    foreach ($rooms as $room) {
        $stmt->execute($room);
    }
    echo "<p style='color:green'>✓ Sample rooms inserted (6 rooms)</p>";
    
    echo "<hr>";
    echo "<h2 style='color:green'>✓ Setup Complete!</h2>";
    echo "<p><strong>You can now:</strong></p>";
    echo "<ul>";
    echo "<li><a href='/RoomRentalReservation/'>Go to Homepage</a></li>";
    echo "<li><a href='/RoomRentalReservation/login'>Login as Admin</a> (email: admin@example.com, password: admin123)</li>";
    echo "<li><a href='/RoomRentalReservation/register'>Register as Customer</a></li>";
    echo "</ul>";
    echo "<p style='color:orange'><strong>Note:</strong> Delete this setup.php file after setup for security!</p>";
    
} catch (PDOException $e) {
    echo "<p style='color:red'>✗ Error: " . $e->getMessage() . "</p>";
}
?>
