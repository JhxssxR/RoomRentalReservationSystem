<?php
$pageTitle = 'My Payments - Room Rental';
include __DIR__ . '/../layouts/header.php';

// Get pending reservations that need payment
require_once __DIR__ . '/../../models/Reservation.php';
require_once __DIR__ . '/../../models/Room.php';

$reservationModel = new Reservation();
$roomModel = new Room();

// Get reservations with pending/pending_payment status
$pendingReservations = $reservationModel->getByCustomerId($_SESSION['user_id']);
$pendingReservations = array_filter($pendingReservations, function($r) {
    $status = strtolower($r['status']);
    return in_array($status, ['pending', 'pending_payment']);
});
?>

<div class="min-h-screen bg-gradient-to-br from-pale-50 to-sage-50 py-12">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-800">Payments</h1>
            <p class="text-gray-600 mt-2">Manage your reservation payments</p>
        </div>

        <!-- Tabs -->
        <div class="bg-white rounded-xl shadow-lg overflow-hidden mb-8">
            <div class="border-b border-gray-200">
                <nav class="flex -mb-px" aria-label="Tabs">
                    <button onclick="showTab('pending')" id="tab-pending" class="tab-btn w-1/2 py-4 px-1 text-center border-b-2 border-sage-500 font-medium text-sage-600">
                        Pending Payments
                        <?php if (count($pendingReservations) > 0): ?>
                            <span class="ml-2 bg-red-100 text-red-600 text-xs px-2 py-1 rounded-full"><?php echo count($pendingReservations); ?></span>
                        <?php endif; ?>
                    </button>
                    <button onclick="showTab('history')" id="tab-history" class="tab-btn w-1/2 py-4 px-1 text-center border-b-2 border-transparent font-medium text-gray-500 hover:text-gray-700 hover:border-gray-300">
                        Payment History
                    </button>
                </nav>
            </div>

            <!-- Pending Payments Tab -->
            <div id="content-pending" class="tab-content p-6">
                <?php if (empty($pendingReservations)): ?>
                    <div class="text-center py-12">
                        <div class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-10 h-10 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                        </div>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">All caught up!</h3>
                        <p class="text-gray-500">You have no pending payments at this time.</p>
                        <a href="<?php echo BASE_URL; ?>/rooms" class="inline-block mt-6 px-6 py-3 bg-sage-500 text-white font-semibold rounded-lg hover:bg-sage-600 transition">
                            Browse Rooms
                        </a>
                    </div>
                <?php else: ?>
                    <div class="space-y-4">
                        <?php foreach ($pendingReservations as $reservation): 
                            $room = $roomModel->getById($reservation['room_id']);
                        ?>
                            <div class="bg-gradient-to-r from-sage-50 to-pale-50 rounded-xl p-6 border border-sage-100">
                                <div class="flex flex-col lg:flex-row gap-6">
                                    <!-- Room Image -->
                                    <div class="w-full lg:w-48 flex-shrink-0">
                                        <img src="<?php echo htmlspecialchars($room['photo_url'] ?? 'https://images.unsplash.com/photo-1631049307264-da0ec9d70304?w=300'); ?>" 
                                             alt="Room" class="w-full h-32 lg:h-full object-cover rounded-lg">
                                    </div>
                                    
                                    <!-- Reservation Details -->
                                    <div class="flex-1">
                                        <div class="flex justify-between items-start mb-4">
                                            <div>
                                                <h3 class="text-lg font-bold text-gray-800">
                                                    <?php echo htmlspecialchars($room['name'] ?? 'Room ' . $room['room_number']); ?>
                                                </h3>
                                                <p class="text-sage-600"><?php echo htmlspecialchars($room['room_type']); ?> • Room <?php echo htmlspecialchars($room['room_number']); ?></p>
                                            </div>
                                            <span class="px-3 py-1 bg-yellow-100 text-yellow-700 text-sm font-semibold rounded-full">
                                                <?php echo ucfirst($reservation['status']); ?>
                                            </span>
                                        </div>
                                        
                                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-4">
                                            <div>
                                                <p class="text-xs text-gray-500 uppercase tracking-wide">Check-in</p>
                                                <p class="font-semibold text-gray-800"><?php echo date('M j, Y', strtotime($reservation['check_in'])); ?></p>
                                            </div>
                                            <div>
                                                <p class="text-xs text-gray-500 uppercase tracking-wide">Check-out</p>
                                                <p class="font-semibold text-gray-800"><?php echo date('M j, Y', strtotime($reservation['check_out'])); ?></p>
                                            </div>
                                            <div>
                                                <p class="text-xs text-gray-500 uppercase tracking-wide">Reservation #</p>
                                                <p class="font-semibold text-gray-800"><?php echo $reservation['reservation_id']; ?></p>
                                            </div>
                                            <div>
                                                <p class="text-xs text-gray-500 uppercase tracking-wide">Amount Due</p>
                                                <p class="font-bold text-2xl bg-gradient-to-r from-sage-600 to-pale-600 bg-clip-text text-transparent">
                                                    ₱<?php echo number_format($reservation['total_price'], 2); ?>
                                                </p>
                                            </div>
                                        </div>
                                        
                                        <div class="flex gap-3">
                                            <a href="<?php echo BASE_URL; ?>/payments?reservation_id=<?php echo $reservation['reservation_id']; ?>" 
                                               class="flex-1 sm:flex-none px-6 py-3 bg-gradient-to-r from-sage-500 to-pale-500 text-white font-semibold rounded-lg hover:from-sage-600 hover:to-pale-600 transition text-center">
                                                <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                                </svg>
                                                Pay Now
                                            </a>
                                            <a href="<?php echo BASE_URL; ?>/reservations/<?php echo $reservation['reservation_id']; ?>" 
                                               class="px-6 py-3 border border-gray-300 text-gray-700 font-semibold rounded-lg hover:bg-gray-50 transition text-center">
                                                View Details
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Payment History Tab -->
            <div id="content-history" class="tab-content p-6 hidden">
                <?php if (empty($payments)): ?>
                    <div class="text-center py-12">
                        <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                            </svg>
                        </div>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">No payment history</h3>
                        <p class="text-gray-500">Your payment history will appear here once you make a payment.</p>
                    </div>
                <?php else: ?>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Payment ID</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Room</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Action</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <?php foreach ($payments as $payment): ?>
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                            #<?php echo $payment['payment_id']; ?>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                            <?php echo htmlspecialchars($payment['room_name'] ?? 'Room ' . ($payment['room_number'] ?? 'N/A')); ?>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900">
                                            ₱<?php echo number_format($payment['amount'], 2); ?>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                            <?php echo date('M j, Y', strtotime($payment['payment_date'])); ?>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <?php 
                                            $status = strtolower($payment['status']);
                                            $statusClass = match($status) {
                                                'confirmed', 'approved', 'completed' => 'bg-green-100 text-green-800',
                                                'pending' => 'bg-yellow-100 text-yellow-800',
                                                'rejected', 'failed' => 'bg-red-100 text-red-800',
                                                default => 'bg-gray-100 text-gray-800'
                                            };
                                            ?>
                                            <span class="px-2 py-1 text-xs font-semibold rounded-full <?php echo $statusClass; ?>">
                                                <?php echo ucfirst($payment['status']); ?>
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                                            <a href="<?php echo BASE_URL; ?>/payments/<?php echo $payment['payment_id']; ?>/receipt" 
                                               class="text-sage-600 hover:text-sage-800 font-medium">
                                                View Receipt
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Payment Instructions -->
        <div class="bg-white rounded-xl shadow-lg p-6">
            <h3 class="text-lg font-bold text-gray-800 mb-4">Payment Instructions</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="flex items-start">
                    <div class="w-10 h-10 bg-sage-100 rounded-lg flex items-center justify-center mr-4 flex-shrink-0">
                        <span class="text-sage-600 font-bold">1</span>
                    </div>
                    <div>
                        <h4 class="font-semibold text-gray-800">Select Reservation</h4>
                        <p class="text-sm text-gray-600">Click "Pay Now" on the reservation you want to pay for.</p>
                    </div>
                </div>
                <div class="flex items-start">
                    <div class="w-10 h-10 bg-pale-100 rounded-lg flex items-center justify-center mr-4 flex-shrink-0">
                        <span class="text-pale-600 font-bold">2</span>
                    </div>
                    <div>
                        <h4 class="font-semibold text-gray-800">Upload Receipt</h4>
                        <p class="text-sm text-gray-600">Complete the payment and upload proof of payment.</p>
                    </div>
                </div>
                <div class="flex items-start">
                    <div class="w-10 h-10 bg-amber-100 rounded-lg flex items-center justify-center mr-4 flex-shrink-0">
                        <span class="text-amber-600 font-bold">3</span>
                    </div>
                    <div>
                        <h4 class="font-semibold text-gray-800">Wait for Confirmation</h4>
                        <p class="text-sm text-gray-600">Admin will verify and confirm your payment.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function showTab(tab) {
    // Hide all tab contents
    document.querySelectorAll('.tab-content').forEach(el => el.classList.add('hidden'));
    // Remove active state from all tabs
    document.querySelectorAll('.tab-btn').forEach(el => {
        el.classList.remove('border-sage-500', 'text-sage-600');
        el.classList.add('border-transparent', 'text-gray-500');
    });
    
    // Show selected tab content
    document.getElementById('content-' + tab).classList.remove('hidden');
    // Add active state to selected tab
    const activeTab = document.getElementById('tab-' + tab);
    activeTab.classList.remove('border-transparent', 'text-gray-500');
    activeTab.classList.add('border-sage-500', 'text-sage-600');
}
</script>

<?php include __DIR__ . '/../layouts/footer.php'; ?>
