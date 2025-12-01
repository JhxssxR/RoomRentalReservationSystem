<?php
$pageTitle = 'Book Room - Room Rental';
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
                <li class="text-sage-700 font-medium">Book Room</li>
            </ol>
        </nav>

        <!-- Error Messages -->
        <?php if (!empty($errors)): ?>
            <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-6 py-4 rounded-xl">
                <div class="flex items-center mb-2">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span class="font-medium">Please fix the following errors:</span>
                </div>
                <ul class="list-disc list-inside space-y-1 text-sm">
                    <?php foreach ($errors as $error): ?>
                        <li><?php echo htmlspecialchars($error); ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Room Details -->
            <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
                <div class="relative h-72">
                    <img src="<?php echo htmlspecialchars($room['photo_url'] ?? 'https://images.unsplash.com/photo-1631049307264-da0ec9d70304?w=800'); ?>" 
                         alt="<?php echo htmlspecialchars($room['name'] ?? $room['room_type']); ?>"
                         class="w-full h-full object-cover">
                    <div class="absolute top-4 right-4">
                        <span class="bg-white/90 backdrop-blur text-sage-700 px-4 py-2 rounded-full text-sm font-semibold shadow-lg">
                            <?php echo htmlspecialchars($room['room_type']); ?>
                        </span>
                    </div>
                </div>
                <div class="p-6">
                    <div class="flex justify-between items-start mb-4">
                        <div>
                            <h1 class="text-2xl font-bold text-gray-800">
                                <?php echo htmlspecialchars($room['name'] ?? 'Room ' . $room['room_number']); ?>
                            </h1>
                            <p class="text-gray-500">Room #<?php echo htmlspecialchars($room['room_number']); ?></p>
                        </div>
                        <div class="text-right">
                            <p class="text-3xl font-bold bg-gradient-to-r from-sage-600 to-pale-600 bg-clip-text text-transparent">
                                ₱<?php echo number_format($room['price'], 2); ?>
                            </p>
                            <p class="text-sm text-gray-500">per night</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4 mb-6">
                        <div class="bg-sage-50 rounded-xl p-4">
                            <div class="flex items-center text-sage-700">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                </svg>
                                <span class="text-sm font-medium">Max Guests</span>
                            </div>
                            <p class="text-2xl font-bold text-gray-800 mt-1"><?php echo $room['capacity']; ?></p>
                        </div>
                        <div class="bg-pale-50 rounded-xl p-4">
                            <div class="flex items-center text-pale-700">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <span class="text-sm font-medium">Status</span>
                            </div>
                            <p class="text-lg font-bold text-emerald-600 mt-1"><?php echo $room['status']; ?></p>
                        </div>
                    </div>

                    <div class="border-t border-gray-100 pt-4">
                        <h3 class="font-semibold text-gray-800 mb-3">Room Amenities</h3>
                        <div class="flex flex-wrap gap-2">
                            <span class="inline-flex items-center px-3 py-1.5 bg-gray-100 text-gray-700 rounded-lg text-sm">
                                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.111 16.404a5.5 5.5 0 017.778 0M12 20h.01m-7.08-7.071c3.904-3.905 10.236-3.905 14.141 0M1.394 9.393c5.857-5.857 15.355-5.857 21.213 0"></path></svg>
                                Free WiFi
                            </span>
                            <span class="inline-flex items-center px-3 py-1.5 bg-gray-100 text-gray-700 rounded-lg text-sm">
                                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                                Smart TV
                            </span>
                            <span class="inline-flex items-center px-3 py-1.5 bg-gray-100 text-gray-700 rounded-lg text-sm">
                                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                                Air Conditioning
                            </span>
                            <span class="inline-flex items-center px-3 py-1.5 bg-gray-100 text-gray-700 rounded-lg text-sm">
                                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path></svg>
                                Daily Housekeeping
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Booking Form -->
            <div class="bg-white rounded-2xl shadow-xl p-8">
                <div class="flex items-center mb-6">
                    <div class="w-12 h-12 bg-gradient-to-br from-sage-400 to-pale-500 rounded-xl flex items-center justify-center shadow-lg">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <h2 class="text-xl font-bold text-gray-800">Reserve Your Stay</h2>
                        <p class="text-gray-500 text-sm">Fill in the details to book this room</p>
                    </div>
                </div>

                <form method="POST" action="<?php echo BASE_URL; ?>/rooms/<?php echo $room['room_id']; ?>/book" class="space-y-6" id="bookingForm">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="check_in" class="block text-sm font-medium text-gray-700 mb-2">Check-in Date</label>
                            <div class="relative">
                                <input type="date" 
                                       id="check_in" 
                                       name="check_in" 
                                       min="<?php echo date('Y-m-d'); ?>"
                                       value="<?php echo htmlspecialchars($_POST['check_in'] ?? $_GET['check_in'] ?? ''); ?>"
                                       required
                                       class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-sage-500 focus:border-transparent transition-all">
                                <svg class="w-5 h-5 text-gray-400 absolute right-4 top-1/2 -translate-y-1/2 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                        </div>
                        <div>
                            <label for="check_out" class="block text-sm font-medium text-gray-700 mb-2">Check-out Date</label>
                            <div class="relative">
                                <input type="date" 
                                       id="check_out" 
                                       name="check_out" 
                                       min="<?php echo date('Y-m-d', strtotime('+1 day')); ?>"
                                       value="<?php echo htmlspecialchars($_POST['check_out'] ?? $_GET['check_out'] ?? ''); ?>"
                                       required
                                       class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-sage-500 focus:border-transparent transition-all">
                                <svg class="w-5 h-5 text-gray-400 absolute right-4 top-1/2 -translate-y-1/2 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                        </div>
                    </div>

                    <div>
                        <label for="guests" class="block text-sm font-medium text-gray-700 mb-2">Number of Guests</label>
                        <select id="guests" 
                                name="guests" 
                                required
                                class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-sage-500 focus:border-transparent transition-all appearance-none bg-white">
                            <?php for ($i = 1; $i <= $room['capacity']; $i++): ?>
                                <option value="<?php echo $i; ?>" <?php echo (($_POST['guests'] ?? 1) == $i) ? 'selected' : ''; ?>>
                                    <?php echo $i; ?> <?php echo $i === 1 ? 'Guest' : 'Guests'; ?>
                                </option>
                            <?php endfor; ?>
                        </select>
                    </div>

                    <!-- Price Summary -->
                    <div class="bg-gradient-to-r from-sage-50 to-pale-50 rounded-xl p-6">
                        <h3 class="font-semibold text-gray-800 mb-4">Price Summary</h3>
                        <div class="space-y-3">
                            <div class="flex justify-between items-center text-gray-600">
                                <span>₱<?php echo number_format($room['price'], 2); ?> × <span id="nightsDisplay">0</span> nights</span>
                                <span id="subtotalDisplay">₱0.00</span>
                            </div>
                            <div class="border-t border-gray-200 pt-3 flex justify-between items-center">
                                <span class="text-lg font-bold text-gray-800">Total Amount</span>
                                <span class="text-2xl font-bold bg-gradient-to-r from-sage-600 to-pale-600 bg-clip-text text-transparent" id="totalDisplay">₱0.00</span>
                            </div>
                        </div>
                    </div>

                    <button type="submit" 
                            class="w-full py-4 px-6 bg-gradient-to-r from-sage-500 to-pale-500 hover:from-sage-600 hover:to-pale-600 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl transition-all transform hover:scale-[1.02] flex items-center justify-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        Confirm Reservation
                    </button>
                </form>

                <p class="mt-4 text-center text-sm text-gray-500">
                    By booking, you agree to our 
                    <a href="#" class="text-sage-600 hover:underline">Terms of Service</a> and 
                    <a href="#" class="text-sage-600 hover:underline">Cancellation Policy</a>
                </p>
            </div>
        </div>
    </div>
</div>

<script>
// Price calculation
const roomPrice = <?php echo $room['price']; ?>;
const checkInInput = document.getElementById('check_in');
const checkOutInput = document.getElementById('check_out');
const nightsDisplay = document.getElementById('nightsDisplay');
const subtotalDisplay = document.getElementById('subtotalDisplay');
const totalDisplay = document.getElementById('totalDisplay');

function calculatePrice() {
    const checkIn = new Date(checkInInput.value);
    const checkOut = new Date(checkOutInput.value);
    
    if (checkIn && checkOut && checkOut > checkIn) {
        const nights = Math.ceil((checkOut - checkIn) / (1000 * 60 * 60 * 24));
        const total = nights * roomPrice;
        
        nightsDisplay.textContent = nights;
        subtotalDisplay.textContent = '₱' + total.toLocaleString('en-PH', {minimumFractionDigits: 2, maximumFractionDigits: 2});
        totalDisplay.textContent = '₱' + total.toLocaleString('en-PH', {minimumFractionDigits: 2, maximumFractionDigits: 2});
    } else {
        nightsDisplay.textContent = '0';
        subtotalDisplay.textContent = '₱0.00';
        totalDisplay.textContent = '₱0.00';
    }
}

checkInInput.addEventListener('change', function() {
    // Set min checkout date to day after check-in
    if (this.value) {
        const nextDay = new Date(this.value);
        nextDay.setDate(nextDay.getDate() + 1);
        checkOutInput.min = nextDay.toISOString().split('T')[0];
        
        // If checkout is before new min, reset it
        if (checkOutInput.value && new Date(checkOutInput.value) <= new Date(this.value)) {
            checkOutInput.value = '';
        }
    }
    calculatePrice();
});

checkOutInput.addEventListener('change', calculatePrice);

// Initial calculation if values exist
if (checkInInput.value && checkOutInput.value) {
    calculatePrice();
}
</script>

<?php include __DIR__ . '/../layouts/footer.php'; ?>
