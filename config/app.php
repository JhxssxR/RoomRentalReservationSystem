<?php
/**
 * Application Configuration
 * Global Settings
 */

// Application Settings
define('APP_NAME', 'Room Rental Reservation');
define('APP_VERSION', '1.0.0');
define('APP_ENV', 'development'); // development, production

// Base URL Configuration
$protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
$host = $_SERVER['HTTP_HOST'] ?? 'localhost';
define('BASE_URL', $protocol . '://' . $host . '/RoomRentalReservation/public');

// Timezone
date_default_timezone_set('Asia/Manila');

// Error Reporting
if (APP_ENV === 'development') {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
} else {
    error_reporting(0);
    ini_set('display_errors', 0);
}

// Session Configuration (only set if session not started)
if (session_status() === PHP_SESSION_NONE) {
    ini_set('session.cookie_httponly', 1);
    ini_set('session.use_only_cookies', 1);
    if ($protocol === 'https') {
        ini_set('session.cookie_secure', 1);
    }
}

// Upload Settings
define('UPLOAD_MAX_SIZE', 5 * 1024 * 1024); // 5MB
define('UPLOAD_PATH', __DIR__ . '/../public/uploads');
define('ALLOWED_IMAGE_TYPES', ['image/jpeg', 'image/png', 'image/gif', 'image/webp']);

// Email Settings
define('MAIL_FROM', 'noreply@roomrental.com');
define('MAIL_FROM_NAME', 'Room Rental');

// Pagination
define('ITEMS_PER_PAGE', 10);

// Currency
define('CURRENCY', 'PHP');
define('CURRENCY_SYMBOL', '₱');

// Reservation Settings
define('MIN_BOOKING_DAYS', 1);
define('MAX_BOOKING_DAYS', 30);
define('CANCELLATION_HOURS', 24); // Hours before check-in when cancellation is not allowed

// Room Types
define('ROOM_TYPES', [
    'Standard' => 'Standard Room',
    'Deluxe' => 'Deluxe Room',
    'Suite' => 'Suite',
    'Family' => 'Family Room'
]);

// Payment Methods
define('PAYMENT_METHODS', [
    'cash' => 'Cash Payment',
    'gcash' => 'GCash',
    'bank_transfer' => 'Bank Transfer',
    'credit_card' => 'Credit Card'
]);

// Reservation Statuses
define('RESERVATION_STATUSES', [
    'pending' => 'Pending',
    'pending_payment' => 'Pending Payment',
    'confirmed' => 'Confirmed',
    'cancelled' => 'Cancelled',
    'completed' => 'Completed'
]);

// Payment Statuses
define('PAYMENT_STATUSES', [
    'pending' => 'Pending',
    'approved' => 'Approved',
    'rejected' => 'Rejected'
]);
