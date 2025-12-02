<?php
/**
 * Room Controller
 * Handles room listing and details
 */

class RoomController
{
    private $roomModel;

    public function __construct()
    {
        require_once __DIR__ . '/../models/Room.php';
        $this->roomModel = new Room();
    }

    /**
     * Display all available rooms
     */
    public function index()
    {
        $filters = [];

        // Apply filters from query string
        if (!empty($_GET['type'])) {
            $filters['type'] = trim($_GET['type']);
        }
        if (!empty($_GET['min_price'])) {
            $filters['min_price'] = filter_input(INPUT_GET, 'min_price', FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        }
        if (!empty($_GET['max_price'])) {
            $filters['max_price'] = filter_input(INPUT_GET, 'max_price', FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        }
        if (!empty($_GET['capacity'])) {
            $filters['capacity'] = filter_input(INPUT_GET, 'capacity', FILTER_SANITIZE_NUMBER_INT);
        }
        if (!empty($_GET['check_in'])) {
            $filters['check_in'] = trim($_GET['check_in']);
        }
        if (!empty($_GET['check_out'])) {
            $filters['check_out'] = trim($_GET['check_out']);
        }

        $rooms = $this->roomModel->getAvailable($filters);
        $roomTypes = $this->roomModel->getTypes();

        include __DIR__ . '/../views/rooms/index.php';
    }

    /**
     * Display single room details
     */
    public function show($id)
    {
        $room = $this->roomModel->getById($id);

        if (!$room) {
            http_response_code(404);
            echo '<h1>Room not found</h1>';
            return;
        }

        // Get room availability calendar
        require_once __DIR__ . '/../models/Reservation.php';
        $reservationModel = new Reservation();
        $bookedDates = $reservationModel->getBookedDates($id);

        include __DIR__ . '/../views/rooms/show.php';
    }

    /**
     * Search rooms by criteria
     */
    public function search()
    {
        $checkIn = trim($_GET['check_in'] ?? '');
        $checkOut = trim($_GET['check_out'] ?? '');
        $guests = filter_input(INPUT_GET, 'guests', FILTER_SANITIZE_NUMBER_INT) ?: 1;

        $rooms = $this->roomModel->searchAvailable($checkIn, $checkOut, $guests);

        include __DIR__ . '/../views/rooms/search.php';
    }

    /**
     * API: Get room availability
     */
    public function checkAvailability()
    {
        header('Content-Type: application/json');

        $roomId = filter_input(INPUT_GET, 'room_id', FILTER_SANITIZE_NUMBER_INT);
        $checkIn = trim($_GET['check_in'] ?? '');
        $checkOut = trim($_GET['check_out'] ?? '');

        $isAvailable = $this->roomModel->isAvailable($roomId, $checkIn, $checkOut);

        echo json_encode([
            'available' => $isAvailable,
            'room_id' => $roomId,
            'check_in' => $checkIn,
            'check_out' => $checkOut
        ]);
    }
}
