<?php
/**
 * Notification Model
 * Handles notifications for admin and customers
 * Uses fallback notifications from system data - no table required
 */

class Notification
{
    private $db;
    private $table = 'notifications';
    private $tableExists = false;
    private $columns = [];

    public function __construct()
    {
        global $pdo;
        $this->db = $pdo;
        $this->checkTable();
    }

    /**
     * Check if notifications table exists and has required columns
     */
    private function checkTable()
    {
        try {
            $stmt = $this->db->query("SHOW TABLES LIKE '{$this->table}'");
            if ($stmt->rowCount() > 0) {
                // Table exists, check columns
                $colStmt = $this->db->query("DESCRIBE {$this->table}");
                $this->columns = $colStmt->fetchAll(PDO::FETCH_COLUMN);
                $this->tableExists = true;
            } else {
                $this->tableExists = false;
            }
        } catch (Exception $e) {
            $this->tableExists = false;
        }
    }

    /**
     * Check if a column exists in the table
     */
    private function hasColumn($column)
    {
        return in_array($column, $this->columns);
    }

    /**
     * Get notifications for admin
     */
    public function getAdminNotifications($limit = 10)
    {
        // Always use system-generated notifications for reliability
        return $this->getDefaultAdminNotifications();
    }

    /**
     * Get notifications for a customer
     */
    public function getCustomerNotifications($customerId, $limit = 10)
    {
        // Always use system-generated notifications for reliability
        return $this->getDefaultCustomerNotifications($customerId);
    }

    /**
     * Get unread count for admin
     */
    public function getAdminUnreadCount()
    {
        return $this->getDefaultAdminUnreadCount();
    }

    /**
     * Get unread count for customer
     */
    public function getCustomerUnreadCount($customerId)
    {
        return $this->getDefaultCustomerUnreadCount($customerId);
    }

    /**
     * Mark notification as read (placeholder for future use)
     */
    public function markAsRead($id)
    {
        return true;
    }

    /**
     * Mark all notifications as read for user (placeholder for future use)
     */
    public function markAllAsRead($userId, $userType = 'customer')
    {
        return true;
    }

    /**
     * Get default admin notifications from system data
     */
    private function getDefaultAdminNotifications()
    {
        $notifications = [];
        
        // Detect check_in column name
        $checkInCol = 'check_in';
        try {
            $stmt = $this->db->query("DESCRIBE reservations");
            $cols = $stmt->fetchAll(PDO::FETCH_COLUMN);
            if (in_array('check_in_date', $cols)) {
                $checkInCol = 'check_in_date';
            }
        } catch (Exception $e) {}
        
        // Get pending payments
        try {
            $sql = "SELECT COUNT(*) as count FROM payments WHERE LOWER(status) IN ('pending')";
            $stmt = $this->db->query($sql);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($result && $result['count'] > 0) {
                $notifications[] = [
                    'id' => 'pending_payments',
                    'type' => 'warning',
                    'title' => 'Pending Payments',
                    'message' => $result['count'] . ' payment(s) awaiting approval',
                    'link' => BASE_URL . '/admin/payments',
                    'is_read' => 0,
                    'created_at' => date('Y-m-d H:i:s')
                ];
            }
        } catch (Exception $e) {}

        // Get today's check-ins
        try {
            $sql = "SELECT COUNT(*) as count FROM reservations WHERE DATE({$checkInCol}) = CURDATE() AND LOWER(status) = 'confirmed'";
            $stmt = $this->db->query($sql);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($result && $result['count'] > 0) {
                $notifications[] = [
                    'id' => 'todays_checkins',
                    'type' => 'info',
                    'title' => "Today's Check-ins",
                    'message' => $result['count'] . ' guest(s) arriving today',
                    'link' => BASE_URL . '/admin/dashboard',
                    'is_read' => 0,
                    'created_at' => date('Y-m-d H:i:s')
                ];
            }
        } catch (Exception $e) {}

        // Get new reservations (last 24 hours)
        try {
            $sql = "SELECT COUNT(*) as count FROM reservations WHERE created_at >= DATE_SUB(NOW(), INTERVAL 24 HOUR)";
            $stmt = $this->db->query($sql);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($result && $result['count'] > 0) {
                $notifications[] = [
                    'id' => 'new_reservations',
                    'type' => 'success',
                    'title' => 'New Reservations',
                    'message' => $result['count'] . ' new booking(s) in last 24 hours',
                    'link' => BASE_URL . '/admin/dashboard',
                    'is_read' => 0,
                    'created_at' => date('Y-m-d H:i:s')
                ];
            }
        } catch (Exception $e) {}

        return $notifications;
    }

    /**
     * Get default unread count for admin
     */
    private function getDefaultAdminUnreadCount()
    {
        $count = 0;
        
        // Detect check_in column name
        $checkInCol = 'check_in';
        try {
            $stmt = $this->db->query("DESCRIBE reservations");
            $cols = $stmt->fetchAll(PDO::FETCH_COLUMN);
            if (in_array('check_in_date', $cols)) {
                $checkInCol = 'check_in_date';
            }
        } catch (Exception $e) {}
        
        try {
            $sql = "SELECT COUNT(*) as count FROM payments WHERE LOWER(status) IN ('pending')";
            $stmt = $this->db->query($sql);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($result && $result['count'] > 0) $count++;
        } catch (Exception $e) {}

        try {
            $sql = "SELECT COUNT(*) as count FROM reservations WHERE DATE({$checkInCol}) = CURDATE() AND LOWER(status) = 'confirmed'";
            $stmt = $this->db->query($sql);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($result && $result['count'] > 0) $count++;
        } catch (Exception $e) {}

        try {
            $sql = "SELECT COUNT(*) as count FROM reservations WHERE created_at >= DATE_SUB(NOW(), INTERVAL 24 HOUR)";
            $stmt = $this->db->query($sql);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($result && $result['count'] > 0) $count++;
        } catch (Exception $e) {}

        return $count;
    }

    /**
     * Get default customer notifications
     */
    private function getDefaultCustomerNotifications($customerId)
    {
        $notifications = [];
        
        // Detect check_in column name
        $checkInCol = 'check_in';
        try {
            $stmt = $this->db->query("DESCRIBE reservations");
            $cols = $stmt->fetchAll(PDO::FETCH_COLUMN);
            if (in_array('check_in_date', $cols)) {
                $checkInCol = 'check_in_date';
            }
        } catch (Exception $e) {}
        
        // Get customer's pending reservations
        try {
            $sql = "SELECT COUNT(*) as count FROM reservations WHERE customer_id = :customer_id AND LOWER(status) = 'pending'";
            $stmt = $this->db->prepare($sql);
            $stmt->execute(['customer_id' => $customerId]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($result && $result['count'] > 0) {
                $notifications[] = [
                    'id' => 'pending_reservations',
                    'type' => 'warning',
                    'title' => 'Pending Reservations',
                    'message' => 'You have ' . $result['count'] . ' reservation(s) awaiting payment',
                    'link' => BASE_URL . '/reservations',
                    'is_read' => 0,
                    'created_at' => date('Y-m-d H:i:s')
                ];
            }
        } catch (Exception $e) {}

        // Get upcoming check-ins (next 3 days)
        try {
            $sql = "SELECT COUNT(*) as count FROM reservations 
                    WHERE customer_id = :customer_id 
                    AND LOWER(status) = 'confirmed' 
                    AND {$checkInCol} BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 3 DAY)";
            $stmt = $this->db->prepare($sql);
            $stmt->execute(['customer_id' => $customerId]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($result && $result['count'] > 0) {
                $notifications[] = [
                    'id' => 'upcoming_checkins',
                    'type' => 'info',
                    'title' => 'Upcoming Stay',
                    'message' => 'You have ' . $result['count'] . ' check-in(s) in the next 3 days',
                    'link' => BASE_URL . '/reservations',
                    'is_read' => 0,
                    'created_at' => date('Y-m-d H:i:s')
                ];
            }
        } catch (Exception $e) {}

        return $notifications;
    }

    /**
     * Get default unread count for customer
     */
    private function getDefaultCustomerUnreadCount($customerId)
    {
        $count = 0;
        
        // Detect check_in column name
        $checkInCol = 'check_in';
        try {
            $stmt = $this->db->query("DESCRIBE reservations");
            $cols = $stmt->fetchAll(PDO::FETCH_COLUMN);
            if (in_array('check_in_date', $cols)) {
                $checkInCol = 'check_in_date';
            }
        } catch (Exception $e) {}
        
        try {
            $sql = "SELECT COUNT(*) as count FROM reservations WHERE customer_id = :customer_id AND LOWER(status) = 'pending'";
            $stmt = $this->db->prepare($sql);
            $stmt->execute(['customer_id' => $customerId]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($result && $result['count'] > 0) $count++;
        } catch (Exception $e) {}

        try {
            $sql = "SELECT COUNT(*) as count FROM reservations 
                    WHERE customer_id = :customer_id 
                    AND LOWER(status) = 'confirmed' 
                    AND {$checkInCol} BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 3 DAY)";
            $stmt = $this->db->prepare($sql);
            $stmt->execute(['customer_id' => $customerId]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($result && $result['count'] > 0) $count++;
        } catch (Exception $e) {}

        return $count;
    }

    /**
     * Create notification (placeholder - not using database table)
     */
    public function create($data)
    {
        // Placeholder - notifications are generated from system data
        return true;
    }

    /**
     * Create notification for new reservation (for admin)
     */
    public function notifyNewReservation($reservationData)
    {
        // Placeholder - notifications are generated from system data
        return true;
    }

    /**
     * Create notification for payment status (for customer)
     */
    public function notifyPaymentStatus($customerId, $status, $reservationId)
    {
        // Placeholder - notifications are generated from system data
        return true;
    }
}
