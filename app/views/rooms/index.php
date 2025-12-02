<?php
$pageTitle = 'Available Rooms';
include __DIR__ . '/../layouts/header.php';
?>

<!-- Hero Banner -->
<section class="bg-gradient-to-r from-sage-600 via-sage-500 to-sage-600 py-10 relative overflow-hidden">
    <div class="absolute inset-0 opacity-10">
        <div class="absolute top-0 left-0 w-48 h-48 bg-white rounded-full -translate-x-1/2 -translate-y-1/2"></div>
        <div class="absolute bottom-0 right-0 w-64 h-64 bg-white rounded-full translate-x-1/2 translate-y-1/2"></div>
    </div>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center relative z-10">
        <h1 class="text-3xl md:text-4xl font-bold text-white mb-2 drop-shadow-lg">Available Rooms</h1>
        <p class="text-sage-100 text-base max-w-2xl mx-auto">Find and book the perfect room for your stay.</p>
    </div>
</section>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <!-- Filters -->
    <div class="bg-white rounded-2xl shadow-lg p-6 mb-10 border border-sage-100 -mt-10 relative z-20">
        <div class="flex items-center mb-4">
            <div class="w-10 h-10 bg-gradient-to-br from-sage-400 to-sage-600 rounded-lg flex items-center justify-center mr-3 shadow">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
                </svg>
            </div>
            <h2 class="text-lg font-semibold text-gray-800">Filter Rooms</h2>
        </div>
        <form method="GET" action="<?php echo BASE_URL; ?>/rooms" id="filterForm" class="grid grid-cols-1 md:grid-cols-5 gap-4">
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Room Type</label>
                <select name="type" onchange="this.form.submit()" class="w-full px-4 py-3 border-2 border-sage-200 rounded-xl focus:border-sage-500 transition bg-white cursor-pointer">
                    <option value="">All Types</option>
                    <?php if (!empty($roomTypes)): foreach ($roomTypes as $type): ?>
                        <option value="<?php echo htmlspecialchars($type); ?>" <?php echo (isset($_GET['type']) && $_GET['type'] === $type) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($type); ?>
                        </option>
                    <?php endforeach; endif; ?>
                </select>
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Check-in</label>
                <input type="date" name="check_in" onchange="this.form.submit()" class="w-full px-4 py-3 border-2 border-sage-200 rounded-xl focus:border-sage-500 transition cursor-pointer" value="<?php echo htmlspecialchars($_GET['check_in'] ?? ''); ?>" min="<?php echo date('Y-m-d'); ?>">
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Check-out</label>
                <input type="date" name="check_out" onchange="this.form.submit()" class="w-full px-4 py-3 border-2 border-sage-200 rounded-xl focus:border-sage-500 transition cursor-pointer" value="<?php echo htmlspecialchars($_GET['check_out'] ?? ''); ?>">
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Guests</label>
                <select name="capacity" onchange="this.form.submit()" class="w-full px-4 py-3 border-2 border-sage-200 rounded-xl focus:border-sage-500 transition bg-white cursor-pointer">
                    <option value="">Any</option>
                    <?php for ($i = 1; $i <= 6; $i++): ?>
                        <option value="<?php echo $i; ?>" <?php echo (isset($_GET['capacity']) && $_GET['capacity'] == $i) ? 'selected' : ''; ?>><?php echo $i; ?> Guest<?php echo $i > 1 ? 's' : ''; ?></option>
                    <?php endfor; ?>
                </select>
            </div>
            <div class="flex items-end gap-2">
                <?php if (!empty($_GET['type']) || !empty($_GET['check_in']) || !empty($_GET['check_out']) || !empty($_GET['capacity'])): ?>
                <a href="<?php echo BASE_URL; ?>/rooms" class="flex-1 py-3 px-4 border-2 border-gray-300 text-gray-600 rounded-xl hover:bg-gray-100 transition flex items-center justify-center" title="Clear Filters">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                    Clear
                </a>
                <?php else: ?>
                <div class="flex-1 py-3 px-4 bg-sage-50 text-sage-600 rounded-xl flex items-center justify-center text-sm">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Select filters
                </div>
                <?php endif; ?>
            </div>
        </form>
    </div>

    <!-- Results Count & Active Filters -->
    <div class="flex flex-wrap items-center justify-between gap-4 mb-6">
        <p class="text-gray-600">
            <span class="font-semibold text-sage-600"><?php echo count($rooms); ?></span> room<?php echo count($rooms) != 1 ? 's' : ''; ?> found
        </p>
        <?php if (!empty($_GET['type']) || !empty($_GET['check_in']) || !empty($_GET['check_out']) || !empty($_GET['capacity'])): ?>
        <div class="flex flex-wrap gap-2">
            <?php if (!empty($_GET['type'])): ?>
            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm bg-sage-100 text-sage-700">
                <span class="font-medium">Type:</span>&nbsp;<?php echo htmlspecialchars($_GET['type']); ?>
            </span>
            <?php endif; ?>
            <?php if (!empty($_GET['check_in']) && !empty($_GET['check_out'])): ?>
            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm bg-sage-100 text-sage-700">
                <span class="font-medium">Dates:</span>&nbsp;<?php echo date('M j', strtotime($_GET['check_in'])); ?> - <?php echo date('M j', strtotime($_GET['check_out'])); ?>
            </span>
            <?php endif; ?>
            <?php if (!empty($_GET['capacity'])): ?>
            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm bg-sage-100 text-sage-700">
                <span class="font-medium">Guests:</span>&nbsp;<?php echo htmlspecialchars($_GET['capacity']); ?>+
            </span>
            <?php endif; ?>
        </div>
        <?php endif; ?>
    </div>

    <!-- Rooms Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        <?php foreach ($rooms as $room): ?>
            <div class="bg-white rounded-2xl overflow-hidden shadow-lg hover:shadow-2xl transition-all transform hover:-translate-y-2 border border-sage-100 group">
                <!-- Room Image -->
                <div class="relative h-56 overflow-hidden">
                    <?php if (!empty($room['photo_url'])): ?>
                        <img src="<?php echo htmlspecialchars($room['photo_url']); ?>" alt="<?php echo htmlspecialchars($room['room_type']); ?>" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                    <?php else: ?>
                        <div class="w-full h-full bg-gradient-to-br from-sage-400 to-sage-600 flex items-center justify-center">
                            <svg class="w-20 h-20 text-white/30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                            </svg>
                        </div>
                    <?php endif; ?>
                    <div class="absolute inset-0 bg-gradient-to-t from-black/50 to-transparent"></div>
                    <span class="absolute top-4 right-4 bg-sage-500 text-white px-4 py-1.5 rounded-full text-sm font-semibold shadow-lg">
                        <?php echo htmlspecialchars($room['room_type']); ?>
                    </span>
                    <div class="absolute bottom-4 left-4">
                        <p class="text-white text-2xl font-bold drop-shadow-lg">₱<?php echo number_format($room['price'], 2); ?></p>
                        <p class="text-white/80 text-sm">per night</p>
                    </div>
                </div>
                
                <!-- Room Details -->
                <div class="p-6">
                    <div class="flex justify-between items-start mb-4">
                        <div>
                            <h3 class="text-xl font-bold text-gray-800"><?php echo htmlspecialchars($room['name'] ?? 'Room ' . $room['room_number']); ?></h3>
                            <p class="text-sage-600 font-medium"><?php echo htmlspecialchars($room['room_type']); ?> • Room <?php echo htmlspecialchars($room['room_number']); ?></p>
                        </div>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-700 border border-green-200">
                            <span class="w-2 h-2 rounded-full mr-1.5 bg-green-500"></span>
                            <?php echo ucfirst($room['status']); ?>
                        </span>
                    </div>
                    
                    <!-- Room Info -->
                    <div class="flex items-center gap-4 text-sm text-gray-600 mb-5 pb-5 border-b border-gray-100">
                        <span class="flex items-center bg-sage-50 px-3 py-1.5 rounded-lg">
                            <svg class="w-4 h-4 mr-1.5 text-sage-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                            <span class="font-medium"><?php echo $room['capacity']; ?> Guest<?php echo $room['capacity'] > 1 ? 's' : ''; ?></span>
                        </span>
                    </div>
                    
                    <!-- Book Button -->
                    <?php if (class_exists('Auth') && Auth::isLoggedIn()): ?>
                        <a href="<?php echo BASE_URL; ?>/rooms/<?php echo $room['room_id']; ?>/book" class="block w-full bg-gradient-to-r from-sage-500 to-sage-600 hover:from-sage-600 hover:to-sage-700 text-white py-3 px-6 rounded-xl font-semibold text-center transition shadow-lg">
                            Book Now
                        </a>
                    <?php else: ?>
                        <a href="<?php echo BASE_URL; ?>/login?redirect=rooms" class="block w-full border-2 border-sage-500 text-sage-600 hover:bg-sage-500 hover:text-white py-3 px-6 rounded-xl font-semibold text-center transition">
                            Login to Book
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<?php include __DIR__ . '/../layouts/footer.php'; ?>
