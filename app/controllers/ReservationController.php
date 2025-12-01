<?php
/**
 * Reservation Controller
 * Handles room reservations
 */

class ReservationController
{
    private $reservationModel;
    private $roomModel;

    public function __construct()
    {
        require_once __DIR__ . '/../models/Reservation.php';
        require_once __DIR__ . '/../models/Room.php';
        $this->reservationModel = new Reservation();
        $this->roomModel = new Room();
    }

    /**
     * Display user reservations
     */
    public function index()
    {
        Auth::requireLogin();

        $customerId = $_SESSION['user_id'];
        $reservations = $this->reservationModel->getByCustomerId($customerId);

        include __DIR__ . '/../views/customer/reservations.php';
    }

    /**
     * Create new reservation
     */
    public function create()
    {
        Auth::requireLogin();

        // Get room_id from $_GET (set by router) or from query string
        $roomId = isset($_GET['room_id']) ? intval($_GET['room_id']) : 0;
        
        if ($roomId <= 0) {
            $_SESSION['error'] = 'No room selected';
            header('Location: ' . BASE_URL . '/rooms');
            exit;
        }
        
        $room = $this->roomModel->getById($roomId);

        if (!$room) {
            $_SESSION['error'] = 'Room not found (ID: ' . $roomId . ')';
            header('Location: ' . BASE_URL . '/rooms');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $checkIn = trim($_POST['check_in'] ?? '');
            $checkOut = trim($_POST['check_out'] ?? '');
            $guests = filter_input(INPUT_POST, 'guests', FILTER_SANITIZE_NUMBER_INT) ?: 1;

            $errors = [];

            // Validation
            if (empty($checkIn) || empty($checkOut)) {
                $errors[] = 'Check-in and check-out dates are required';
            }
            if (strtotime($checkOut) <= strtotime($checkIn)) {
                $errors[] = 'Check-out date must be after check-in date';
            }
            if (strtotime($checkIn) < strtotime('today')) {
                $errors[] = 'Check-in date cannot be in the past';
            }
            if (!$this->roomModel->isAvailable($roomId, $checkIn, $checkOut)) {
                $errors[] = 'Room is not available for selected dates';
            }
            if ($guests > $room['capacity']) {
                $errors[] = 'Number of guests exceeds room capacity (' . $room['capacity'] . ' max)';
            }

            if (empty($errors)) {
                // Calculate total price
                $nights = (strtotime($checkOut) - strtotime($checkIn)) / (60 * 60 * 24);
                $totalPrice = $nights * $room['price'];

                $data = [
                    'customer_id' => $_SESSION['user_id'],
                    'room_id' => $roomId,
                    'check_in' => $checkIn,
                    'check_out' => $checkOut,
                    'guests' => $guests,
                    'total_price' => $totalPrice,
                    'status' => 'Pending'
                ];

                $reservationId = $this->reservationModel->create($data);

                if ($reservationId) {
                    $_SESSION['success'] = 'Reservation created successfully! Your booking is pending confirmation.';
                    header('Location: ' . BASE_URL . '/dashboard');
                    exit;
                } else {
                    $errors[] = 'Failed to create reservation. Please try again.';
                }
            }
        }

        include __DIR__ . '/../views/reservations/create.php';
    }

    /**
     * View reservation details
     */
    public function show($id)
    {
        Auth::requireLogin();

        $reservation = $this->reservationModel->getById($id);

        if (!$reservation || $reservation['customer_id'] != $_SESSION['user_id']) {
            $_SESSION['error'] = 'Reservation not found';
            header('Location: ' . BASE_URL . '/reservations');
            exit;
        }

        $room = $this->roomModel->getById($reservation['room_id']);

        include __DIR__ . '/../views/reservations/show.php';
    }

    /**
     * Cancel reservation
     */
    public function cancel($id)
    {
        Auth::requireLogin();

        $reservation = $this->reservationModel->getById($id);

        if (!$reservation || $reservation['customer_id'] != $_SESSION['user_id']) {
            $_SESSION['error'] = 'Reservation not found';
            header('Location: ' . BASE_URL . '/reservations');
            exit;
        }

        if ($reservation['status'] === 'Approved' && strtotime($reservation['check_in']) < strtotime('+24 hours')) {
            $_SESSION['error'] = 'Cannot cancel reservation within 24 hours of check-in';
            header('Location: ' . BASE_URL . '/reservations');
            exit;
        }

        if ($this->reservationModel->updateStatus($id, 'Rejected')) {
            $_SESSION['success'] = 'Reservation cancelled successfully';
        } else {
            $_SESSION['error'] = 'Failed to cancel reservation';
        }

        header('Location: ' . BASE_URL . '/reservations');
        exit;
    }
}
