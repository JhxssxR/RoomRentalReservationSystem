<?php
$pageTitle = 'Payment Receipt - Room Rental';
include __DIR__ . '/../layouts/header.php';

// Load Room model to get room details
require_once __DIR__ . '/../../models/Room.php';
$roomModel = new Room();
$room = $roomModel->getById($reservation['room_id']);
?>

<div class="min-h-screen bg-gradient-to-br from-pale-50 to-sage-50 py-12">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Back Button -->
        <a href="<?php echo BASE_URL; ?>/payments" class="inline-flex items-center text-sage-600 hover:text-sage-700 mb-6">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Back to Payments
        </a>

        <!-- Receipt Card -->
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden" id="receipt">
            <!-- Header -->
            <div class="bg-gradient-to-r from-sage-500 to-pale-500 px-8 py-6 text-white">
                <div class="flex justify-between items-start">
                    <div>
                        <h1 class="text-2xl font-bold">Payment Receipt</h1>
                        <p class="text-white/80 mt-1">Transaction #<?php echo htmlspecialchars($payment['transaction_id'] ?? 'TXN' . $payment['payment_id']); ?></p>
                    </div>
                    <div class="text-right">
                        <?php 
                        $status = strtolower($payment['status']);
                        $statusClass = match($status) {
                            'confirmed', 'approved', 'completed' => 'bg-green-400',
                            'pending' => 'bg-yellow-400',
                            'rejected', 'failed' => 'bg-red-400',
                            default => 'bg-gray-400'
                        };
                        ?>
                        <span class="inline-block px-4 py-1 <?php echo $statusClass; ?> text-white text-sm font-semibold rounded-full">
                            <?php echo ucfirst($payment['status']); ?>
                        </span>
                    </div>
                </div>
            </div>

            <!-- Content -->
            <div class="p-8">
                <!-- Amount Section -->
                <div class="text-center mb-8 pb-8 border-b border-gray-200">
                    <p class="text-gray-500 text-sm uppercase tracking-wide mb-2">Amount Paid</p>
                    <p class="text-5xl font-bold bg-gradient-to-r from-sage-600 to-pale-600 bg-clip-text text-transparent">
                        ₱<?php echo number_format($payment['amount'], 2); ?>
                    </p>
                </div>

                <!-- Details Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                    <div>
                        <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wide mb-3">Payment Details</h3>
                        <div class="space-y-3">
                            <div class="flex justify-between">
                                <span class="text-gray-600">Payment ID</span>
                                <span class="font-semibold text-gray-800">#<?php echo $payment['payment_id']; ?></span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Payment Method</span>
                                <span class="font-semibold text-gray-800"><?php echo ucfirst($payment['payment_method'] ?? 'N/A'); ?></span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Payment Date</span>
                                <span class="font-semibold text-gray-800"><?php echo date('M j, Y g:i A', strtotime($payment['created_at'] ?? $payment['payment_date'] ?? 'now')); ?></span>
                            </div>
                        </div>
                    </div>

                    <div>
                        <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wide mb-3">Reservation Details</h3>
                        <div class="space-y-3">
                            <div class="flex justify-between">
                                <span class="text-gray-600">Reservation ID</span>
                                <span class="font-semibold text-gray-800">#<?php echo $reservation['reservation_id']; ?></span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Room</span>
                                <span class="font-semibold text-gray-800"><?php echo htmlspecialchars($room['name'] ?? 'Room ' . $room['room_number']); ?></span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Room Type</span>
                                <span class="font-semibold text-gray-800"><?php echo htmlspecialchars($room['room_type']); ?></span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Stay Details -->
                <div class="bg-gradient-to-r from-sage-50 to-pale-50 rounded-xl p-6 mb-8">
                    <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wide mb-4">Stay Information</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="text-center">
                            <p class="text-xs text-gray-500 uppercase mb-1">Check-in</p>
                            <p class="text-lg font-bold text-gray-800"><?php echo date('M j, Y', strtotime($reservation['check_in'] ?? $reservation['check_in_date'])); ?></p>
                        </div>
                        <div class="text-center">
                            <p class="text-xs text-gray-500 uppercase mb-1">Check-out</p>
                            <p class="text-lg font-bold text-gray-800"><?php echo date('M j, Y', strtotime($reservation['check_out'] ?? $reservation['check_out_date'])); ?></p>
                        </div>
                        <div class="text-center">
                            <p class="text-xs text-gray-500 uppercase mb-1">Guests</p>
                            <p class="text-lg font-bold text-gray-800"><?php echo $reservation['guests'] ?? 1; ?></p>
                        </div>
                    </div>
                </div>

                <!-- Uploaded Receipt Image -->
                <?php if (!empty($payment['receipt_path'])): ?>
                <div class="mb-8">
                    <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wide mb-3">Uploaded Receipt</h3>
                    <div class="border-2 border-dashed border-gray-200 rounded-xl p-4">
                        <?php 
                        $receiptUrl = BASE_URL . '/' . $payment['receipt_path'];
                        $isImage = preg_match('/\.(jpg|jpeg|png|gif)$/i', $payment['receipt_path']);
                        ?>
                        <?php if ($isImage): ?>
                            <img src="<?php echo htmlspecialchars($receiptUrl); ?>" alt="Receipt" class="max-w-full h-auto rounded-lg mx-auto" style="max-height: 400px;">
                        <?php else: ?>
                            <a href="<?php echo htmlspecialchars($receiptUrl); ?>" target="_blank" class="flex items-center justify-center p-4 text-sage-600 hover:text-sage-700">
                                <svg class="w-8 h-8 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                                </svg>
                                <span class="font-semibold">View Receipt Document</span>
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endif; ?>

                <!-- Footer Note -->
                <div class="text-center text-gray-500 text-sm">
                    <p>Thank you for your payment!</p>
                    <p class="mt-1">For any inquiries, please contact our support team.</p>
                </div>
            </div>

            <!-- Print Footer -->
            <div class="bg-gray-50 px-8 py-4 border-t border-gray-200 flex justify-between items-center">
                <p class="text-sm text-gray-500">
                    Generated on <?php echo date('F j, Y \a\t g:i A'); ?>
                </p>
                <button onclick="printReceipt()" class="inline-flex items-center px-4 py-2 bg-sage-500 text-white rounded-lg hover:bg-sage-600 transition">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                    </svg>
                    Print Receipt
                </button>
            </div>
        </div>
    </div>
</div>

<style>
@media print {
    body * {
        visibility: hidden;
    }
    #receipt, #receipt * {
        visibility: visible;
    }
    #receipt {
        position: absolute;
        left: 0;
        top: 0;
        width: 100%;
        box-shadow: none !important;
    }
    button {
        display: none !important;
    }
}
</style>

<script>
function printReceipt() {
    window.print();
}
</script>

<?php include __DIR__ . '/../layouts/footer.php'; ?>
