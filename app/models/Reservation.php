<?php
/**
 * Reservation Model
 * Database operations for reservations
 * Auto-detects database column names for compatibility
 */

class Reservation
{
    private $db;
    private $table = 'reservations';
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
            $stmt = $this->db->query("DESCRIBE {$this->table}");
            $cols = $stmt->fetchAll(PDO::FETCH_COLUMN);
            
            $this->columns = [
                'id' => in_array('reservation_id', $cols) ? 'reservation_id' : 'id',
                'check_in' => in_array('check_in_date', $cols) ? 'check_in_date' : 'check_in',
                'check_out' => in_array('check_out_date', $cols) ? 'check_out_date' : 'check_out',
                'has_guests' => in_array('guests', $cols)
            ];
            
            // Detect customer table columns
            $stmt = $this->db->query("DESCRIBE customers");
            $custCols = $stmt->fetchAll(PDO::FETCH_COLUMN);
            $this->columns['customer_id'] = in_array('customer_id', $custCols) ? 'customer_id' : 'id';
            $this->columns['customer_phone'] = in_array('contact', $custCols) ? 'contact' : 'phone';
            
            // Detect room table columns
            $stmt = $this->db->query("DESCRIBE rooms");
            $roomCols = $stmt->fetchAll(PDO::FETCH_COLUMN);
            $this->columns['room_id'] = in_array('room_id', $roomCols) ? 'room_id' : 'id';
            $this->columns['room_type'] = in_array('room_type', $roomCols) ? 'room_type' : 'type';
            $this->columns['room_price'] = in_array('price_per_night', $roomCols) ? 'price_per_night' : 'price';
            $this->columns['room_image'] = in_array('photo_url', $roomCols) ? 'photo_url' : 'image';
            
        } catch (Exception $e) {
            // Fallback defaults
            $this->columns = [
                'id' => 'reservation_id',
                'check_in' => 'check_in',
                'check_out' => 'check_out',
                'has_guests' => false,
                'customer_id' => 'customer_id',
                'customer_phone' => 'contact',
                'room_id' => 'room_id',
                'room_type' => 'room_type',
                'room_price' => 'price',
                'room_image' => 'photo_url'
            ];
        }
    }

    /**
     * Normalize reservation data for consistent field names
     */
    private function normalizeReservation($reservation)
    {
        if (!$reservation) return null;
        
        $idCol = $this->columns['id'];
        $checkInCol = $this->columns['check_in'];
        $checkOutCol = $this->columns['check_out'];
        $roomTypeCol = $this->columns['room_type'];
        $roomImageCol = $this->columns['room_image'];
        
        // Add aliases for field name compatibility
        $reservation['reservation_id'] = $reservation[$idCol] ?? $reservation['id'] ?? $reservation['reservation_id'] ?? null;
        $reservation['check_in'] = $reservation[$checkInCol] ?? $reservation['check_in_date'] ?? $reservation['check_in'] ?? null;
        $reservation['check_out'] = $reservation[$checkOutCol] ?? $reservation['check_out_date'] ?? $reservation['check_out'] ?? null;
        $reservation['room_type'] = $reservation[$roomTypeCol] ?? $reservation['type'] ?? $reservation['room_type'] ?? null;
        $reservation['photo_url'] = $reservation[$roomImageCol] ?? $reservation['image'] ?? $reservation['photo_url'] ?? null;
        
        // Normalize status to consistent format (capitalize first letter)
        if (isset($reservation['status']) && !empty($reservation['status'])) {
            $reservation['status'] = ucfirst(strtolower($reservation['status']));
            // Map common variations - Approved/Confirmed shows as Completed
            $statusMap = [
                'Pending_payment' => 'Pending',
                'Confirmed' => 'Completed',
                'Approved' => 'Completed',
                'Canceled' => 'Rejected',
                'Cancelled' => 'Rejected'
            ];
            if (isset($statusMap[$reservation['status']])) {
                $reservation['status'] = $statusMap[$reservation['status']];
            }
        } else {
            $reservation['status'] = 'Pending';
        }
        
        return $reservation;
    }

    /**
     * Normalize multiple reservations
     */
    private function normalizeReservations($reservations)
    {
        return array_map([$this, 'normalizeReservation'], $reservations);
    }

    /**
     * Get all reservations
     */
    public function getAll()
    {
        $custIdCol = $this->columns['customer_id'];
        $roomIdCol = $this->columns['room_id'];
        $roomTypeCol = $this->columns['room_type'];
        
        $sql = "SELECT r.*, c.name as customer_name, c.email as customer_email, 
                       rm.room_number, rm.{$roomTypeCol} as room_type, rm.name as room_name
                FROM {$this->table} r
                LEFT JOIN customers c ON r.customer_id = c.{$custIdCol}
                LEFT JOIN rooms rm ON r.room_id = rm.{$roomIdCol}
                ORDER BY r.created_at DESC";
        $stmt = $this->db->query($sql);
        return $this->normalizeReservations($stmt->fetchAll(PDO::FETCH_ASSOC));
    }

    /**
     * Get reservation by ID
     */
    public function getById($id)
    {
        $idCol = $this->columns['id'];
        $custIdCol = $this->columns['customer_id'];
        $roomIdCol = $this->columns['room_id'];
        $roomTypeCol = $this->columns['room_type'];
        $roomPriceCol = $this->columns['room_price'];
        $custPhoneCol = $this->columns['customer_phone'];
        
        $sql = "SELECT r.*, c.name as customer_name, c.email as customer_email, c.{$custPhoneCol} as customer_phone,
                       rm.room_number, rm.{$roomTypeCol} as room_type, rm.{$roomPriceCol} as price_per_night
                FROM {$this->table} r
                LEFT JOIN customers c ON r.customer_id = c.{$custIdCol}
                LEFT JOIN rooms rm ON r.room_id = rm.{$roomIdCol}
                WHERE r.{$idCol} = :id LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id' => $id]);
        return $this->normalizeReservation($stmt->fetch(PDO::FETCH_ASSOC));
    }

    /**
     * Get reservations by customer ID
     */
    public function getByCustomerId($customerId)
    {
        $roomIdCol = $this->columns['room_id'];
        $roomTypeCol = $this->columns['room_type'];
        $roomImageCol = $this->columns['room_image'];
        
        $sql = "SELECT r.*, rm.room_number, rm.{$roomTypeCol} as room_type, rm.name, rm.{$roomImageCol} as photo_url
                FROM {$this->table} r
                LEFT JOIN rooms rm ON r.room_id = rm.{$roomIdCol}
                WHERE r.customer_id = :customer_id
                ORDER BY r.created_at DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['customer_id' => $customerId]);
        return $this->normalizeReservations($stmt->fetchAll(PDO::FETCH_ASSOC));
    }

    /**
     * Get recent reservations
     */
    public function getRecent($limit = 10)
    {
        $custIdCol = $this->columns['customer_id'];
        $roomIdCol = $this->columns['room_id'];
        $roomTypeCol = $this->columns['room_type'];
        
        $sql = "SELECT r.*, c.name as customer_name, rm.room_number, rm.{$roomTypeCol} as room_type, rm.name as room_name
                FROM {$this->table} r
                LEFT JOIN customers c ON r.customer_id = c.{$custIdCol}
                LEFT JOIN rooms rm ON r.room_id = rm.{$roomIdCol}
                ORDER BY r.created_at DESC
                LIMIT :limit";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $this->normalizeReservations($stmt->fetchAll(PDO::FETCH_ASSOC));
    }

    /**
     * Create new reservation
     */
    public function create($data)
    {
        $checkInCol = $this->columns['check_in'];
        $checkOutCol = $this->columns['check_out'];
        $hasGuests = $this->columns['has_guests'];
        
        if ($hasGuests) {
            $sql = "INSERT INTO {$this->table} 
                    (customer_id, room_id, {$checkInCol}, {$checkOutCol}, guests, total_price, status, created_at) 
                    VALUES (:customer_id, :room_id, :check_in, :check_out, :guests, :total_price, :status, NOW())";
            
            $stmt = $this->db->prepare($sql);
            
            $result = $stmt->execute([
                'customer_id' => $data['customer_id'],
                'room_id' => $data['room_id'],
                'check_in' => $data['check_in'],
                'check_out' => $data['check_out'],
                'guests' => $data['guests'] ?? 1,
                'total_price' => $data['total_price'],
                'status' => $data['status'] ?? 'Pending'
            ]);
        } else {
            $sql = "INSERT INTO {$this->table} 
                    (customer_id, room_id, {$checkInCol}, {$checkOutCol}, total_price, status, created_at) 
                    VALUES (:customer_id, :room_id, :check_in, :check_out, :total_price, :status, NOW())";
            
            $stmt = $this->db->prepare($sql);
            
            $result = $stmt->execute([
                'customer_id' => $data['customer_id'],
                'room_id' => $data['room_id'],
                'check_in' => $data['check_in'],
                'check_out' => $data['check_out'],
                'total_price' => $data['total_price'],
                'status' => $data['status'] ?? 'Pending'
            ]);
        }

        return $result ? $this->db->lastInsertId() : false;
    }

    /**
     * Update reservation status
     */
    public function updateStatus($id, $status)
    {
        $idCol = $this->columns['id'];
        $sql = "UPDATE {$this->table} SET status = :status WHERE {$idCol} = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute(['id' => $id, 'status' => $status]);
    }

    /**
     * Update reservation
     */
    public function update($id, $data)
    {
        $idCol = $this->columns['id'];
        
        // Map field names to database columns
        $fieldMapping = [
            'check_in' => $this->columns['check_in'],
            'check_out' => $this->columns['check_out'],
            'check_in_date' => $this->columns['check_in'],
            'check_out_date' => $this->columns['check_out']
        ];

        $fields = [];
        $params = ['id' => $id];

        foreach ($data as $key => $value) {
            if ($value !== null) {
                $dbField = $fieldMapping[$key] ?? $key;
                $fields[] = "{$dbField} = :{$dbField}";
                $params[$dbField] = $value;
            }
        }

        if (empty($fields)) {
            return false;
        }

        $sql = "UPDATE {$this->table} SET " . implode(', ', $fields) . " WHERE {$idCol} = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute($params);
    }

    /**
     * Delete reservation
     */
    public function delete($id)
    {
        $idCol = $this->columns['id'];
        $sql = "DELETE FROM {$this->table} WHERE {$idCol} = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute(['id' => $id]);
    }

    /**
     * Get booked dates for a room
     */
    public function getBookedDates($roomId)
    {
        $checkInCol = $this->columns['check_in'];
        $checkOutCol = $this->columns['check_out'];
        
        $sql = "SELECT {$checkInCol} as check_in, {$checkOutCol} as check_out FROM {$this->table} 
                WHERE room_id = :room_id AND status NOT IN ('Rejected', 'Completed', 'cancelled', 'completed')";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['room_id' => $roomId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Get today's check-ins
     */
    public function getTodaysCheckins()
    {
        $checkInCol = $this->columns['check_in'];
        $custIdCol = $this->columns['customer_id'];
        $roomIdCol = $this->columns['room_id'];
        
        $sql = "SELECT r.*, c.name as customer_name, rm.room_number
                FROM {$this->table} r
                LEFT JOIN customers c ON r.customer_id = c.{$custIdCol}
                LEFT JOIN rooms rm ON r.room_id = rm.{$roomIdCol}
                WHERE DATE(r.{$checkInCol}) = CURDATE() AND r.status IN ('Approved', 'confirmed')";
        $stmt = $this->db->query($sql);
        return $this->normalizeReservations($stmt->fetchAll(PDO::FETCH_ASSOC));
    }

    /**
     * Get today's check-outs
     */
    public function getTodaysCheckouts()
    {
        $checkOutCol = $this->columns['check_out'];
        $custIdCol = $this->columns['customer_id'];
        $roomIdCol = $this->columns['room_id'];
        
        $sql = "SELECT r.*, c.name as customer_name, rm.room_number
                FROM {$this->table} r
                LEFT JOIN customers c ON r.customer_id = c.{$custIdCol}
                LEFT JOIN rooms rm ON r.room_id = rm.{$roomIdCol}
                WHERE DATE(r.{$checkOutCol}) = CURDATE() AND r.status IN ('Approved', 'confirmed')";
        $stmt = $this->db->query($sql);
        return $this->normalizeReservations($stmt->fetchAll(PDO::FETCH_ASSOC));
    }

    /**
     * Get reservation count
     */
    public function getCount()
    {
        $sql = "SELECT COUNT(*) as count FROM {$this->table}";
        $stmt = $this->db->query($sql);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['count'];
    }

    /**
     * Get reservations by status
     */
    public function getByStatus($status)
    {
        $custIdCol = $this->columns['customer_id'];
        $roomIdCol = $this->columns['room_id'];
        $roomTypeCol = $this->columns['room_type'];
        
        $sql = "SELECT r.*, c.name as customer_name, rm.room_number, rm.{$roomTypeCol} as room_type, rm.name as room_name
                FROM {$this->table} r
                LEFT JOIN customers c ON r.customer_id = c.{$custIdCol}
                LEFT JOIN rooms rm ON r.room_id = rm.{$roomIdCol}
                WHERE r.status = :status
                ORDER BY r.created_at DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['status' => $status]);
        return $this->normalizeReservations($stmt->fetchAll(PDO::FETCH_ASSOC));
    }
}
