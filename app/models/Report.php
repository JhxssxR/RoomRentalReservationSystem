<?php
/**
 * Report Model
 * Database operations for generating reports
 * Auto-detects column names for compatibility
 */

class Report
{
    private $db;
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
            // Detect reservation columns
            $stmt = $this->db->query("DESCRIBE reservations");
            $cols = $stmt->fetchAll(PDO::FETCH_COLUMN);
            
            $this->columns['check_in'] = in_array('check_in_date', $cols) ? 'check_in_date' : 'check_in';
            $this->columns['check_out'] = in_array('check_out_date', $cols) ? 'check_out_date' : 'check_out';
            $this->columns['reservation_id'] = in_array('reservation_id', $cols) ? 'reservation_id' : 'id';
            
            // Detect room columns
            $stmt = $this->db->query("DESCRIBE rooms");
            $cols = $stmt->fetchAll(PDO::FETCH_COLUMN);
            $this->columns['room_id'] = in_array('room_id', $cols) ? 'room_id' : 'id';
            $this->columns['room_type'] = in_array('room_type', $cols) ? 'room_type' : 'type';
            
            // Detect payment columns
            $stmt = $this->db->query("DESCRIBE payments");
            $cols = $stmt->fetchAll(PDO::FETCH_COLUMN);
            $this->columns['payment_date'] = in_array('payment_date', $cols) ? 'payment_date' : 'created_at';
            
        } catch (Exception $e) {
            // Fallback defaults
            $this->columns = [
                'check_in' => 'check_in',
                'check_out' => 'check_out',
                'reservation_id' => 'reservation_id',
                'room_id' => 'room_id',
                'room_type' => 'room_type',
                'payment_date' => 'payment_date'
            ];
        }
    }

    /**
     * Get total rooms
     */
    public function getTotalRooms()
    {
        $sql = "SELECT COUNT(*) as count FROM rooms";
        $stmt = $this->db->query($sql);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['count'];
    }

    /**
     * Get total customers
     */
    public function getTotalCustomers()
    {
        // Check if role column exists
        try {
            $stmt = $this->db->query("DESCRIBE customers");
            $cols = $stmt->fetchAll(PDO::FETCH_COLUMN);
            
            if (in_array('role', $cols)) {
                $sql = "SELECT COUNT(*) as count FROM customers WHERE role = 'customer' OR role IS NULL";
            } else {
                $sql = "SELECT COUNT(*) as count FROM customers";
            }
        } catch (Exception $e) {
            $sql = "SELECT COUNT(*) as count FROM customers";
        }
        
        $stmt = $this->db->query($sql);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['count'];
    }

    /**
     * Get total reservations
     */
    public function getTotalReservations()
    {
        $sql = "SELECT COUNT(*) as count FROM reservations";
        $stmt = $this->db->query($sql);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['count'];
    }

    /**
     * Get total revenue
     */
    public function getTotalRevenue()
    {
        $sql = "SELECT SUM(amount) as total FROM payments WHERE status IN ('approved', 'Approved', 'confirmed', 'Confirmed')";
        $stmt = $this->db->query($sql);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total'] ?? 0;
    }

    /**
     * Get pending payments count
     */
    public function getPendingPayments()
    {
        $sql = "SELECT COUNT(*) as count FROM payments WHERE status IN ('pending', 'Pending') OR status IS NULL OR status = ''";
        $stmt = $this->db->query($sql);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['count'];
    }

    /**
     * Get today's check-ins count
     */
    public function getTodaysCheckins()
    {
        $checkInCol = $this->columns['check_in'];
        $sql = "SELECT COUNT(*) as count FROM reservations WHERE DATE({$checkInCol}) = CURDATE() AND status = 'confirmed'";
        $stmt = $this->db->query($sql);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['count'];
    }

    /**
     * Get today's check-outs count
     */
    public function getTodaysCheckouts()
    {
        $checkOutCol = $this->columns['check_out'];
        $sql = "SELECT COUNT(*) as count FROM reservations WHERE DATE({$checkOutCol}) = CURDATE() AND status = 'confirmed'";
        $stmt = $this->db->query($sql);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['count'];
    }

    /**
     * Get occupancy rate
     */
    public function getOccupancyRate()
    {
        $totalRooms = $this->getTotalRooms();
        if ($totalRooms == 0) return 0;

        $checkInCol = $this->columns['check_in'];
        $checkOutCol = $this->columns['check_out'];
        $sql = "SELECT COUNT(DISTINCT room_id) as occupied FROM reservations 
                WHERE status = 'confirmed' 
                AND CURDATE() BETWEEN {$checkInCol} AND {$checkOutCol}";
        $stmt = $this->db->query($sql);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        return round(($result['occupied'] / $totalRooms) * 100, 1);
    }

    /**
     * Get revenue report for date range
     */
    public function getRevenueReport($startDate, $endDate)
    {
        $paymentDateCol = $this->columns['payment_date'];
        $sql = "SELECT DATE(p.{$paymentDateCol}) as date, SUM(p.amount) as revenue, COUNT(*) as transactions
                FROM payments p
                WHERE (p.status IN ('approved', 'Approved', 'Confirmed', 'confirmed'))
                AND DATE(p.{$paymentDateCol}) BETWEEN :start_date AND :end_date
                GROUP BY DATE(p.{$paymentDateCol})
                ORDER BY date ASC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['start_date' => $startDate, 'end_date' => $endDate]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Get occupancy report for date range
     */
    public function getOccupancyReport($startDate, $endDate)
    {
        $totalRooms = $this->getTotalRooms();
        $checkInCol = $this->columns['check_in'];
        
        $sql = "SELECT DATE(r.{$checkInCol}) as date, COUNT(DISTINCT r.room_id) as occupied_rooms
                FROM reservations r
                WHERE r.status IN ('confirmed', 'completed')
                AND DATE(r.{$checkInCol}) BETWEEN :start_date AND :end_date
                GROUP BY DATE(r.{$checkInCol})
                ORDER BY date ASC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['start_date' => $startDate, 'end_date' => $endDate]);
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Calculate occupancy percentage
        foreach ($results as &$row) {
            $row['occupancy_rate'] = $totalRooms > 0 ? round(($row['occupied_rooms'] / $totalRooms) * 100, 1) : 0;
            $row['total_rooms'] = $totalRooms;
        }

        return $results;
    }

    /**
     * Get customer report for date range
     */
    public function getCustomerReport($startDate, $endDate)
    {
        $sql = "SELECT DATE(created_at) as date, COUNT(*) as new_customers
                FROM customers
                WHERE role = 'customer'
                AND DATE(created_at) BETWEEN :start_date AND :end_date
                GROUP BY DATE(created_at)
                ORDER BY date ASC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['start_date' => $startDate, 'end_date' => $endDate]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Get reservation report for date range
     */
    public function getReservationReport($startDate, $endDate)
    {
        $sql = "SELECT DATE(r.created_at) as date, 
                       COUNT(*) as total_reservations,
                       SUM(CASE WHEN r.status = 'confirmed' THEN 1 ELSE 0 END) as confirmed,
                       SUM(CASE WHEN r.status = 'pending' THEN 1 ELSE 0 END) as pending,
                       SUM(CASE WHEN r.status = 'cancelled' THEN 1 ELSE 0 END) as cancelled,
                       SUM(r.total_price) as total_value
                FROM reservations r
                WHERE DATE(r.created_at) BETWEEN :start_date AND :end_date
                GROUP BY DATE(r.created_at)
                ORDER BY date ASC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['start_date' => $startDate, 'end_date' => $endDate]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Get top rooms by revenue
     */
    public function getTopRoomsByRevenue($limit = 5)
    {
        $roomIdCol = $this->columns['room_id'];
        $reservationIdCol = $this->columns['reservation_id'];
        $sql = "SELECT rm.{$roomIdCol} as id, rm.room_number, rm.name, SUM(p.amount) as revenue, COUNT(r.{$reservationIdCol}) as bookings
                FROM rooms rm
                LEFT JOIN reservations r ON rm.{$roomIdCol} = r.room_id
                LEFT JOIN payments p ON r.{$reservationIdCol} = p.reservation_id AND (p.status = 'approved' OR p.status = 'Confirmed')
                GROUP BY rm.{$roomIdCol}, rm.room_number, rm.name
                ORDER BY revenue DESC
                LIMIT :limit";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Get monthly revenue summary
     */
    public function getMonthlyRevenue($year)
    {
        $paymentDateCol = $this->columns['payment_date'];
        $sql = "SELECT MONTH({$paymentDateCol}) as month, SUM(amount) as revenue
                FROM payments
                WHERE (status = 'approved' OR status = 'Confirmed') AND YEAR({$paymentDateCol}) = :year
                GROUP BY MONTH({$paymentDateCol})
                ORDER BY month ASC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['year' => $year]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Get room type distribution
     */
    public function getRoomTypeDistribution()
    {
        $roomTypeCol = $this->columns['room_type'];
        $sql = "SELECT {$roomTypeCol} as type, COUNT(*) as count FROM rooms GROUP BY {$roomTypeCol} ORDER BY count DESC";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Get reservation status distribution
     */
    public function getReservationStatusDistribution()
    {
        $sql = "SELECT status, COUNT(*) as count FROM reservations GROUP BY status";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
