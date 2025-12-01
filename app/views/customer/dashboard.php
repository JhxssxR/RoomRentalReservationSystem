<?php
$pageTitle = 'Dashboard - Room Rental';
include __DIR__ . '/../layouts/header.php';
?>

<div class="min-h-screen bg-gradient-to-br from-pale-50 to-sage-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Welcome Section -->
        <div class="mb-8">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                <div>
                    <h1 class="text-3xl font-bold bg-gradient-to-r from-sage-700 to-pale-600 bg-clip-text text-transparent">
                        Welcome back, <?php echo htmlspecialchars($customer['name'] ?? 'Guest'); ?>!
                    </h1>
                    <p class="text-gray-600 mt-1">Here's an overview of your reservations and account.</p>
                </div>
                <div class="flex items-center space-x-4 mt-4 md:mt-0">
                    <!-- Notification Bell -->
                    <div class="relative" id="notificationContainer">
                        <button onclick="toggleNotifications()" class="relative p-3 bg-white rounded-xl shadow-md hover:shadow-lg transition-all group">
                            <svg class="w-6 h-6 text-gray-600 group-hover:text-sage-600 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                            </svg>
                            <?php if (isset($unreadCount) && $unreadCount > 0): ?>
                                <span class="absolute -top-1 -right-1 w-5 h-5 bg-gradient-to-r from-red-500 to-pink-500 text-white text-xs rounded-full flex items-center justify-center font-bold animate-pulse">
                                    <?php echo $unreadCount > 9 ? '9+' : $unreadCount; ?>
                                </span>
                            <?php endif; ?>
                        </button>
                        
                        <!-- Notification Dropdown -->
                        <div id="notificationDropdown" class="hidden absolute right-0 mt-2 w-80 bg-white rounded-xl shadow-2xl border border-gray-100 z-50 overflow-hidden">
                            <div class="bg-gradient-to-r from-sage-500 to-pale-500 px-4 py-3">
                                <div class="flex justify-between items-center">
                                    <h3 class="text-white font-semibold">Notifications</h3>
                                    <?php if (isset($unreadCount) && $unreadCount > 0): ?>
                                        <span class="bg-white/20 text-white text-xs px-2 py-1 rounded-full"><?php echo $unreadCount; ?> new</span>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="max-h-80 overflow-y-auto">
                                <?php if (empty($notifications)): ?>
                                    <div class="px-4 py-8 text-center text-gray-500">
                                        <svg class="w-12 h-12 mx-auto mb-3 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                                        </svg>
                                        <p class="text-sm">No notifications</p>
                                    </div>
                                <?php else: ?>
                                    <?php foreach ($notifications as $notification): ?>
                                        <?php
                                        $typeColors = [
                                            'success' => 'bg-green-100 text-green-600 border-green-200',
                                            'warning' => 'bg-yellow-100 text-yellow-600 border-yellow-200',
                                            'error' => 'bg-red-100 text-red-600 border-red-200',
                                            'info' => 'bg-pale-100 text-pale-600 border-pale-200'
                                        ];
                                        $colorClass = $typeColors[$notification['type']] ?? $typeColors['info'];
                                        $isUnread = !($notification['is_read'] ?? false);
                                        ?>
                                        <a href="<?php echo htmlspecialchars($notification['link'] ?? '#'); ?>" 
                                           class="block px-4 py-3 hover:bg-gray-50 transition border-b border-gray-100 <?php echo $isUnread ? 'bg-sage-50/50' : ''; ?>">
                                            <div class="flex items-start space-x-3">
                                                <div class="flex-shrink-0 w-10 h-10 rounded-lg <?php echo $colorClass; ?> flex items-center justify-center border">
                                                    <?php if ($notification['type'] === 'success'): ?>
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                                    <?php elseif ($notification['type'] === 'warning'): ?>
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                                                    <?php elseif ($notification['type'] === 'error'): ?>
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                                    <?php else: ?>
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                                    <?php endif; ?>
                                                </div>
                                                <div class="flex-1 min-w-0">
                                                    <p class="text-sm font-medium text-gray-800 <?php echo $isUnread ? 'font-semibold' : ''; ?>"><?php echo htmlspecialchars($notification['title']); ?></p>
                                                    <p class="text-xs text-gray-500 mt-0.5 truncate"><?php echo htmlspecialchars($notification['message']); ?></p>
                                                    <p class="text-xs text-gray-400 mt-1"><?php echo isset($notification['created_at']) ? date('M j, g:i A', strtotime($notification['created_at'])) : 'Just now'; ?></p>
                                                </div>
                                                <?php if ($isUnread): ?>
                                                    <span class="w-2 h-2 bg-sage-500 rounded-full flex-shrink-0 mt-2"></span>
                                                <?php endif; ?>
                                            </div>
                                        </a>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </div>
                            <div class="bg-gray-50 px-4 py-2 border-t border-gray-100">
                                <button onclick="markAllRead()" class="text-sm text-sage-600 hover:text-sage-700 font-medium">Mark all as read</button>
                            </div>
                        </div>
                    </div>
                    
                    <a href="<?php echo BASE_URL; ?>/rooms" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-sage-500 to-pale-500 hover:from-sage-600 hover:to-pale-600 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl transition-all transform hover:scale-105">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        Book a Room
                    </a>
                </div>
            </div>
        </div>

        <!-- Quick Stats -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 mb-8">
            <div class="bg-sage-50 rounded-2xl shadow-lg p-6 border-l-4 border-sage-500 hover:shadow-xl transition-shadow">
                <div class="flex items-center">
                    <div class="w-14 h-14 bg-gradient-to-br from-sage-400 to-sage-600 rounded-xl flex items-center justify-center shadow-md">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-sage-600">Total Bookings</p>
                        <p class="text-3xl font-bold text-sage-800"><?php echo count($reservations ?? []); ?></p>
                    </div>
                </div>
            </div>

            <div class="bg-amber-50 rounded-2xl shadow-lg p-6 border-l-4 border-amber-500 hover:shadow-xl transition-shadow">
                <div class="flex items-center">
                    <div class="w-14 h-14 bg-gradient-to-br from-amber-400 to-amber-600 rounded-xl flex items-center justify-center shadow-md">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-amber-600">Pending</p>
                        <p class="text-3xl font-bold text-amber-800">
                            <?php echo count(array_filter($reservations ?? [], fn($r) => strtolower($r['status'] ?? '') === 'pending')); ?>
                        </p>
                    </div>
                </div>
            </div>

            <div class="bg-pale-50 rounded-2xl shadow-lg p-6 border-l-4 border-pale-500 hover:shadow-xl transition-shadow">
                <div class="flex items-center">
                    <div class="w-14 h-14 bg-gradient-to-br from-pale-400 to-pale-600 rounded-xl flex items-center justify-center shadow-md">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-pale-600">Completed</p>
                        <p class="text-3xl font-bold text-pale-800">
                            <?php echo count(array_filter($reservations ?? [], fn($r) => in_array(strtolower($r['status'] ?? ''), ['completed', 'confirmed', 'approved']))); ?>
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Recent Reservations -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
                    <div class="p-6 bg-gradient-to-r from-sage-500 to-pale-500">
                        <div class="flex justify-between items-center">
                            <h2 class="text-xl font-bold text-white">Recent Reservations</h2>
                            <a href="<?php echo BASE_URL; ?>/reservations" class="text-white/80 hover:text-white text-sm font-medium flex items-center">
                                View All
                                <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                            </a>
                        </div>
                    </div>
                    <div class="p-6">
                        <?php if (empty($reservations)): ?>
                            <div class="text-center py-12">
                                <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                    <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                </div>
                                <h3 class="text-lg font-medium text-gray-700 mb-2">No reservations yet</h3>
                                <p class="text-gray-500 mb-4">Start exploring our rooms and book your perfect stay!</p>
                                <a href="<?php echo BASE_URL; ?>/rooms" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-sage-500 to-sage-600 text-white font-semibold rounded-lg hover:from-sage-600 hover:to-sage-700 transition-all shadow-md">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                    </svg>
                                    Browse Rooms
                                </a>
                            </div>
                        <?php else: ?>
                            <div class="space-y-4">
                                <?php foreach (array_slice($reservations, 0, 5) as $reservation): ?>
                                    <div class="flex items-center p-4 bg-gradient-to-r from-gray-50 to-white rounded-xl border border-gray-100 hover:shadow-md transition-shadow">
                                        <img src="<?php echo htmlspecialchars($reservation['photo_url'] ?? 'https://images.unsplash.com/photo-1631049307264-da0ec9d70304?w=100'); ?>" 
                                             alt="Room"
                                             class="w-20 h-16 object-cover rounded-lg shadow-sm">
                                        <div class="ml-4 flex-1">
                                            <h3 class="font-semibold text-gray-800"><?php echo htmlspecialchars($reservation['name'] ?? $reservation['room_type']); ?></h3>
                                            <p class="text-sm text-gray-500 flex items-center">
                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                </svg>
                                                <?php echo date('M d', strtotime($reservation['check_in'])); ?> - 
                                                <?php echo date('M d, Y', strtotime($reservation['check_out'])); ?>
                                            </p>
                                        </div>
                                        <div class="text-right">
                                            <?php 
                                            $status = ucfirst(strtolower($reservation['status'] ?? 'pending'));
                                            // Map approved/confirmed to Completed for display
                                            if (in_array($status, ['Approved', 'Confirmed'])) {
                                                $status = 'Completed';
                                            }
                                            $statusColors = [
                                                'Pending' => 'bg-amber-100 text-amber-700',
                                                'Rejected' => 'bg-red-100 text-red-700',
                                                'Cancelled' => 'bg-red-100 text-red-700',
                                                'Completed' => 'bg-pale-100 text-pale-700',
                                            ];
                                            $statusClass = $statusColors[$status] ?? 'bg-gray-100 text-gray-700';
                                            ?>
                                            <span class="inline-block px-3 py-1.5 rounded-full text-xs font-semibold <?php echo $statusClass; ?>">
                                                <?php echo $status; ?>
                                            </span>
                                            <p class="text-lg font-bold text-gray-800 mt-1">
                                                ₱<?php echo number_format($reservation['total_price'], 2); ?>
                                            </p>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="space-y-6">
                <div class="bg-white rounded-2xl shadow-lg p-6">
                    <h2 class="text-lg font-bold text-gray-800 mb-4">Quick Actions</h2>
                    <div class="space-y-3">
                        <a href="<?php echo BASE_URL; ?>/rooms" class="flex items-center p-4 bg-gradient-to-r from-sage-50 to-sage-100 hover:from-sage-100 hover:to-sage-200 rounded-xl transition-all group">
                            <div class="w-10 h-10 bg-sage-500 rounded-lg flex items-center justify-center shadow-sm group-hover:shadow-md transition-shadow">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                            </div>
                            <span class="ml-3 font-medium text-gray-700">Browse Rooms</span>
                            <svg class="w-5 h-5 ml-auto text-gray-400 group-hover:text-sage-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </a>
                        <a href="<?php echo BASE_URL; ?>/reservations" class="flex items-center p-4 bg-gradient-to-r from-pale-50 to-pale-100 hover:from-pale-100 hover:to-pale-200 rounded-xl transition-all group">
                            <div class="w-10 h-10 bg-pale-500 rounded-lg flex items-center justify-center shadow-sm group-hover:shadow-md transition-shadow">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                </svg>
                            </div>
                            <span class="ml-3 font-medium text-gray-700">My Reservations</span>
                            <svg class="w-5 h-5 ml-auto text-gray-400 group-hover:text-pale-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </a>
                        <a href="<?php echo BASE_URL; ?>/payments" class="flex items-center p-4 bg-gradient-to-r from-amber-50 to-amber-100 hover:from-amber-100 hover:to-amber-200 rounded-xl transition-all group">
                            <div class="w-10 h-10 bg-amber-500 rounded-lg flex items-center justify-center shadow-sm group-hover:shadow-md transition-shadow">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                </svg>
                            </div>
                            <span class="ml-3 font-medium text-gray-700">Payment History</span>
                            <svg class="w-5 h-5 ml-auto text-gray-400 group-hover:text-amber-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Notification dropdown toggle
    function toggleNotifications() {
        const dropdown = document.getElementById('notificationDropdown');
        dropdown.classList.toggle('hidden');
    }

    // Close dropdown when clicking outside
    document.addEventListener('click', function(event) {
        const container = document.getElementById('notificationContainer');
        const dropdown = document.getElementById('notificationDropdown');
        if (container && !container.contains(event.target)) {
            dropdown.classList.add('hidden');
        }
    });

    // Mark all notifications as read
    function markAllRead() {
        const badge = document.querySelector('#notificationContainer .animate-pulse');
        if (badge) {
            badge.remove();
        }
        const unreadDots = document.querySelectorAll('#notificationDropdown .bg-sage-500.rounded-full');
        unreadDots.forEach(dot => dot.remove());
        
        const unreadBgs = document.querySelectorAll('#notificationDropdown .bg-sage-50\\/50');
        unreadBgs.forEach(bg => bg.classList.remove('bg-sage-50/50'));
    }
</script>

<?php include __DIR__ . '/../layouts/footer.php'; ?>
