<?php
/**
 * Payment Controller
 * Handles payment processing
 */

class PaymentController
{
    private $paymentModel;
    private $reservationModel;

    public function __construct()
    {
        require_once __DIR__ . '/../models/Payment.php';
        require_once __DIR__ . '/../models/Reservation.php';
        $this->paymentModel = new Payment();
        $this->reservationModel = new Reservation();
    }

    /**
     * Display payment page
     */
    public function index()
    {
        Auth::requireLogin();

        $reservationId = filter_input(INPUT_GET, 'reservation_id', FILTER_SANITIZE_NUMBER_INT);

        if ($reservationId) {
            $reservation = $this->reservationModel->getById($reservationId);

            if (!$reservation || $reservation['customer_id'] != $_SESSION['user_id']) {
                $_SESSION['error'] = 'Reservation not found';
                header('Location: ' . BASE_URL . '/reservations');
                exit;
            }

            include __DIR__ . '/../views/payments/create.php';
        } else {
            // Show payment history
            $payments = $this->paymentModel->getByCustomerId($_SESSION['user_id']);
            include __DIR__ . '/../views/payments/index.php';
        }
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
     * Process payment
     */
    public function process()
    {
        Auth::requireLogin();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . '/reservations');
            exit;
        }

        $reservationId = filter_input(INPUT_POST, 'reservation_id', FILTER_SANITIZE_NUMBER_INT);
        $paymentMethod = $this->sanitizeString($_POST['payment_method'] ?? '');
        $amount = filter_input(INPUT_POST, 'amount', FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);

        $reservation = $this->reservationModel->getById($reservationId);

        if (!$reservation || $reservation['customer_id'] != $_SESSION['user_id']) {
            $_SESSION['error'] = 'Invalid reservation';
            header('Location: ' . BASE_URL . '/reservations');
            exit;
        }

        $errors = [];

        // Handle file upload for receipt
        $receiptPath = null;
        if (isset($_FILES['receipt']) && $_FILES['receipt']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = __DIR__ . '/../../public/uploads/receipts/';
            
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }

            $fileName = uniqid('receipt_') . '_' . basename($_FILES['receipt']['name']);
            $targetPath = $uploadDir . $fileName;

            $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'application/pdf'];
            if (!in_array($_FILES['receipt']['type'], $allowedTypes)) {
                $errors[] = 'Invalid file type. Please upload an image or PDF.';
            } elseif ($_FILES['receipt']['size'] > 5 * 1024 * 1024) {
                $errors[] = 'File size must be less than 5MB';
            } elseif (move_uploaded_file($_FILES['receipt']['tmp_name'], $targetPath)) {
                $receiptPath = 'uploads/receipts/' . $fileName;
            } else {
                $errors[] = 'Failed to upload receipt';
            }
        }

        if (empty($errors)) {
            $data = [
                'reservation_id' => $reservationId,
                'customer_id' => $_SESSION['user_id'],
                'amount' => $amount,
                'payment_method' => $paymentMethod,
                'receipt_path' => $receiptPath,
                'status' => 'pending',
                'transaction_id' => 'TXN' . time() . rand(1000, 9999)
            ];

            $paymentId = $this->paymentModel->create($data);

            if ($paymentId) {
                // Update reservation status
                $this->reservationModel->updateStatus($reservationId, 'pending_payment');

                $_SESSION['success'] = 'Payment submitted successfully! Awaiting admin approval.';
                header('Location: ' . BASE_URL . '/reservations');
                exit;
            } else {
                $errors[] = 'Failed to process payment. Please try again.';
            }
        }

        $_SESSION['errors'] = $errors;
        header('Location: ' . BASE_URL . '/payments?reservation_id=' . $reservationId);
        exit;
    }

    /**
     * View payment receipt
     */
    public function receipt($id)
    {
        Auth::requireLogin();

        $payment = $this->paymentModel->getById($id);

        if (!$payment || $payment['customer_id'] != $_SESSION['user_id']) {
            $_SESSION['error'] = 'Payment not found';
            header('Location: ' . BASE_URL . '/payments');
            exit;
        }

        $reservation = $this->reservationModel->getById($payment['reservation_id']);

        include __DIR__ . '/../views/payments/receipt.php';
    }
}
