<?php
/**
 * Customer Controller
 * Handles customer authentication and dashboard
 */

class CustomerController
{
    private $customerModel;

    public function __construct()
    {
        require_once __DIR__ . '/../models/Customer.php';
        $this->customerModel = new Customer();
    }

    /**
     * Display login page
     */
    public function login()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
            $password = $_POST['password'] ?? '';

            $customer = $this->customerModel->authenticate($email, $password);

            if ($customer) {
                $_SESSION['user_id'] = $customer['id'];
                $_SESSION['user_name'] = $customer['name'];
                $_SESSION['user_email'] = $customer['email'];
                $_SESSION['user_role'] = $customer['role'] ?? 'customer';

                // Redirect admin to admin dashboard
                if (($_SESSION['user_role'] ?? '') === 'admin') {
                    header('Location: ' . BASE_URL . '/admin/dashboard');
                } else {
                    header('Location: ' . BASE_URL . '/dashboard');
                }
                exit;
            } else {
                $error = 'Invalid email or password';
            }
        }

        include __DIR__ . '/../views/customer/login.php';
    }

    /**
     * Display registration page
     */
    public function register()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = trim(htmlspecialchars($_POST['name'] ?? ''));
            $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
            $phone = trim(htmlspecialchars($_POST['phone'] ?? ''));
            $address = trim(htmlspecialchars($_POST['address'] ?? ''));
            $password = $_POST['password'] ?? '';
            $confirmPassword = $_POST['confirm_password'] ?? '';
            $terms = isset($_POST['terms']);

            $errors = [];

            // Validation
            if (empty($name)) {
                $errors[] = 'Name is required';
            }
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errors[] = 'Valid email is required';
            }
            if (strlen($password) < 6) {
                $errors[] = 'Password must be at least 6 characters';
            }
            if ($password !== $confirmPassword) {
                $errors[] = 'Passwords do not match';
            }
            if (!$terms) {
                $errors[] = 'You must agree to the Terms of Service and Privacy Policy';
            }
            if ($this->customerModel->emailExists($email)) {
                $errors[] = 'Email already registered';
            }

            if (empty($errors)) {
                $data = [
                    'name' => $name,
                    'email' => $email,
                    'contact' => $phone,
                    'address' => $address,
                    'password_hash' => password_hash($password, PASSWORD_DEFAULT)
                ];

                if ($this->customerModel->create($data)) {
                    $_SESSION['success'] = 'Registration successful! Please login.';
                    header('Location: ' . BASE_URL . '/login');
                    exit;
                } else {
                    $errors[] = 'Registration failed. Please try again.';
                }
            }
        }

        include __DIR__ . '/../views/customer/register.php';
    }

    /**
     * Display forgot password page
     */
    public function forgotPassword()
    {
        $message = '';
        $messageType = '';
        $emailVerified = false;
        $verifiedEmail = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
            $action = $_POST['action'] ?? 'verify_email';

            if ($action === 'verify_email') {
                // Step 1: Verify email exists
                if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    $message = 'Please enter a valid email address';
                    $messageType = 'error';
                } elseif (!$this->customerModel->emailExists($email)) {
                    $message = 'No account found with this email address';
                    $messageType = 'error';
                } else {
                    // Email exists, show password reset form
                    $emailVerified = true;
                    $verifiedEmail = $email;
                    $_SESSION['reset_email'] = $email;
                }
            } elseif ($action === 'reset_password') {
                // Step 2: Reset password
                $email = $_POST['verified_email'] ?? $_SESSION['reset_email'] ?? '';
                $password = $_POST['password'] ?? '';
                $confirmPassword = $_POST['confirm_password'] ?? '';

                $errors = [];

                if (empty($email) || !$this->customerModel->emailExists($email)) {
                    $errors[] = 'Invalid session. Please start over.';
                }
                if (strlen($password) < 6) {
                    $errors[] = 'Password must be at least 6 characters';
                }
                if ($password !== $confirmPassword) {
                    $errors[] = 'Passwords do not match';
                }

                if (empty($errors)) {
                    $customer = $this->customerModel->getByEmail($email);
                    if ($customer) {
                        $passwordHash = password_hash($password, PASSWORD_DEFAULT);
                        if ($this->customerModel->updatePassword($customer['id'], $passwordHash)) {
                            unset($_SESSION['reset_email']);
                            $_SESSION['success'] = 'Password has been reset successfully. Please login with your new password.';
                            header('Location: ' . BASE_URL . '/login');
                            exit;
                        } else {
                            $message = 'Failed to reset password. Please try again.';
                            $messageType = 'error';
                        }
                    } else {
                        $message = 'Account not found. Please try again.';
                        $messageType = 'error';
                    }
                } else {
                    $message = implode('<br>', $errors);
                    $messageType = 'error';
                    $emailVerified = true;
                    $verifiedEmail = $email;
                }
            }
        }

        include __DIR__ . '/../views/customer/forgot-password.php';
    }

    /**
     * Display reset password page
     */
    public function resetPassword()
    {
        $token = $_GET['token'] ?? $_SESSION['reset_token'] ?? '';
        $errors = [];
        $success = false;

        if (empty($token)) {
            $_SESSION['error'] = 'Invalid or expired reset link';
            header('Location: ' . BASE_URL . '/forgot-password');
            exit;
        }

        // Verify token
        $customer = $this->customerModel->getByResetToken($token);
        
        if (!$customer) {
            $_SESSION['error'] = 'Invalid or expired reset link';
            header('Location: ' . BASE_URL . '/forgot-password');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $password = $_POST['password'] ?? '';
            $confirmPassword = $_POST['confirm_password'] ?? '';

            if (strlen($password) < 6) {
                $errors[] = 'Password must be at least 6 characters';
            }
            if ($password !== $confirmPassword) {
                $errors[] = 'Passwords do not match';
            }

            if (empty($errors)) {
                $passwordHash = password_hash($password, PASSWORD_DEFAULT);
                
                if ($this->customerModel->updatePassword($customer['id'], $passwordHash)) {
                    // Clear the reset token
                    $this->customerModel->clearResetToken($customer['id']);
                    unset($_SESSION['reset_token']);
                    unset($_SESSION['reset_email']);
                    
                    $_SESSION['success'] = 'Password has been reset successfully. Please login with your new password.';
                    header('Location: ' . BASE_URL . '/login');
                    exit;
                } else {
                    $errors[] = 'Failed to reset password. Please try again.';
                }
            }
        }

        include __DIR__ . '/../views/customer/reset-password.php';
    }

    /**
     * Display customer dashboard
     */
    public function dashboard()
    {
        Auth::requireLogin();

        $customerId = $_SESSION['user_id'];
        $customer = $this->customerModel->getById($customerId);

        require_once __DIR__ . '/../models/Reservation.php';
        $reservationModel = new Reservation();
        $reservations = $reservationModel->getByCustomerId($customerId);

        // Get notifications for customer
        require_once __DIR__ . '/../models/Notification.php';
        $notificationModel = new Notification();
        $notifications = $notificationModel->getCustomerNotifications($customerId, 10);
        $unreadCount = $notificationModel->getCustomerUnreadCount($customerId);

        include __DIR__ . '/../views/customer/dashboard.php';
    }

    /**
     * Logout user
     */
    public function logout()
    {
        session_destroy();
        header('Location: ' . BASE_URL . '/');
        exit;
    }

    /**
     * Update customer profile
     */
    public function updateProfile()
    {
        Auth::requireLogin();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $customerId = $_SESSION['user_id'];
            $name = trim(htmlspecialchars($_POST['name'] ?? ''));
            $phone = trim(htmlspecialchars($_POST['phone'] ?? ''));
            $address = trim(htmlspecialchars($_POST['address'] ?? ''));

            $data = [
                'name' => $name,
                'contact' => $phone,
                'address' => $address
            ];

            if ($this->customerModel->update($customerId, $data)) {
                $_SESSION['user_name'] = $name;
                $_SESSION['success'] = 'Profile updated successfully';
            } else {
                $_SESSION['error'] = 'Failed to update profile';
            }
        }

        header('Location: ' . BASE_URL . '/dashboard');
        exit;
    }
}
