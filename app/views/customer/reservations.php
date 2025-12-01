<?php
$pageTitle = 'My Reservations - Room Rental';
include __DIR__ . '/../layouts/header.php';
?>

<div class="min-h-screen bg-gradient-to-br from-pale-50 to-sage-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Page Header -->
        <div class="mb-8 flex flex-col md:flex-row md:items-center md:justify-between">
            <div>
                <h1 class="text-3xl font-bold bg-gradient-to-r from-sage-700 to-pale-600 bg-clip-text text-transparent">My Reservations</h1>
                <p class="text-gray-600 mt-1">View and manage all your room reservations</p>
            </div>
            <a href="<?php echo BASE_URL; ?>/rooms" class="mt-4 md:mt-0 inline-flex items-center px-6 py-3 bg-gradient-to-r from-sage-500 to-pale-500 hover:from-sage-600 hover:to-pale-600 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl transition-all">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                Book Another Room
            </a>
        </div>

        <!-- Filter Tabs -->
        <div class="bg-white rounded-2xl shadow-lg mb-8 p-2">
            <nav class="flex flex-wrap gap-2">
                <a href="?status=all" class="flex-1 py-3 px-4 rounded-xl text-center font-medium text-sm transition-all <?php echo (!isset($_GET['status']) || $_GET['status'] === 'all') ? 'bg-gradient-to-r from-sage-500 to-pale-500 text-white shadow-md' : 'text-gray-600 hover:bg-gray-100'; ?>">
                    All
                </a>
                <a href="?status=Pending" class="flex-1 py-3 px-4 rounded-xl text-center font-medium text-sm transition-all <?php echo (isset($_GET['status']) && $_GET['status'] === 'Pending') ? 'bg-gradient-to-r from-amber-400 to-amber-500 text-white shadow-md' : 'text-gray-600 hover:bg-gray-100'; ?>">
                    Pending
                </a>
                <a href="?status=Completed" class="flex-1 py-3 px-4 rounded-xl text-center font-medium text-sm transition-all <?php echo (isset($_GET['status']) && in_array($_GET['status'], ['Completed', 'Approved'])) ? 'bg-gradient-to-r from-emerald-400 to-emerald-500 text-white shadow-md' : 'text-gray-600 hover:bg-gray-100'; ?>">
                    Completed
                </a>
                <a href="?status=Rejected" class="flex-1 py-3 px-4 rounded-xl text-center font-medium text-sm transition-all <?php echo (isset($_GET['status']) && $_GET['status'] === 'Rejected') ? 'bg-gradient-to-r from-red-400 to-red-500 text-white shadow-md' : 'text-gray-600 hover:bg-gray-100'; ?>">
                    Rejected
                </a>
            </nav>
        </div>

        <!-- Reservations List -->
        <?php if (empty($reservations)): ?>
            <div class="bg-white rounded-2xl shadow-xl p-12 text-center">
                <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-6">
                    <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-gray-800 mb-2">No reservations found</h3>
                <p class="text-gray-500 mb-6">You haven't made any reservations yet. Start exploring our rooms!</p>
                <a href="<?php echo BASE_URL; ?>/rooms" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-sage-500 to-sage-600 text-white font-semibold rounded-xl hover:from-sage-600 hover:to-sage-700 transition-all shadow-lg">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                    Browse Rooms
                </a>
            </div>
        <?php else: ?>
            <div class="space-y-6">
                <?php 
                $statusFilter = $_GET['status'] ?? 'all';
                foreach ($reservations as $reservation): 
                    // For "Completed" filter, show both Approved and Completed reservations
                    if ($statusFilter === 'Completed') {
                        if (!in_array($reservation['status'], ['Completed', 'Approved', 'Confirmed'])) continue;
                    } elseif ($statusFilter !== 'all' && $reservation['status'] !== $statusFilter) {
                        continue;
                    }
                ?>
                    <div class="bg-white rounded-2xl shadow-lg overflow-hidden hover:shadow-xl transition-shadow">
                        <div class="md:flex">
                            <!-- Room Image -->
                            <div class="md:w-64 h-48 md:h-auto relative">
                                <img src="<?php echo htmlspecialchars($reservation['photo_url'] ?? 'https://images.unsplash.com/photo-1631049307264-da0ec9d70304?w=400'); ?>" 
                                     alt="<?php echo htmlspecialchars($reservation['room_type']); ?>"
                                     class="w-full h-full object-cover">
                                <div class="absolute inset-0 bg-gradient-to-r from-black/30 to-transparent md:hidden"></div>
                            </div>
                            
                            <!-- Reservation Details -->
                            <div class="flex-1 p-6">
                                <div class="flex flex-wrap justify-between items-start mb-4">
                                    <div>
                                        <h3 class="text-xl font-bold text-gray-800">
                                            <?php echo htmlspecialchars($reservation['name'] ?? $reservation['room_type']); ?>
                                        </h3>
                                        <p class="text-gray-500 flex items-center mt-1">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                            </svg>
                                            Room <?php echo htmlspecialchars($reservation['room_number']); ?> • <?php echo htmlspecialchars($reservation['room_type']); ?>
                                        </p>
                                    </div>
                                    <?php 
                                    $displayStatus = in_array($reservation['status'], ['Approved', 'Confirmed']) ? 'Completed' : $reservation['status'];
                                    ?>
                                    <span class="inline-flex items-center px-4 py-1.5 rounded-full text-sm font-semibold
                                        <?php echo match($displayStatus) {
                                            'Pending' => 'bg-amber-100 text-amber-700',
                                            'Completed' => 'bg-emerald-100 text-emerald-700',
                                            'Rejected' => 'bg-red-100 text-red-700',
                                            default => 'bg-gray-100 text-gray-700'
                                        }; ?>">
                                        <span class="w-2 h-2 rounded-full mr-2 
                                            <?php echo match($displayStatus) {
                                                'Pending' => 'bg-amber-500',
                                                'Completed' => 'bg-emerald-500',
                                                'Rejected' => 'bg-red-500',
                                                default => 'bg-gray-500'
                                            }; ?>"></span>
                                        <?php echo $displayStatus; ?>
                                    </span>
                                </div>
                                
                                <div class="grid grid-cols-2 md:grid-cols-3 gap-4 mb-5 pb-5 border-b border-gray-100">
                                    <div class="bg-gray-50 rounded-xl p-3">
                                        <p class="text-xs text-gray-500 uppercase font-medium mb-1">Check-in</p>
                                        <p class="font-bold text-gray-800 flex items-center">
                                            <svg class="w-4 h-4 mr-1.5 text-sage-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                            </svg>
                                            <?php echo date('M d, Y', strtotime($reservation['check_in'])); ?>
                                        </p>
                                    </div>
                                    <div class="bg-gray-50 rounded-xl p-3">
                                        <p class="text-xs text-gray-500 uppercase font-medium mb-1">Check-out</p>
                                        <p class="font-bold text-gray-800 flex items-center">
                                            <svg class="w-4 h-4 mr-1.5 text-pale-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                            </svg>
                                            <?php echo date('M d, Y', strtotime($reservation['check_out'])); ?>
                                        </p>
                                    </div>
                                    <div class="bg-gradient-to-r from-sage-50 to-pale-50 rounded-xl p-3">
                                        <p class="text-xs text-gray-500 uppercase font-medium mb-1">Total Price</p>
                                        <p class="font-bold text-xl bg-gradient-to-r from-sage-600 to-pale-600 bg-clip-text text-transparent">
                                            ₱<?php echo number_format($reservation['total_price'], 2); ?>
                                        </p>
                                    </div>
                                </div>
                                
                                <!-- Actions -->
                                <div class="flex flex-wrap gap-3">
                                    <a href="<?php echo BASE_URL; ?>/reservations/<?php echo $reservation['reservation_id']; ?>" 
                                       class="inline-flex items-center px-5 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium rounded-xl transition-all">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                        </svg>
                                        View Details
                                    </a>
                                    <?php if ($reservation['status'] === 'Pending'): ?>
                                        <a href="<?php echo BASE_URL; ?>/reservations/<?php echo $reservation['reservation_id']; ?>/cancel" 
                                           class="inline-flex items-center px-5 py-2.5 border-2 border-red-300 text-red-600 hover:bg-red-50 font-medium rounded-xl transition-all"
                                           onclick="return confirm('Are you sure you want to cancel this reservation?')">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                            </svg>
                                            Cancel
                                        </a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php include __DIR__ . '/../layouts/footer.php'; ?>
