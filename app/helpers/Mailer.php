<?php
/**
 * Mailer Helper
 * Handles email notifications
 */

class Mailer
{
    private $fromEmail;
    private $fromName;

    public function __construct()
    {
        $this->fromEmail = defined('MAIL_FROM') ? MAIL_FROM : 'noreply@roomrental.com';
        $this->fromName = defined('MAIL_FROM_NAME') ? MAIL_FROM_NAME : 'Room Rental';
    }

    /**
     * Send email
     */
    public function send($to, $subject, $body, $isHtml = true)
    {
        $headers = [];
        $headers[] = 'MIME-Version: 1.0';
        $headers[] = $isHtml ? 'Content-type: text/html; charset=UTF-8' : 'Content-type: text/plain; charset=UTF-8';
        $headers[] = "From: {$this->fromName} <{$this->fromEmail}>";
        $headers[] = "Reply-To: {$this->fromEmail}";
        $headers[] = 'X-Mailer: PHP/' . phpversion();

        return mail($to, $subject, $body, implode("\r\n", $headers));
    }

    /**
     * Send reservation confirmation email
     */
    public function sendReservationConfirmation($customer, $reservation, $room)
    {
        $subject = 'Reservation Confirmation - Room Rental';
        
        $body = $this->getEmailTemplate('reservation_confirmation', [
            'customer_name' => $customer['name'],
            'room_name' => $room['name'],
            'room_number' => $room['room_number'],
            'check_in' => date('F j, Y', strtotime($reservation['check_in_date'])),
            'check_out' => date('F j, Y', strtotime($reservation['check_out_date'])),
            'total_price' => number_format($reservation['total_price'], 2),
            'reservation_id' => $reservation['id']
        ]);

        return $this->send($customer['email'], $subject, $body);
    }

    /**
     * Send payment confirmation email
     */
    public function sendPaymentConfirmation($customer, $payment, $reservation)
    {
        $subject = 'Payment Confirmed - Room Rental';
        
        $body = $this->getEmailTemplate('payment_confirmation', [
            'customer_name' => $customer['name'],
            'amount' => number_format($payment['amount'], 2),
            'transaction_id' => $payment['transaction_id'],
            'payment_method' => ucfirst($payment['payment_method']),
            'reservation_id' => $reservation['id']
        ]);

        return $this->send($customer['email'], $subject, $body);
    }

    /**
     * Send welcome email to new customers
     */
    public function sendWelcomeEmail($customer)
    {
        $subject = 'Welcome to Room Rental';
        
        $body = $this->getEmailTemplate('welcome', [
            'customer_name' => $customer['name']
        ]);

        return $this->send($customer['email'], $subject, $body);
    }

    /**
     * Get email template
     */
    private function getEmailTemplate($template, $data = [])
    {
        $templates = [
            'reservation_confirmation' => "
                <html>
                <body style='font-family: Arial, sans-serif; line-height: 1.6; color: #333;'>
                    <div style='max-width: 600px; margin: 0 auto; padding: 20px;'>
                        <h2 style='color: #4a7c59;'>Reservation Confirmed!</h2>
                        <p>Dear {customer_name},</p>
                        <p>Your reservation has been confirmed. Here are the details:</p>
                        <div style='background: #f5f5f5; padding: 20px; border-radius: 8px; margin: 20px 0;'>
                            <p><strong>Reservation ID:</strong> #{reservation_id}</p>
                            <p><strong>Room:</strong> {room_name} (Room {room_number})</p>
                            <p><strong>Check-in:</strong> {check_in}</p>
                            <p><strong>Check-out:</strong> {check_out}</p>
                            <p><strong>Total Amount:</strong> ₱{total_price}</p>
                        </div>
                        <p>Thank you for choosing Room Rental!</p>
                        <p>Best regards,<br>Room Rental Team</p>
                    </div>
                </body>
                </html>
            ",
            'payment_confirmation' => "
                <html>
                <body style='font-family: Arial, sans-serif; line-height: 1.6; color: #333;'>
                    <div style='max-width: 600px; margin: 0 auto; padding: 20px;'>
                        <h2 style='color: #4a7c59;'>Payment Received!</h2>
                        <p>Dear {customer_name},</p>
                        <p>We have received your payment. Here are the details:</p>
                        <div style='background: #f5f5f5; padding: 20px; border-radius: 8px; margin: 20px 0;'>
                            <p><strong>Transaction ID:</strong> {transaction_id}</p>
                            <p><strong>Amount:</strong> ₱{amount}</p>
                            <p><strong>Payment Method:</strong> {payment_method}</p>
                            <p><strong>Reservation ID:</strong> #{reservation_id}</p>
                        </div>
                        <p>Thank you for your payment!</p>
                        <p>Best regards,<br>Room Rental Team</p>
                    </div>
                </body>
                </html>
            ",
            'welcome' => "
                <html>
                <body style='font-family: Arial, sans-serif; line-height: 1.6; color: #333;'>
                    <div style='max-width: 600px; margin: 0 auto; padding: 20px;'>
                        <h2 style='color: #4a7c59;'>Welcome to Room Rental!</h2>
                        <p>Dear {customer_name},</p>
                        <p>Thank you for joining Room Rental. We're excited to have you!</p>
                        <p>You can now:</p>
                        <ul>
                            <li>Browse our available rooms</li>
                            <li>Make reservations online</li>
                            <li>Manage your bookings</li>
                            <li>View payment history</li>
                        </ul>
                        <p>If you have any questions, feel free to contact us.</p>
                        <p>Best regards,<br>Room Rental Team</p>
                    </div>
                </body>
                </html>
            "
        ];

        $html = $templates[$template] ?? '';
        
        foreach ($data as $key => $value) {
            $html = str_replace('{' . $key . '}', htmlspecialchars($value), $html);
        }

        return $html;
    }
}
