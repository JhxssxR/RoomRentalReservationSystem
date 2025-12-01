<?php
$pageTitle = ($room['name'] ?? 'Room ' . $room['room_number']) . ' - Room Rental';
include __DIR__ . '/../layouts/header.php';
?>

<div class="min-h-screen bg-gradient-to-br from-pale-50 to-sage-50 py-12">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Breadcrumb -->
        <nav class="mb-8">
            <ol class="flex items-center space-x-2 text-sm">
                <li><a href="<?php echo BASE_URL; ?>" class="text-gray-500 hover:text-sage-600">Home</a></li>
                <li><svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg></li>
                <li><a href="<?php echo BASE_URL; ?>/rooms" class="text-gray-500 hover:text-sage-600">Rooms</a></li>
                <li><svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg></li>
                <li class="text-sage-700 font-medium"><?php echo htmlspecialchars($room['name'] ?? 'Room ' . $room['room_number']); ?></li>
            </ol>
        </nav>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Main Content -->
            <div class="lg:col-span-2 space-y-8">
                <!-- Main Image -->
                <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
                    <div class="relative h-96">
                        <img src="<?php echo htmlspecialchars($room['photo_url'] ?? 'https://images.unsplash.com/photo-1631049307264-da0ec9d70304?w=1200'); ?>" 
                             alt="<?php echo htmlspecialchars($room['name'] ?? $room['room_type']); ?>"
                             class="w-full h-full object-cover">
                        <div class="absolute top-4 left-4">
                            <span class="bg-gradient-to-r from-sage-500 to-pale-500 text-white px-4 py-2 rounded-full text-sm font-semibold shadow-lg">
                                <?php echo htmlspecialchars($room['room_type']); ?>
                            </span>
                        </div>
                        <div class="absolute top-4 right-4">
                            <span class="bg-white/90 backdrop-blur px-4 py-2 rounded-full text-sm font-semibold shadow-lg
                                <?php echo $room['status'] === 'Available' ? 'text-emerald-600' : 'text-red-600'; ?>">
                                <?php echo $room['status']; ?>
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Room Details -->
                <div class="bg-white rounded-2xl shadow-xl p-8">
                    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-start mb-6">
                        <div>
                            <h1 class="text-3xl font-bold text-gray-800">
                                <?php echo htmlspecialchars($room['name'] ?? 'Room ' . $room['room_number']); ?>
                            </h1>
                            <p class="text-gray-500 mt-1">Room #<?php echo htmlspecialchars($room['room_number']); ?></p>
                        </div>
                        <div class="mt-4 sm:mt-0 text-right">
                            <p class="text-4xl font-bold bg-gradient-to-r from-sage-600 to-pale-600 bg-clip-text text-transparent">
                                ₱<?php echo number_format($room['price'], 2); ?>
                            </p>
                            <p class="text-gray-500">per night</p>
                        </div>
                    </div>

                    <!-- Room Features -->
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mb-8">
                        <div class="bg-sage-50 rounded-xl p-4 text-center">
                            <svg class="w-8 h-8 text-sage-600 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                            <p class="text-2xl font-bold text-gray-800"><?php echo $room['capacity']; ?></p>
                            <p class="text-sm text-gray-500">Max Guests</p>
                        </div>
                        <div class="bg-pale-50 rounded-xl p-4 text-center">
                            <svg class="w-8 h-8 text-pale-600 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                            </svg>
                            <p class="text-lg font-bold text-gray-800"><?php echo htmlspecialchars($room['room_type']); ?></p>
                            <p class="text-sm text-gray-500">Room Type</p>
                        </div>
                        <div class="bg-amber-50 rounded-xl p-4 text-center">
                            <svg class="w-8 h-8 text-amber-600 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            <p class="text-lg font-bold text-gray-800">32m²</p>
                            <p class="text-sm text-gray-500">Room Size</p>
                        </div>
                        <div class="bg-emerald-50 rounded-xl p-4 text-center">
                            <svg class="w-8 h-8 text-emerald-600 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path>
                            </svg>
                            <p class="text-lg font-bold text-gray-800">4.8</p>
                            <p class="text-sm text-gray-500">Rating</p>
                        </div>
                    </div>

                    <!-- Description -->
                    <div class="mb-8">
                        <h2 class="text-xl font-bold text-gray-800 mb-4">About This Room</h2>
                        <p class="text-gray-600 leading-relaxed">
                            Experience comfort and elegance in our <?php echo htmlspecialchars($room['room_type']); ?> room. 
                            This beautifully designed space accommodates up to <?php echo $room['capacity']; ?> guests and features 
                            modern amenities to ensure a pleasant stay. Perfect for both business and leisure travelers looking 
                            for a comfortable retreat in the city.
                        </p>
                    </div>

                    <!-- Amenities -->
                    <div>
                        <h2 class="text-xl font-bold text-gray-800 mb-4">Room Amenities</h2>
                        <div class="grid grid-cols-2 sm:grid-cols-3 gap-4">
                            <div class="flex items-center p-3 bg-gray-50 rounded-lg">
                                <svg class="w-5 h-5 text-sage-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.111 16.404a5.5 5.5 0 017.778 0M12 20h.01m-7.08-7.071c3.904-3.905 10.236-3.905 14.141 0M1.394 9.393c5.857-5.857 15.355-5.857 21.213 0"></path>
                                </svg>
                                <span class="text-gray-700">Free WiFi</span>
                            </div>
                            <div class="flex items-center p-3 bg-gray-50 rounded-lg">
                                <svg class="w-5 h-5 text-sage-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                </svg>
                                <span class="text-gray-700">Smart TV</span>
                            </div>
                            <div class="flex items-center p-3 bg-gray-50 rounded-lg">
                                <svg class="w-5 h-5 text-sage-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                </svg>
                                <span class="text-gray-700">Air Conditioning</span>
                            </div>
                            <div class="flex items-center p-3 bg-gray-50 rounded-lg">
                                <svg class="w-5 h-5 text-sage-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path>
                                </svg>
                                <span class="text-gray-700">Daily Housekeeping</span>
                            </div>
                            <div class="flex items-center p-3 bg-gray-50 rounded-lg">
                                <svg class="w-5 h-5 text-sage-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                                </svg>
                                <span class="text-gray-700">Safe Deposit Box</span>
                            </div>
                            <div class="flex items-center p-3 bg-gray-50 rounded-lg">
                                <svg class="w-5 h-5 text-sage-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                                </svg>
                                <span class="text-gray-700">Mini Bar</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar - Booking Card -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-2xl shadow-xl p-6 sticky top-24">
                    <div class="text-center mb-6">
                        <p class="text-gray-500 text-sm">Starting from</p>
                        <p class="text-4xl font-bold bg-gradient-to-r from-sage-600 to-pale-600 bg-clip-text text-transparent">
                            ₱<?php echo number_format($room['price'], 2); ?>
                        </p>
                        <p class="text-gray-500">per night</p>
                    </div>

                    <?php if ($room['status'] === 'Available'): ?>
                        <a href="<?php echo BASE_URL; ?>/rooms/<?php echo $room['room_id']; ?>/book" 
                           class="block w-full py-4 px-6 bg-gradient-to-r from-sage-500 to-pale-500 hover:from-sage-600 hover:to-pale-600 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl transition-all text-center transform hover:scale-[1.02]">
                            <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            Book Now
                        </a>
                    <?php else: ?>
                        <button disabled 
                                class="block w-full py-4 px-6 bg-gray-300 text-gray-500 font-semibold rounded-xl cursor-not-allowed text-center">
                            Currently Unavailable
                        </button>
                    <?php endif; ?>

                    <div class="mt-6 space-y-4 text-sm">
                        <div class="flex items-center text-gray-600">
                            <svg class="w-5 h-5 text-emerald-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            Free cancellation up to 24 hours
                        </div>
                        <div class="flex items-center text-gray-600">
                            <svg class="w-5 h-5 text-emerald-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            Instant confirmation
                        </div>
                        <div class="flex items-center text-gray-600">
                            <svg class="w-5 h-5 text-emerald-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            Best price guarantee
                        </div>
                    </div>

                    <div class="mt-6 pt-6 border-t border-gray-100">
                        <p class="text-gray-500 text-sm mb-3">Need help?</p>
                        <a href="tel:+639123456789" class="flex items-center text-sage-600 hover:text-sage-700 font-medium">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                            </svg>
                            +63 912 345 6789
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../layouts/footer.php'; ?>
