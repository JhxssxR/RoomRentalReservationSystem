<?php
/**
 * Admin Controller
 * Handles admin dashboard and management
 */

class AdminController
{
    public function __construct()
    {
        // Admin authentication check will be done in each method
    }

    /**
     * Sanitize string input (replacement for deprecated FILTER_SANITIZE_STRING)
     */
    private function sanitizeString($value)
    {
        if ($value === null) return '';
        return htmlspecialchars(strip_tags(trim($value)), ENT_QUOTES, 'UTF-8');
    }

    /**
     * Check if user is admin
     */
    private function requireAdmin()
    {
        Auth::requireLogin();
        
        if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
            $_SESSION['error'] = 'Access denied. Admin privileges required.';
            header('Location: ' . BASE_URL . '/');
            exit;
        }
    }

    /**
     * Get notifications for admin
     */
    private function getNotifications()
    {
        require_once __DIR__ . '/../models/Notification.php';
        $notificationModel = new Notification();
        return [
            'notifications' => $notificationModel->getAdminNotifications(10),
            'unread_count' => $notificationModel->getAdminUnreadCount()
        ];
    }

    /**
     * Admin Dashboard
     */
    public function dashboard()
    {
        $this->requireAdmin();

        require_once __DIR__ . '/../models/Report.php';
        $reportModel = new Report();

        // Get dashboard statistics
        $stats = [
            'total_rooms' => $reportModel->getTotalRooms(),
            'total_customers' => $reportModel->getTotalCustomers(),
            'total_reservations' => $reportModel->getTotalReservations(),
            'total_revenue' => $reportModel->getTotalRevenue(),
            'pending_payments' => $reportModel->getPendingPayments(),
            'todays_checkins' => $reportModel->getTodaysCheckins(),
            'todays_checkouts' => $reportModel->getTodaysCheckouts(),
            'occupancy_rate' => $reportModel->getOccupancyRate()
        ];

        // Recent reservations
        require_once __DIR__ . '/../models/Reservation.php';
        $reservationModel = new Reservation();
        $recentReservations = $reservationModel->getRecent(10);

        // Get notifications
        $notificationData = $this->getNotifications();
        $notifications = $notificationData['notifications'];
        $unreadCount = $notificationData['unread_count'];

        include __DIR__ . '/../views/admin/dashboard.php';
    }

    /**
     * Room Management
     */
    public function rooms()
    {
        $this->requireAdmin();

        require_once __DIR__ . '/../models/Room.php';
        $roomModel = new Room();

        // Handle CRUD operations
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $action = $_POST['action'] ?? '';

            switch ($action) {
                case 'create':
                    $this->createRoom($roomModel);
                    break;
                case 'update':
                    $this->updateRoom($roomModel);
                    break;
                case 'delete':
                    $this->deleteRoom($roomModel);
                    break;
            }
        }

        $rooms = $roomModel->getAll();
        
        // Get notifications
        $notificationData = $this->getNotifications();
        $notifications = $notificationData['notifications'];
        $unreadCount = $notificationData['unread_count'];
        
        include __DIR__ . '/../views/admin/rooms.php';
    }

    private function createRoom($roomModel)
    {
        $data = [
            'room_number' => $this->sanitizeString($_POST['room_number'] ?? ''),
            'name' => $this->sanitizeString($_POST['name'] ?? ''),
            'type' => $this->sanitizeString($_POST['type'] ?? ''),
            'description' => $this->sanitizeString($_POST['description'] ?? ''),
            'price_per_night' => filter_input(INPUT_POST, 'price_per_night', FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION),
            'capacity' => filter_input(INPUT_POST, 'capacity', FILTER_SANITIZE_NUMBER_INT),
            'amenities' => $this->sanitizeString($_POST['amenities'] ?? ''),
            'status' => 'available'
        ];

        // Handle image upload
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = __DIR__ . '/../../public/uploads/rooms/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }
            $fileName = uniqid('room_') . '_' . basename($_FILES['image']['name']);
            $targetPath = $uploadDir . $fileName;
            
            if (move_uploaded_file($_FILES['image']['tmp_name'], $targetPath)) {
                $data['image'] = 'uploads/rooms/' . $fileName;
            }
        }

        if ($roomModel->create($data)) {
            $_SESSION['success'] = 'Room created successfully';
        } else {
            $_SESSION['error'] = 'Failed to create room';
        }

        header('Location: ' . BASE_URL . '/admin/rooms');
        exit;
    }

    private function updateRoom($roomModel)
    {
        $id = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT);
        
        $data = [
            'room_number' => $this->sanitizeString($_POST['room_number'] ?? ''),
            'name' => $this->sanitizeString($_POST['name'] ?? ''),
            'type' => $this->sanitizeString($_POST['type'] ?? ''),
            'description' => $this->sanitizeString($_POST['description'] ?? ''),
            'price_per_night' => filter_input(INPUT_POST, 'price_per_night', FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION),
            'capacity' => filter_input(INPUT_POST, 'capacity', FILTER_SANITIZE_NUMBER_INT),
            'amenities' => $this->sanitizeString($_POST['amenities'] ?? ''),
            'status' => $this->sanitizeString($_POST['status'] ?? 'available')
        ];

        // Handle image upload
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = __DIR__ . '/../../public/uploads/rooms/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }
            $fileName = uniqid('room_') . '_' . basename($_FILES['image']['name']);
            $targetPath = $uploadDir . $fileName;
            
            if (move_uploaded_file($_FILES['image']['tmp_name'], $targetPath)) {
                $data['image'] = 'uploads/rooms/' . $fileName;
            }
        }

        if ($roomModel->update($id, $data)) {
            $_SESSION['success'] = 'Room updated successfully';
        } else {
            $_SESSION['error'] = 'Failed to update room';
        }

        header('Location: ' . BASE_URL . '/admin/rooms');
        exit;
    }

    private function deleteRoom($roomModel)
    {
        $id = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT);

        if ($roomModel->delete($id)) {
            $_SESSION['success'] = 'Room deleted successfully';
        } else {
            $_SESSION['error'] = 'Failed to delete room';
        }

        header('Location: ' . BASE_URL . '/admin/rooms');
        exit;
    }

    /**
     * Customer Management
     */
    public function customers()
    {
        $this->requireAdmin();

        require_once __DIR__ . '/../models/Customer.php';
        $customerModel = new Customer();

        // Handle actions
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $action = $_POST['action'] ?? '';
            $id = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT);

            if ($action === 'delete') {
                if ($customerModel->delete($id)) {
                    $_SESSION['success'] = 'Customer deleted successfully';
                } else {
                    $_SESSION['error'] = 'Failed to delete customer';
                }
                header('Location: ' . BASE_URL . '/admin/customers');
                exit;
            }
        }

        $customers = $customerModel->getAll();
        
        // Get notifications
        $notificationData = $this->getNotifications();
        $notifications = $notificationData['notifications'];
        $unreadCount = $notificationData['unread_count'];
        
        include __DIR__ . '/../views/admin/customers.php';
    }

    /**
     * Payment Management
     */
    public function payments()
    {
        $this->requireAdmin();

        require_once __DIR__ . '/../models/Payment.php';
        require_once __DIR__ . '/../models/Reservation.php';
        require_once __DIR__ . '/../models/Notification.php';
        $paymentModel = new Payment();
        $reservationModel = new Reservation();
        $notificationModel = new Notification();

        // Handle payment approval/rejection
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $action = $_POST['action'] ?? '';
            $paymentId = filter_input(INPUT_POST, 'payment_id', FILTER_SANITIZE_NUMBER_INT);

            $payment = $paymentModel->getById($paymentId);

            if ($payment) {
                if ($action === 'approve') {
                    $paymentModel->updateStatus($paymentId, 'approved');
                    $reservationModel->updateStatus($payment['reservation_id'], 'Approved');
                    
                    // Update room status to occupied
                    require_once __DIR__ . '/../models/Room.php';
                    $roomModel = new Room();
                    $reservation = $reservationModel->getById($payment['reservation_id']);
                    if ($reservation && isset($reservation['room_id'])) {
                        $roomModel->updateStatus($reservation['room_id'], 'Occupied');
                    }
                    
                    // Send notification to customer
                    if ($reservation) {
                        $notificationModel->notifyPaymentStatus(
                            $reservation['customer_id'], 
                            'approved', 
                            $payment['reservation_id']
                        );
                    }
                    
                    $_SESSION['success'] = 'Payment approved and reservation confirmed';
                } elseif ($action === 'reject') {
                    $paymentModel->updateStatus($paymentId, 'rejected');
                    $reservationModel->updateStatus($payment['reservation_id'], 'Pending');
                    
                    // Send notification to customer
                    $reservation = $reservationModel->getById($payment['reservation_id']);
                    if ($reservation) {
                        $notificationModel->notifyPaymentStatus(
                            $reservation['customer_id'], 
                            'rejected', 
                            $payment['reservation_id']
                        );
                    }
                    
                    $_SESSION['success'] = 'Payment rejected';
                }
            }

            header('Location: ' . BASE_URL . '/admin/payments');
            exit;
        }

        $payments = $paymentModel->getAll();
        
        // Get notifications
        $notificationData = $this->getNotifications();
        $notifications = $notificationData['notifications'];
        $unreadCount = $notificationData['unread_count'];
        
        include __DIR__ . '/../views/admin/payments.php';
    }

    /**
     * Reports
     */
    public function reports()
    {
        $this->requireAdmin();

        require_once __DIR__ . '/../models/Report.php';
        $reportModel = new Report();

        $reportType = $this->sanitizeString($_GET['type'] ?? '') ?: 'revenue';
        $startDate = $this->sanitizeString($_GET['start_date'] ?? '') ?: date('Y-m-01');
        $endDate = $this->sanitizeString($_GET['end_date'] ?? '') ?: date('Y-m-d');

        switch ($reportType) {
            case 'revenue':
                $reportData = $reportModel->getRevenueReport($startDate, $endDate);
                break;
            case 'occupancy':
                $reportData = $reportModel->getOccupancyReport($startDate, $endDate);
                break;
            case 'customers':
                $reportData = $reportModel->getCustomerReport($startDate, $endDate);
                break;
            case 'reservations':
                $reportData = $reportModel->getReservationReport($startDate, $endDate);
                break;
            default:
                $reportData = [];
        }

        // Get notifications
        $notificationData = $this->getNotifications();
        $notifications = $notificationData['notifications'];
        $unreadCount = $notificationData['unread_count'];

        include __DIR__ . '/../views/admin/reports.php';
    }
}
