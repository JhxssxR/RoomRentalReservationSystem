<?php
/**
 * Authentication Helper
 * Handles user authentication utilities
 */

class Auth
{
    /**
     * Check if user is logged in
     */
    public static function isLoggedIn()
    {
        return isset($_SESSION['user_id']);
    }

    /**
     * Check if user is admin
     */
    public static function isAdmin()
    {
        return isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin';
    }

    /**
     * Require login to access page
     */
    public static function requireLogin()
    {
        if (!self::isLoggedIn()) {
            $_SESSION['error'] = 'Please login to continue';
            header('Location: ' . BASE_URL . '/login');
            exit;
        }
    }

    /**
     * Require admin access
     */
    public static function requireAdmin()
    {
        self::requireLogin();
        
        if (!self::isAdmin()) {
            $_SESSION['error'] = 'Access denied. Admin privileges required.';
            header('Location: ' . BASE_URL . '/');
            exit;
        }
    }

    /**
     * Get current user ID
     */
    public static function getUserId()
    {
        return $_SESSION['user_id'] ?? null;
    }

    /**
     * Get current user name
     */
    public static function getUserName()
    {
        return $_SESSION['user_name'] ?? null;
    }

    /**
     * Get current user email
     */
    public static function getUserEmail()
    {
        return $_SESSION['user_email'] ?? null;
    }

    /**
     * Get current user role
     */
    public static function getUserRole()
    {
        return $_SESSION['user_role'] ?? 'guest';
    }

    /**
     * Set user session
     */
    public static function setUser($user)
    {
        // Handle various ID column names
        $_SESSION['user_id'] = $user['id'] ?? $user['customer_id'] ?? null;
        $_SESSION['user_name'] = $user['name'] ?? 'Guest';
        $_SESSION['user_email'] = $user['email'] ?? '';
        $_SESSION['user_role'] = $user['role'] ?? 'customer';
    }

    /**
     * Clear user session (logout)
     */
    public static function logout()
    {
        unset($_SESSION['user_id']);
        unset($_SESSION['user_name']);
        unset($_SESSION['user_email']);
        unset($_SESSION['user_role']);
        session_destroy();
    }

    /**
     * Generate CSRF token
     */
    public static function generateCsrfToken()
    {
        if (!isset($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }

    /**
     * Verify CSRF token
     */
    public static function verifyCsrfToken($token)
    {
        return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
    }

    /**
     * Get CSRF hidden input field
     */
    public static function csrfField()
    {
        return '<input type="hidden" name="csrf_token" value="' . self::generateCsrfToken() . '">';
    }
}
