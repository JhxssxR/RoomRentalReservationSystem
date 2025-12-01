<?php
$pageTitle = 'Make Payment - Room Rental';
include __DIR__ . '/../layouts/header.php';

// Get room details
require_once __DIR__ . '/../../models/Room.php';
$roomModel = new Room();
$room = $roomModel->getById($reservation['room_id']);

$checkIn = new DateTime($reservation['check_in']);
$checkOut = new DateTime($reservation['check_out']);
$nights = $checkIn->diff($checkOut)->days;
?>

<div class="min-h-screen bg-gradient-to-br from-pale-50 to-sage-50 py-12">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Breadcrumb -->
        <nav class="mb-8">
            <ol class="flex items-center space-x-2 text-sm">
                <li><a href="<?php echo BASE_URL; ?>" class="text-gray-500 hover:text-sage-600">Home</a></li>
                <li><svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg></li>
                <li><a href="<?php echo BASE_URL; ?>/payments" class="text-gray-500 hover:text-sage-600">Payments</a></li>
                <li><svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg></li>
                <li class="text-sage-700 font-medium">Pay for Reservation #<?php echo $reservation['reservation_id']; ?></li>
            </ol>
        </nav>

        <?php if (isset($_SESSION['errors'])): ?>
            <div class="bg-red-50 border border-red-200 text-red-700 px-6 py-4 rounded-xl mb-6">
                <ul class="list-disc list-inside">
                    <?php foreach ($_SESSION['errors'] as $error): ?>
                        <li><?php echo htmlspecialchars($error); ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
            <?php unset($_SESSION['errors']); ?>
        <?php endif; ?>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Payment Form -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
                    <div class="bg-gradient-to-r from-sage-500 to-pale-500 px-8 py-6">
                        <h1 class="text-2xl font-bold text-white">Complete Your Payment</h1>
                        <p class="text-white/80 mt-1">Reservation #<?php echo $reservation['reservation_id']; ?></p>
                    </div>

                    <form action="<?php echo BASE_URL; ?>/payments/process" method="POST" enctype="multipart/form-data" class="p-8">
                        <input type="hidden" name="reservation_id" value="<?php echo $reservation['reservation_id']; ?>">
                        <input type="hidden" name="amount" value="<?php echo $reservation['total_price']; ?>">

                        <!-- Payment Method -->
                        <div class="mb-8">
                            <label class="block text-sm font-medium text-gray-700 mb-4">Select Payment Method</label>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <label class="relative cursor-pointer">
                                    <input type="radio" name="payment_method" value="gcash" class="peer sr-only" checked>
                                    <div class="p-4 border-2 border-gray-200 rounded-xl peer-checked:border-sage-500 peer-checked:bg-sage-50 transition">
                                        <div class="flex items-center">
                                            <div class="w-12 h-12 bg-blue-500 rounded-lg flex items-center justify-center mr-4">
                                                <span class="text-white font-bold text-sm">G</span>
                                            </div>
                                            <div>
                                                <p class="font-semibold text-gray-800">GCash</p>
                                                <p class="text-sm text-gray-500">Mobile wallet payment</p>
                                            </div>
                                        </div>
                                    </div>
                                </label>
                                
                                <label class="relative cursor-pointer">
                                    <input type="radio" name="payment_method" value="bank_transfer" class="peer sr-only">
                                    <div class="p-4 border-2 border-gray-200 rounded-xl peer-checked:border-sage-500 peer-checked:bg-sage-50 transition">
                                        <div class="flex items-center">
                                            <div class="w-12 h-12 bg-green-600 rounded-lg flex items-center justify-center mr-4">
                                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                                                </svg>
                                            </div>
                                            <div>
                                                <p class="font-semibold text-gray-800">Bank Transfer</p>
                                                <p class="text-sm text-gray-500">Direct bank deposit</p>
                                            </div>
                                        </div>
                                    </div>
                                </label>

                                <label class="relative cursor-pointer">
                                    <input type="radio" name="payment_method" value="paymaya" class="peer sr-only">
                                    <div class="p-4 border-2 border-gray-200 rounded-xl peer-checked:border-sage-500 peer-checked:bg-sage-50 transition">
                                        <div class="flex items-center">
                                            <div class="w-12 h-12 bg-green-500 rounded-lg flex items-center justify-center mr-4">
                                                <span class="text-white font-bold text-sm">M</span>
                                            </div>
                                            <div>
                                                <p class="font-semibold text-gray-800">PayMaya</p>
                                                <p class="text-sm text-gray-500">Digital payment</p>
                                            </div>
                                        </div>
                                    </div>
                                </label>

                                <label class="relative cursor-pointer">
                                    <input type="radio" name="payment_method" value="cash" class="peer sr-only">
                                    <div class="p-4 border-2 border-gray-200 rounded-xl peer-checked:border-sage-500 peer-checked:bg-sage-50 transition">
                                        <div class="flex items-center">
                                            <div class="w-12 h-12 bg-amber-500 rounded-lg flex items-center justify-center mr-4">
                                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                                </svg>
                                            </div>
                                            <div>
                                                <p class="font-semibold text-gray-800">Cash</p>
                                                <p class="text-sm text-gray-500">Pay upon check-in</p>
                                            </div>
                                        </div>
                                    </div>
                                </label>
                            </div>
                        </div>

                        <!-- Payment Details (GCash/Bank) -->
                        <div id="payment-details" class="mb-8 p-6 bg-gray-50 rounded-xl">
                            <h3 class="font-semibold text-gray-800 mb-4">Payment Details</h3>
                            <div id="gcash-details">
                                <p class="text-gray-600 mb-2"><strong>GCash Number:</strong> 0917-123-4567</p>
                                <p class="text-gray-600 mb-2"><strong>Account Name:</strong> Room Rental Services</p>
                                <p class="text-sm text-gray-500">Send the exact amount and upload the receipt below.</p>
                            </div>
                            <div id="bank-details" class="hidden">
                                <p class="text-gray-600 mb-2"><strong>Bank:</strong> BDO Unibank</p>
                                <p class="text-gray-600 mb-2"><strong>Account Number:</strong> 1234-5678-9012</p>
                                <p class="text-gray-600 mb-2"><strong>Account Name:</strong> Room Rental Services Inc.</p>
                                <p class="text-sm text-gray-500">Transfer the exact amount and upload the receipt below.</p>
                            </div>
                            <div id="paymaya-details" class="hidden">
                                <p class="text-gray-600 mb-2"><strong>PayMaya Number:</strong> 0917-123-4567</p>
                                <p class="text-gray-600 mb-2"><strong>Account Name:</strong> Room Rental Services</p>
                                <p class="text-sm text-gray-500">Send the exact amount and upload the receipt below.</p>
                            </div>
                            <div id="cash-details" class="hidden">
                                <p class="text-gray-600 mb-2">You will pay the full amount upon check-in.</p>
                                <p class="text-sm text-gray-500">Please arrive on time with the exact amount ready.</p>
                            </div>
                        </div>

                        <!-- Receipt Upload -->
                        <div id="receipt-upload" class="mb-8">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Upload Payment Receipt</label>
                            <div class="border-2 border-dashed border-gray-300 rounded-xl p-8 text-center hover:border-sage-400 transition cursor-pointer" onclick="document.getElementById('receipt').click()">
                                <input type="file" name="receipt" id="receipt" class="hidden" accept="image/*,.pdf">
                                <svg class="w-12 h-12 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                                </svg>
                                <p class="text-gray-600 mb-2">Click to upload or drag and drop</p>
                                <p class="text-sm text-gray-500">PNG, JPG, or PDF up to 5MB</p>
                                <p id="file-name" class="mt-2 text-sage-600 font-medium hidden"></p>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <button type="submit" class="w-full py-4 bg-gradient-to-r from-sage-500 to-pale-500 text-white font-bold rounded-xl hover:from-sage-600 hover:to-pale-600 transition transform hover:scale-[1.02] shadow-lg">
                            <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Submit Payment
                        </button>
                    </form>
                </div>
            </div>

            <!-- Order Summary -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-2xl shadow-xl overflow-hidden sticky top-8">
                    <div class="p-6 border-b border-gray-100">
                        <h3 class="font-bold text-gray-800">Order Summary</h3>
                    </div>
                    
                    <!-- Room Info -->
                    <div class="p-6">
                        <img src="<?php echo htmlspecialchars($room['photo_url'] ?? 'https://images.unsplash.com/photo-1631049307264-da0ec9d70304?w=400'); ?>" 
                             alt="Room" class="w-full h-40 object-cover rounded-lg mb-4">
                        
                        <h4 class="font-bold text-gray-800"><?php echo htmlspecialchars($room['name'] ?? 'Room ' . $room['room_number']); ?></h4>
                        <p class="text-sage-600 text-sm"><?php echo htmlspecialchars($room['room_type']); ?></p>
                        
                        <div class="mt-4 space-y-3 text-sm">
                            <div class="flex justify-between">
                                <span class="text-gray-500">Check-in</span>
                                <span class="font-medium"><?php echo $checkIn->format('M j, Y'); ?></span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-500">Check-out</span>
                                <span class="font-medium"><?php echo $checkOut->format('M j, Y'); ?></span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-500">Duration</span>
                                <span class="font-medium"><?php echo $nights; ?> night<?php echo $nights > 1 ? 's' : ''; ?></span>
                            </div>
                        </div>

                        <div class="border-t border-gray-100 mt-4 pt-4">
                            <div class="flex justify-between text-sm mb-2">
                                <span class="text-gray-500">₱<?php echo number_format($room['price'], 2); ?> × <?php echo $nights; ?> night<?php echo $nights > 1 ? 's' : ''; ?></span>
                                <span>₱<?php echo number_format($reservation['total_price'], 2); ?></span>
                            </div>
                            <div class="flex justify-between font-bold text-lg mt-4 pt-4 border-t border-gray-100">
                                <span>Total</span>
                                <span class="bg-gradient-to-r from-sage-600 to-pale-600 bg-clip-text text-transparent">
                                    ₱<?php echo number_format($reservation['total_price'], 2); ?>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Handle payment method change
document.querySelectorAll('input[name="payment_method"]').forEach(radio => {
    radio.addEventListener('change', function() {
        // Hide all details
        document.getElementById('gcash-details').classList.add('hidden');
        document.getElementById('bank-details').classList.add('hidden');
        document.getElementById('paymaya-details').classList.add('hidden');
        document.getElementById('cash-details').classList.add('hidden');
        
        // Show selected details
        document.getElementById(this.value.replace('_', '-') + '-details')?.classList.remove('hidden');
        if (this.value === 'bank_transfer') {
            document.getElementById('bank-details').classList.remove('hidden');
        }
        
        // Toggle receipt upload visibility
        if (this.value === 'cash') {
            document.getElementById('receipt-upload').classList.add('hidden');
        } else {
            document.getElementById('receipt-upload').classList.remove('hidden');
        }
    });
});

// Handle file input change
document.getElementById('receipt').addEventListener('change', function() {
    const fileName = this.files[0]?.name;
    const fileNameEl = document.getElementById('file-name');
    if (fileName) {
        fileNameEl.textContent = 'Selected: ' + fileName;
        fileNameEl.classList.remove('hidden');
    } else {
        fileNameEl.classList.add('hidden');
    }
});
</script>

<?php include __DIR__ . '/../layouts/footer.php'; ?>
