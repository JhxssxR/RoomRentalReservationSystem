<?php
/**
 * Room Model
 * Database operations for rooms
 * Auto-detects database column names for compatibility
 */

class Room
{
    private $db;
    private $table = 'rooms';
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
            
            // Store all existing columns for validation
            $this->columns = [
                'id' => in_array('room_id', $cols) ? 'room_id' : 'id',
                'type' => in_array('room_type', $cols) ? 'room_type' : 'type',
                'price' => in_array('price_per_night', $cols) ? 'price_per_night' : 'price',
                'image' => in_array('photo_url', $cols) ? 'photo_url' : 'image',
                'status_available' => $this->detectStatusValue(),
                'existing_columns' => $cols // Store all column names
            ];
        } catch (Exception $e) {
            // Fallback to common names
            $this->columns = [
                'id' => 'room_id',
                'type' => 'room_type', 
                'price' => 'price',
                'image' => 'photo_url',
                'status_available' => 'Available',
                'existing_columns' => ['room_id', 'room_number', 'name', 'room_type', 'price', 'capacity', 'status', 'photo_url']
            ];
        }
    }

    /**
     * Detect what value is used for available status
     */
    private function detectStatusValue()
    {
        try {
            $stmt = $this->db->query("SELECT status FROM {$this->table} LIMIT 1");
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($row && isset($row['status'])) {
                // Check if it's capitalized or lowercase
                return (strtolower($row['status']) === $row['status']) ? 'available' : 'Available';
            }
        } catch (Exception $e) {}
        return 'Available';
    }

    /**
     * Normalize room data to use consistent field names across the app
     */
    private function normalizeRoom($room)
    {
        if (!$room || !is_array($room)) return null;
        
        $idCol = $this->columns['id'];
        $typeCol = $this->columns['type'];
        $priceCol = $this->columns['price'];
        $imageCol = $this->columns['image'];
        
        // Get the ID from various possible column names
        $roomId = $room[$idCol] ?? $room['id'] ?? $room['room_id'] ?? null;
        
        return [
            'room_id' => $roomId,
            'room_number' => $room['room_number'] ?? $roomId,
            'name' => $room['name'] ?? ('Room ' . ($room['room_number'] ?? $roomId)),
            'room_type' => $room[$typeCol] ?? $room['type'] ?? $room['room_type'] ?? 'Standard',
            'description' => $room['description'] ?? '',
            'price' => $room[$priceCol] ?? $room['price'] ?? $room['price_per_night'] ?? 0,
            'capacity' => $room['capacity'] ?? 2,
            'amenities' => $room['amenities'] ?? '',
            'photo_url' => $room[$imageCol] ?? $room['photo_url'] ?? $room['image'] ?? null,
            'status' => ucfirst($room['status'] ?? 'available'),
            'created_at' => $room['created_at'] ?? null,
            'updated_at' => $room['updated_at'] ?? null
        ];
    }

    /**
     * Normalize multiple rooms
     */
    private function normalizeRooms($rooms)
    {
        return array_map([$this, 'normalizeRoom'], $rooms);
    }

    /**
     * Get all rooms
     */
    public function getAll()
    {
        $sql = "SELECT * FROM {$this->table} ORDER BY room_number ASC";
        $stmt = $this->db->query($sql);
        return $this->normalizeRooms($stmt->fetchAll(PDO::FETCH_ASSOC));
    }

    /**
     * Get available rooms with filters
     */
    public function getAvailable($filters = [])
    {
        $typeCol = $this->columns['type'];
        $priceCol = $this->columns['price'];
        $idCol = $this->columns['id'];
        
        // Try to match available status with case-insensitive comparison
        $sql = "SELECT * FROM {$this->table} WHERE LOWER(status) = 'available'";
        $params = [];

        if (!empty($filters['type'])) {
            $sql .= " AND {$typeCol} = :type";
            $params['type'] = $filters['type'];
        }

        if (!empty($filters['min_price'])) {
            $sql .= " AND {$priceCol} >= :min_price";
            $params['min_price'] = $filters['min_price'];
        }

        if (!empty($filters['max_price'])) {
            $sql .= " AND {$priceCol} <= :max_price";
            $params['max_price'] = $filters['max_price'];
        }

        if (!empty($filters['capacity'])) {
            $sql .= " AND capacity >= :capacity";
            $params['capacity'] = $filters['capacity'];
        }

        // Filter by date availability (exclude rooms with overlapping reservations)
        if (!empty($filters['check_in']) && !empty($filters['check_out'])) {
            // Detect reservation column names
            $checkInCol = 'check_in_date';
            $checkOutCol = 'check_out_date';
            try {
                $stmt = $this->db->query("DESCRIBE reservations");
                $cols = $stmt->fetchAll(PDO::FETCH_COLUMN);
                if (in_array('check_in', $cols)) {
                    $checkInCol = 'check_in';
                    $checkOutCol = 'check_out';
                }
            } catch (Exception $e) {}

            $sql .= " AND {$idCol} NOT IN (
                SELECT room_id FROM reservations 
                WHERE status NOT IN ('cancelled', 'completed', 'Cancelled', 'Completed', 'rejected', 'Rejected')
                AND (
                    ({$checkInCol} <= :check_in AND {$checkOutCol} > :check_in2)
                    OR ({$checkInCol} < :check_out AND {$checkOutCol} >= :check_out2)
                    OR ({$checkInCol} >= :check_in3 AND {$checkOutCol} <= :check_out3)
                )
            )";
            $params['check_in'] = $filters['check_in'];
            $params['check_in2'] = $filters['check_in'];
            $params['check_in3'] = $filters['check_in'];
            $params['check_out'] = $filters['check_out'];
            $params['check_out2'] = $filters['check_out'];
            $params['check_out3'] = $filters['check_out'];
        }

        $sql .= " ORDER BY {$priceCol} ASC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $this->normalizeRooms($stmt->fetchAll(PDO::FETCH_ASSOC));
    }

    /**
     * Get room by ID
     */
    public function getById($id)
    {
        if (empty($id)) {
            return null;
        }
        
        $idCol = $this->columns['id'];
        
        // Try with detected column first
        $sql = "SELECT * FROM {$this->table} WHERE {$idCol} = :id LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id' => $id]);
        $room = $stmt->fetch(PDO::FETCH_ASSOC);
        
        // If not found and we used 'id', try 'room_id' as fallback
        if (!$room && $idCol === 'id') {
            $sql = "SELECT * FROM {$this->table} WHERE room_id = :id LIMIT 1";
            $stmt = $this->db->prepare($sql);
            $stmt->execute(['id' => $id]);
            $room = $stmt->fetch(PDO::FETCH_ASSOC);
        }
        
        // If still not found and we used 'room_id', try 'id' as fallback  
        if (!$room && $idCol === 'room_id') {
            $sql = "SELECT * FROM {$this->table} WHERE id = :id LIMIT 1";
            $stmt = $this->db->prepare($sql);
            $stmt->execute(['id' => $id]);
            $room = $stmt->fetch(PDO::FETCH_ASSOC);
        }
        
        return $this->normalizeRoom($room);
    }

    /**
     * Create new room
     */
    public function create($data)
    {
        $idCol = $this->columns['id'];
        $typeCol = $this->columns['type'];
        $priceCol = $this->columns['price'];
        $imageCol = $this->columns['image'];
        $statusVal = strtolower($this->columns['status_available']);
        $existingCols = $this->columns['existing_columns'] ?? [];
        
        // Build dynamic insert based on existing columns
        $columns = [];
        $placeholders = [];
        $values = [];
        
        // Room number (required)
        if (empty($existingCols) || in_array('room_number', $existingCols)) {
            $columns[] = 'room_number';
            $placeholders[] = ':room_number';
            $values['room_number'] = $data['room_number'];
        }
        
        // Name
        if (empty($existingCols) || in_array('name', $existingCols)) {
            $columns[] = 'name';
            $placeholders[] = ':name';
            $values['name'] = $data['name'] ?? 'Room ' . $data['room_number'];
        }
        
        // Type
        if (empty($existingCols) || in_array($typeCol, $existingCols)) {
            $columns[] = $typeCol;
            $placeholders[] = ':type';
            $values['type'] = $data['room_type'] ?? $data['type'] ?? 'Standard';
        }
        
        // Description (only if column exists)
        if (in_array('description', $existingCols)) {
            $columns[] = 'description';
            $placeholders[] = ':description';
            $values['description'] = $data['description'] ?? '';
        }
        
        // Price
        if (empty($existingCols) || in_array($priceCol, $existingCols)) {
            $columns[] = $priceCol;
            $placeholders[] = ':price';
            $values['price'] = $data['price'] ?? $data['price_per_night'] ?? 0;
        }
        
        // Capacity
        if (empty($existingCols) || in_array('capacity', $existingCols)) {
            $columns[] = 'capacity';
            $placeholders[] = ':capacity';
            $values['capacity'] = $data['capacity'] ?? 2;
        }
        
        // Amenities (only if column exists)
        if (in_array('amenities', $existingCols)) {
            $columns[] = 'amenities';
            $placeholders[] = ':amenities';
            $values['amenities'] = $data['amenities'] ?? '';
        }
        
        // Image
        if (empty($existingCols) || in_array($imageCol, $existingCols)) {
            $columns[] = $imageCol;
            $placeholders[] = ':image';
            $values['image'] = $data['photo_url'] ?? $data['image'] ?? null;
        }
        
        // Status
        if (empty($existingCols) || in_array('status', $existingCols)) {
            $columns[] = 'status';
            $placeholders[] = ':status';
            $values['status'] = $statusVal;
        }
        
        $sql = "INSERT INTO {$this->table} (" . implode(', ', $columns) . ") 
                VALUES (" . implode(', ', $placeholders) . ")";
        
        $stmt = $this->db->prepare($sql);
        $result = $stmt->execute($values);

        return $result ? $this->db->lastInsertId() : false;
    }

    /**
     * Update room
     */
    public function update($id, $data)
    {
        $idCol = $this->columns['id'];
        $existingCols = $this->columns['existing_columns'] ?? [];
        
        // Map incoming field names to database column names
        $fieldMapping = [
            'room_type' => $this->columns['type'],
            'type' => $this->columns['type'],
            'price' => $this->columns['price'],
            'price_per_night' => $this->columns['price'],
            'photo_url' => $this->columns['image'],
            'image' => $this->columns['image']
        ];

        $fields = [];
        $params = ['id' => $id];

        foreach ($data as $key => $value) {
            if ($value !== null) {
                $dbField = $fieldMapping[$key] ?? $key;
                
                // Only include fields that actually exist in the database
                if (!empty($existingCols) && !in_array($dbField, $existingCols)) {
                    continue; // Skip non-existent columns
                }
                
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
     * Delete room
     */
    public function delete($id)
    {
        $idCol = $this->columns['id'];
        $sql = "DELETE FROM {$this->table} WHERE {$idCol} = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute(['id' => $id]);
    }

    /**
     * Check room availability for date range
     */
    public function isAvailable($roomId, $checkIn, $checkOut)
    {
        // Try both possible column name formats
        $checkInCol = 'check_in_date';
        $checkOutCol = 'check_out_date';
        
        // Check if reservations table uses different column names
        try {
            $stmt = $this->db->query("DESCRIBE reservations");
            $cols = $stmt->fetchAll(PDO::FETCH_COLUMN);
            if (in_array('check_in', $cols)) {
                $checkInCol = 'check_in';
                $checkOutCol = 'check_out';
            }
        } catch (Exception $e) {}
        
        $sql = "SELECT COUNT(*) as count FROM reservations 
                WHERE room_id = :room_id 
                AND status NOT IN ('cancelled', 'completed', 'Cancelled', 'Completed', 'Rejected')
                AND (
                    ({$checkInCol} <= :check_in1 AND {$checkOutCol} > :check_in2)
                    OR ({$checkInCol} < :check_out1 AND {$checkOutCol} >= :check_out2)
                    OR ({$checkInCol} >= :check_in3 AND {$checkOutCol} <= :check_out3)
                )";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            'room_id' => $roomId,
            'check_in1' => $checkIn,
            'check_in2' => $checkIn,
            'check_in3' => $checkIn,
            'check_out1' => $checkOut,
            'check_out2' => $checkOut,
            'check_out3' => $checkOut
        ]);
        
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['count'] == 0;
    }

    /**
     * Search available rooms for dates
     */
    public function searchAvailable($checkIn, $checkOut, $guests = 1)
    {
        $priceCol = $this->columns['price'];
        
        // Detect reservation column names
        $checkInCol = 'check_in_date';
        $checkOutCol = 'check_out_date';
        try {
            $stmt = $this->db->query("DESCRIBE reservations");
            $cols = $stmt->fetchAll(PDO::FETCH_COLUMN);
            if (in_array('check_in', $cols)) {
                $checkInCol = 'check_in';
                $checkOutCol = 'check_out';
            }
        } catch (Exception $e) {}
        
        $idCol = $this->columns['id'];
        
        $sql = "SELECT r.* FROM {$this->table} r 
                WHERE LOWER(r.status) = 'available' 
                AND r.capacity >= :guests
                AND r.{$idCol} NOT IN (
                    SELECT room_id FROM reservations 
                    WHERE status NOT IN ('cancelled', 'completed', 'Cancelled', 'Completed', 'Rejected')
                    AND (
                        ({$checkInCol} <= :check_in1 AND {$checkOutCol} > :check_in2)
                        OR ({$checkInCol} < :check_out1 AND {$checkOutCol} >= :check_out2)
                        OR ({$checkInCol} >= :check_in3 AND {$checkOutCol} <= :check_out3)
                    )
                )
                ORDER BY r.{$priceCol} ASC";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            'check_in1' => $checkIn,
            'check_in2' => $checkIn,
            'check_in3' => $checkIn,
            'check_out1' => $checkOut,
            'check_out2' => $checkOut,
            'check_out3' => $checkOut,
            'guests' => $guests
        ]);
        
        return $this->normalizeRooms($stmt->fetchAll(PDO::FETCH_ASSOC));
    }

    /**
     * Get room types
     */
    public function getTypes()
    {
        $typeCol = $this->columns['type'];
        $sql = "SELECT DISTINCT {$typeCol} FROM {$this->table} ORDER BY {$typeCol} ASC";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }

    /**
     * Update room status
     */
    public function updateStatus($id, $status)
    {
        $idCol = $this->columns['id'];
        $sql = "UPDATE {$this->table} SET status = :status WHERE {$idCol} = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute(['id' => $id, 'status' => $status]);
    }

    /**
     * Get room count
     */
    public function getCount()
    {
        $sql = "SELECT COUNT(*) as count FROM {$this->table}";
        $stmt = $this->db->query($sql);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['count'];
    }

    /**
     * Get available room count
     */
    public function getAvailableCount()
    {
        $statusVal = $this->columns['status_available'];
        $sql = "SELECT COUNT(*) as count FROM {$this->table} WHERE status = '{$statusVal}'";
        $stmt = $this->db->query($sql);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['count'];
    }
}
