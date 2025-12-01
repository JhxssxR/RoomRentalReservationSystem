<?php
$pageTitle = 'Reservation Details - Room Rental';
include __DIR__ . '/../layouts/header.php';
?>

<div class="min-h-screen bg-gradient-to-br from-pale-50 to-sage-50 py-12">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Breadcrumb -->
        <nav class="mb-8">
            <ol class="flex items-center space-x-2 text-sm">
                <li><a href="<?php echo BASE_URL; ?>" class="text-gray-500 hover:text-sage-600">Home</a></li>
                <li><svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg></li>
                <li><a href="<?php echo BASE_URL; ?>/reservations" class="text-gray-500 hover:text-sage-600">My Bookings</a></li>
                <li><svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg></li>
                <li class="text-sage-700 font-medium">Reservation #<?php echo $reservation['reservation_id']; ?></li>
            </ol>
        </nav>

        <!-- Reservation Card -->
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
            <!-- Header -->
            <div class="bg-gradient-to-r from-sage-500 to-pale-500 px-8 py-6">
                <div class="flex justify-between items-center">
                    <div>
                        <h1 class="text-2xl font-bold text-white">Reservation #<?php echo $reservation['reservation_id']; ?></h1>
                        <p class="text-white/80 mt-1">Booked on <?php echo date('F j, Y', strtotime($reservation['created_at'])); ?></p>
                    </div>
                    <span class="px-4 py-2 rounded-full text-sm font-semibold
                        <?php 
                        $status = strtolower($reservation['status']);
                        if ($status === 'confirmed' || $status === 'approved') echo 'bg-green-100 text-green-800';
                        elseif ($status === 'pending' || $status === 'pending_payment') echo 'bg-yellow-100 text-yellow-800';
                        elseif ($status === 'cancelled' || $status === 'rejected') echo 'bg-red-100 text-red-800';
                        elseif ($status === 'completed') echo 'bg-blue-100 text-blue-800';
                        else echo 'bg-gray-100 text-gray-800';
                        ?>">
                        <?php echo ucfirst($reservation['status']); ?>
                    </span>
                </div>
            </div>

            <div class="p-8">
                <!-- Room Info -->
                <div class="flex flex-col md:flex-row gap-6 mb-8 pb-8 border-b border-gray-100">
                    <?php if ($room): ?>
                    <div class="w-full md:w-1/3">
                        <img src="<?php echo htmlspecialchars($room['photo_url'] ?? 'https://images.unsplash.com/photo-1631049307264-da0ec9d70304?w=400'); ?>" 
                             alt="<?php echo htmlspecialchars($room['name'] ?? 'Room'); ?>"
                             class="w-full h-48 object-cover rounded-xl">
                    </div>
                    <div class="flex-1">
                        <h2 class="text-xl font-bold text-gray-800"><?php echo htmlspecialchars($room['name'] ?? 'Room ' . $room['room_number']); ?></h2>
                        <p class="text-sage-600 font-medium"><?php echo htmlspecialchars($room['room_type']); ?> • Room <?php echo htmlspecialchars($room['room_number']); ?></p>
                        
                        <div class="mt-4 grid grid-cols-2 gap-4">
                            <div class="bg-sage-50 rounded-lg p-3">
                                <p class="text-sm text-gray-500">Capacity</p>
                                <p class="font-semibold text-gray-800"><?php echo $room['capacity']; ?> Guest<?php echo $room['capacity'] > 1 ? 's' : ''; ?></p>
                            </div>
                            <div class="bg-pale-50 rounded-lg p-3">
                                <p class="text-sm text-gray-500">Price per Night</p>
                                <p class="font-semibold text-gray-800">₱<?php echo number_format($room['price'], 2); ?></p>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>

                <!-- Booking Details -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Booking Details</h3>
                        <div class="space-y-4">
                            <div class="flex items-center">
                                <div class="w-10 h-10 bg-sage-100 rounded-lg flex items-center justify-center mr-4">
                                    <svg class="w-5 h-5 text-sage-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">Check-in</p>
                                    <p class="font-semibold text-gray-800"><?php echo date('F j, Y', strtotime($reservation['check_in'])); ?></p>
                                </div>
                            </div>
                            <div class="flex items-center">
                                <div class="w-10 h-10 bg-pale-100 rounded-lg flex items-center justify-center mr-4">
                                    <svg class="w-5 h-5 text-pale-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">Check-out</p>
                                    <p class="font-semibold text-gray-800"><?php echo date('F j, Y', strtotime($reservation['check_out'])); ?></p>
                                </div>
                            </div>
                            <?php 
                            $checkIn = new DateTime($reservation['check_in']);
                            $checkOut = new DateTime($reservation['check_out']);
                            $nights = $checkIn->diff($checkOut)->days;
                            ?>
                            <div class="flex items-center">
                                <div class="w-10 h-10 bg-amber-100 rounded-lg flex items-center justify-center mr-4">
                                    <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">Duration</p>
                                    <p class="font-semibold text-gray-800"><?php echo $nights; ?> Night<?php echo $nights > 1 ? 's' : ''; ?></p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div>
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Payment Summary</h3>
                        <div class="bg-gradient-to-br from-sage-50 to-pale-50 rounded-xl p-6">
                            <div class="space-y-3">
                                <div class="flex justify-between text-gray-600">
                                    <span>Room Rate (<?php echo $nights; ?> night<?php echo $nights > 1 ? 's' : ''; ?>)</span>
                                    <span>₱<?php echo number_format($reservation['total_price'], 2); ?></span>
                                </div>
                                <div class="border-t border-gray-200 pt-3 flex justify-between">
                                    <span class="text-lg font-bold text-gray-800">Total</span>
                                    <span class="text-2xl font-bold bg-gradient-to-r from-sage-600 to-pale-600 bg-clip-text text-transparent">
                                        ₱<?php echo number_format($reservation['total_price'], 2); ?>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="flex flex-col sm:flex-row gap-4">
                    <a href="<?php echo BASE_URL; ?>/reservations" class="flex-1 py-3 px-6 border-2 border-sage-500 text-sage-600 hover:bg-sage-50 font-semibold rounded-xl text-center transition">
                        <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        Back to Bookings
                    </a>
                    
                    <?php 
                    $canCancel = in_array(strtolower($reservation['status']), ['pending', 'pending_payment', 'confirmed', 'approved']);
                    $isWithin24Hours = strtotime($reservation['check_in']) < strtotime('+24 hours');
                    ?>
                    
                    <?php if ($canCancel && !$isWithin24Hours): ?>
                    <a href="<?php echo BASE_URL; ?>/reservations/<?php echo $reservation['reservation_id']; ?>/cancel" 
                       onclick="return confirm('Are you sure you want to cancel this reservation?')"
                       class="flex-1 py-3 px-6 bg-red-500 hover:bg-red-600 text-white font-semibold rounded-xl text-center transition">
                        <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                        Cancel Reservation
                    </a>
                    <?php elseif ($canCancel && $isWithin24Hours): ?>
                    <button disabled class="flex-1 py-3 px-6 bg-gray-300 text-gray-500 font-semibold rounded-xl text-center cursor-not-allowed">
                        Cannot cancel within 24 hours
                    </button>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Help Section -->
        <div class="mt-8 bg-white rounded-xl shadow-lg p-6">
            <h3 class="font-semibold text-gray-800 mb-3">Need Help?</h3>
            <p class="text-gray-600 text-sm mb-4">If you have any questions about your reservation, please don't hesitate to contact us.</p>
            <div class="flex items-center text-sage-600">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                </svg>
                <a href="tel:+639123456789" class="font-medium hover:underline">+63 912 345 6789</a>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../layouts/footer.php'; ?>
