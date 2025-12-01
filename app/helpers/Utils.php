<?php
/**
 * Utility Helper Functions
 */

class Utils
{
    /**
     * Sanitize input string
     */
    public static function sanitize($input)
    {
        if (is_array($input)) {
            return array_map([self::class, 'sanitize'], $input);
        }
        return htmlspecialchars(strip_tags(trim($input)), ENT_QUOTES, 'UTF-8');
    }

    /**
     * Format currency
     */
    public static function formatCurrency($amount, $currency = 'PHP')
    {
        $symbols = [
            'PHP' => '₱',
            'USD' => '$',
            'EUR' => '€'
        ];
        $symbol = $symbols[$currency] ?? $currency . ' ';
        return $symbol . number_format($amount, 2);
    }

    /**
     * Format date
     */
    public static function formatDate($date, $format = 'M d, Y')
    {
        return date($format, strtotime($date));
    }

    /**
     * Format datetime
     */
    public static function formatDateTime($datetime, $format = 'M d, Y h:i A')
    {
        return date($format, strtotime($datetime));
    }

    /**
     * Calculate number of nights between two dates
     */
    public static function calculateNights($checkIn, $checkOut)
    {
        $start = new DateTime($checkIn);
        $end = new DateTime($checkOut);
        return $start->diff($end)->days;
    }

    /**
     * Generate random string
     */
    public static function generateRandomString($length = 10)
    {
        return bin2hex(random_bytes($length / 2));
    }

    /**
     * Generate unique transaction ID
     */
    public static function generateTransactionId()
    {
        return 'TXN' . date('YmdHis') . strtoupper(self::generateRandomString(6));
    }

    /**
     * Redirect to URL
     */
    public static function redirect($url)
    {
        header('Location: ' . $url);
        exit;
    }

    /**
     * Get base URL
     */
    public static function baseUrl($path = '')
    {
        return BASE_URL . $path;
    }

    /**
     * Set flash message
     */
    public static function setFlash($type, $message)
    {
        $_SESSION[$type] = $message;
    }

    /**
     * Get flash message
     */
    public static function getFlash($type)
    {
        $message = $_SESSION[$type] ?? null;
        unset($_SESSION[$type]);
        return $message;
    }

    /**
     * Check if string is valid email
     */
    public static function isValidEmail($email)
    {
        return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
    }

    /**
     * Check if string is valid phone number
     */
    public static function isValidPhone($phone)
    {
        return preg_match('/^[0-9+\-\s()]{10,20}$/', $phone);
    }

    /**
     * Upload file
     */
    public static function uploadFile($file, $uploadDir, $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'], $maxSize = 5242880)
    {
        if (!isset($file['error']) || $file['error'] !== UPLOAD_ERR_OK) {
            return ['success' => false, 'error' => 'Upload failed'];
        }

        if (!in_array($file['type'], $allowedTypes)) {
            return ['success' => false, 'error' => 'Invalid file type'];
        }

        if ($file['size'] > $maxSize) {
            return ['success' => false, 'error' => 'File too large'];
        }

        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $filename = self::generateRandomString(16) . '.' . $extension;
        $targetPath = $uploadDir . '/' . $filename;

        if (move_uploaded_file($file['tmp_name'], $targetPath)) {
            return ['success' => true, 'filename' => $filename, 'path' => $targetPath];
        }

        return ['success' => false, 'error' => 'Failed to move uploaded file'];
    }

    /**
     * Truncate text
     */
    public static function truncate($text, $length = 100, $suffix = '...')
    {
        if (strlen($text) <= $length) {
            return $text;
        }
        return substr($text, 0, $length) . $suffix;
    }

    /**
     * Convert to slug
     */
    public static function slugify($text)
    {
        $text = preg_replace('~[^\pL\d]+~u', '-', $text);
        $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
        $text = preg_replace('~[^-\w]+~', '', $text);
        $text = trim($text, '-');
        $text = preg_replace('~-+~', '-', $text);
        return strtolower($text);
    }

    /**
     * Get time ago
     */
    public static function timeAgo($datetime)
    {
        $time = strtotime($datetime);
        $diff = time() - $time;

        if ($diff < 60) {
            return 'Just now';
        } elseif ($diff < 3600) {
            $mins = floor($diff / 60);
            return $mins . ' minute' . ($mins > 1 ? 's' : '') . ' ago';
        } elseif ($diff < 86400) {
            $hours = floor($diff / 3600);
            return $hours . ' hour' . ($hours > 1 ? 's' : '') . ' ago';
        } elseif ($diff < 604800) {
            $days = floor($diff / 86400);
            return $days . ' day' . ($days > 1 ? 's' : '') . ' ago';
        } else {
            return date('M d, Y', $time);
        }
    }

    /**
     * Get pagination data
     */
    public static function paginate($totalItems, $currentPage = 1, $perPage = 10)
    {
        $totalPages = ceil($totalItems / $perPage);
        $currentPage = max(1, min($currentPage, $totalPages));
        $offset = ($currentPage - 1) * $perPage;

        return [
            'total_items' => $totalItems,
            'per_page' => $perPage,
            'current_page' => $currentPage,
            'total_pages' => $totalPages,
            'offset' => $offset,
            'has_previous' => $currentPage > 1,
            'has_next' => $currentPage < $totalPages
        ];
    }
}
