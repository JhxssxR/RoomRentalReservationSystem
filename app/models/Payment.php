<?php
/**
 * Payment Model
 * Database operations for payments
 * Auto-detects database column names for compatibility
 */

class Payment
{
    private $db;
    private $table = 'payments';
    private $columns = [];

    public function __construct()
    {
        global $pdo;
        $this->db = $pdo;
        $this->detectColumns();
    }

    /**
     * Detect actual column names in the database
     */
    private function detectColumns()
    {
        try {
            // Detect payment columns
            $stmt = $this->db->query("DESCRIBE {$this->table}");
            $paymentCols = $stmt->fetchAll(PDO::FETCH_COLUMN);
            
            // Detect reservation columns
            $stmt2 = $this->db->query("DESCRIBE reservations");
            $resCols = $stmt2->fetchAll(PDO::FETCH_COLUMN);
            
            // Detect room columns
            $stmt3 = $this->db->query("DESCRIBE rooms");
            $roomCols = $stmt3->fetchAll(PDO::FETCH_COLUMN);
            
            // Detect customer columns
            $stmt4 = $this->db->query("DESCRIBE customers");
            $custCols = $stmt4->fetchAll(PDO::FETCH_COLUMN);
            
            $this->columns = [
                'payment_id' => in_array('payment_id', $paymentCols) ? 'payment_id' : 'id',
                'check_in' => in_array('check_in', $resCols) ? 'check_in' : 'check_in_date',
                'check_out' => in_array('check_out', $resCols) ? 'check_out' : 'check_out_date',
                'room_type' => in_array('room_type', $roomCols) ? 'room_type' : 'type',
                'customer_id' => in_array('customer_id', $custCols) ? 'customer_id' : 'id',
                'contact' => in_array('contact', $custCols) ? 'contact' : 'phone'
            ];
        } catch (Exception $e) {
            $this->columns = [
                'payment_id' => 'payment_id',
                'check_in' => 'check_in',
                'check_out' => 'check_out',
                'room_type' => 'room_type',
                'customer_id' => 'customer_id',
                'contact' => 'contact'
            ];
        }
    }

    /**
     * Get all payments
     */
    public function getAll()
    {
        $checkIn = $this->columns['check_in'];
        $checkOut = $this->columns['check_out'];
        $roomType = $this->columns['room_type'];
        $custId = $this->columns['customer_id'];
        
        $sql = "SELECT p.*, r.{$checkIn} as check_in, r.{$checkOut} as check_out, r.total_price as reservation_total,
                       c.name as customer_name, c.email as customer_email,
                       rm.room_number, rm.{$roomType} as room_name
                FROM {$this->table} p
                LEFT JOIN reservations r ON p.reservation_id = r.reservation_id
                LEFT JOIN customers c ON r.{$custId} = c.{$custId}
                LEFT JOIN rooms rm ON r.room_id = rm.room_id
                ORDER BY p.payment_date DESC";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Get payment by ID
     */
    public function getById($id)
    {
        $checkIn = $this->columns['check_in'];
        $checkOut = $this->columns['check_out'];
        $roomType = $this->columns['room_type'];
        $custId = $this->columns['customer_id'];
        $contact = $this->columns['contact'];
        $paymentId = $this->columns['payment_id'];
        
        $sql = "SELECT p.*, r.{$checkIn} as check_in, r.{$checkOut} as check_out, r.total_price as reservation_total,
                       r.customer_id as customer_id,
                       c.name as customer_name, c.email as customer_email, c.{$contact} as customer_phone,
                       rm.room_number, rm.{$roomType} as room_type
                FROM {$this->table} p
                LEFT JOIN reservations r ON p.reservation_id = r.reservation_id
                LEFT JOIN customers c ON r.customer_id = c.{$custId}
                LEFT JOIN rooms rm ON r.room_id = rm.room_id
                WHERE p.{$paymentId} = :id LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Get payments by customer ID
     */
    public function getByCustomerId($customerId)
    {
        $checkIn = $this->columns['check_in'];
        $checkOut = $this->columns['check_out'];
        $roomType = $this->columns['room_type'];
        $custId = $this->columns['customer_id'];
        
        $sql = "SELECT p.*, r.{$checkIn} as check_in, r.{$checkOut} as check_out,
                       rm.room_number, rm.{$roomType} as room_name
                FROM {$this->table} p
                LEFT JOIN reservations r ON p.reservation_id = r.reservation_id
                LEFT JOIN rooms rm ON r.room_id = rm.room_id
                WHERE r.{$custId} = :customer_id
                ORDER BY p.payment_date DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['customer_id' => $customerId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Get payment by reservation ID
     */
    public function getByReservationId($reservationId)
    {
        $sql = "SELECT * FROM {$this->table} WHERE reservation_id = :reservation_id ORDER BY payment_date DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['reservation_id' => $reservationId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Create new payment
     */
    public function create($data)
    {
        $sql = "INSERT INTO {$this->table} 
                (reservation_id, amount, status, receipt_url, payment_date) 
                VALUES (:reservation_id, :amount, :status, :receipt_url, NOW())";
        
        $stmt = $this->db->prepare($sql);
        
        $result = $stmt->execute([
            'reservation_id' => $data['reservation_id'],
            'amount' => $data['amount'],
            'status' => $data['status'] ?? 'Pending',
            'receipt_url' => $data['receipt_url'] ?? null
        ]);

        return $result ? $this->db->lastInsertId() : false;
    }

    /**
     * Update payment status
     */
    public function updateStatus($id, $status)
    {
        $paymentId = $this->columns['payment_id'];
        $sql = "UPDATE {$this->table} SET status = :status WHERE {$paymentId} = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute(['id' => $id, 'status' => $status]);
    }

    /**
     * Update payment
     */
    public function update($id, $data)
    {
        $paymentId = $this->columns['payment_id'];
        $fields = [];
        $params = ['id' => $id];

        foreach ($data as $key => $value) {
            if ($value !== null) {
                $fields[] = "{$key} = :{$key}";
                $params[$key] = $value;
            }
        }

        if (empty($fields)) {
            return false;
        }

        $sql = "UPDATE {$this->table} SET " . implode(', ', $fields) . " WHERE {$paymentId} = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute($params);
    }

    /**
     * Delete payment
     */
    public function delete($id)
    {
        $paymentId = $this->columns['payment_id'];
        $sql = "DELETE FROM {$this->table} WHERE {$paymentId} = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute(['id' => $id]);
    }

    /**
     * Get pending payments count
     */
    public function getPendingCount()
    {
        $sql = "SELECT COUNT(*) as count FROM {$this->table} WHERE status = 'Pending'";
        $stmt = $this->db->query($sql);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['count'];
    }

    /**
     * Get total revenue
     */
    public function getTotalRevenue()
    {
        $sql = "SELECT SUM(amount) as total FROM {$this->table} WHERE status = 'Confirmed'";
        $stmt = $this->db->query($sql);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total'] ?? 0;
    }

    /**
     * Get revenue for date range
     */
    public function getRevenueByDateRange($startDate, $endDate)
    {
        $sql = "SELECT SUM(amount) as total FROM {$this->table} 
                WHERE status = 'Confirmed' 
                AND DATE(payment_date) BETWEEN :start_date AND :end_date";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['start_date' => $startDate, 'end_date' => $endDate]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total'] ?? 0;
    }

    /**
     * Get payments by status
     */
    public function getByStatus($status)
    {
        $custId = $this->columns['customer_id'];
        $roomType = $this->columns['room_type'];
        
        $sql = "SELECT p.*, c.name as customer_name, rm.room_number
                FROM {$this->table} p
                LEFT JOIN reservations r ON p.reservation_id = r.reservation_id
                LEFT JOIN customers c ON r.{$custId} = c.{$custId}
                LEFT JOIN rooms rm ON r.room_id = rm.room_id
                WHERE p.status = :status
                ORDER BY p.payment_date DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['status' => $status]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
