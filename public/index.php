<?php
/**
 * Entry Point - Routes to controllers
 * Room Rental Reservation System
 */

// Load configuration FIRST (before session_start)
require_once __DIR__ . '/../config/app.php';

// Start session AFTER config
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Load database
require_once __DIR__ . '/../config/database.php';

// Load helpers
require_once __DIR__ . '/../app/helpers/Auth.php';
require_once __DIR__ . '/../app/helpers/Utils.php';

// Simple Router
$request = $_SERVER['REQUEST_URI'];

// Handle both /RoomRentalReservation and /RoomRentalReservation/public
$basePaths = ['/RoomRentalReservation/public', '/RoomRentalReservation'];
foreach ($basePaths as $basePath) {
    if (strpos($request, $basePath) === 0) {
        $request = substr($request, strlen($basePath));
        break;
    }
}
$request = strtok($request, '?'); // Remove query string
$request = rtrim($request, '/'); // Remove trailing slash
if ($request === '') $request = '/';

// Load controllers
require_once __DIR__ . '/../app/controllers/CustomerController.php';
require_once __DIR__ . '/../app/controllers/RoomController.php';
require_once __DIR__ . '/../app/controllers/ReservationController.php';
require_once __DIR__ . '/../app/controllers/PaymentController.php';
require_once __DIR__ . '/../app/controllers/AdminController.php';
require_once __DIR__ . '/../app/controllers/ReportController.php';

// Route handling
// First check for pattern-based routes
if (preg_match('/^\/rooms\/(\d+)$/', $request, $matches)) {
    $controller = new RoomController();
    $controller->show($matches[1]);
} elseif (preg_match('/^\/rooms\/(\d+)\/book$/', $request, $matches)) {
    $_GET['room_id'] = $matches[1];
    $controller = new ReservationController();
    $controller->create();
} elseif (preg_match('/^\/reservations\/(\d+)$/', $request, $matches)) {
    $controller = new ReservationController();
    $controller->show($matches[1]);
} elseif (preg_match('/^\/reservations\/(\d+)\/cancel$/', $request, $matches)) {
    $controller = new ReservationController();
    $controller->cancel($matches[1]);
} else {
    // Static routes
    switch ($request) {
        case '/':
        case '':
            include __DIR__ . '/../app/views/index.php';
            break;
        
        // Customer routes
        case '/login':
            $controller = new CustomerController();
            $controller->login();
            break;
        
        case '/register':
            $controller = new CustomerController();
            $controller->register();
            break;
        
        case '/dashboard':
            $controller = new CustomerController();
            $controller->dashboard();
            break;
        
        case '/forgot-password':
            $controller = new CustomerController();
            $controller->forgotPassword();
            break;
        
        case '/reset-password':
            $controller = new CustomerController();
            $controller->resetPassword();
            break;
        
        case '/reservations':
            $controller = new ReservationController();
            $controller->index();
            break;
        
        case '/book':
            $controller = new ReservationController();
            $controller->create();
            break;
        
        // Room routes
        case '/rooms':
            $controller = new RoomController();
            $controller->index();
            break;
        
        // Payment routes
        case '/payments':
            $controller = new PaymentController();
            $controller->index();
            break;
        
        case '/payments/process':
            $controller = new PaymentController();
            $controller->process();
            break;
        
        // Admin routes
        case '/admin':
        case '/admin/dashboard':
            $controller = new AdminController();
            $controller->dashboard();
            break;
        
        case '/admin/rooms':
            $controller = new AdminController();
            $controller->rooms();
            break;
        
        case '/admin/customers':
            $controller = new AdminController();
            $controller->customers();
            break;
        
        case '/admin/payments':
            $controller = new AdminController();
            $controller->payments();
            break;
        
        case '/admin/reports':
            $controller = new AdminController();
            $controller->reports();
            break;
        
        case '/admin/reports/pdf':
            $controller = new ReportController();
            $controller->generatePdf();
            break;
        
        case '/logout':
            $controller = new CustomerController();
            $controller->logout();
            break;
        
        default:
            http_response_code(404);
            echo '<h1>404 - Page Not Found</h1>';
            break;
    }
}
