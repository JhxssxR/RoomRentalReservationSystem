<?php
$pageTitle = 'Admin Dashboard - Room Rental';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $pageTitle; ?></title>
    
    <!-- Favicon -->
    <link rel="icon" type="image/svg+xml" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><rect width='100' height='100' rx='20' fill='%23f0f7fa'/><path d='M50 25L20 50v25a5 5 0 005 5h15v-15a5 5 0 015-5h10a5 5 0 015 5v15h15a5 5 0 005-5V50L50 25z' fill='%235a7858'/></svg>">
    <link rel="apple-touch-icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><rect width='100' height='100' rx='20' fill='%23f0f7fa'/><path d='M50 25L20 50v25a5 5 0 005 5h15v-15a5 5 0 015-5h10a5 5 0 015 5v15h15a5 5 0 005-5V50L50 25z' fill='%235a7858'/></svg>">
    
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        sage: {
                            50: '#f4f7f4', 100: '#e4ebe4', 200: '#c9d9c9', 300: '#a3bfa3',
                            400: '#7a9f7a', 500: '#4a7c59', 600: '#3d6549', 700: '#33523d',
                            800: '#2b4333', 900: '#24382b',
                        },
                        pale: {
                            50: '#f0f7ff', 100: '#e0efff', 200: '#baddff', 300: '#7cc2ff',
                            400: '#36a3ff', 500: '#0c87eb', 600: '#006bc9', 700: '#0055a3',
                            800: '#054986', 900: '#0a3d6f',
                        }
                    }
                }
            }
        }
    </script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>body { font-family: 'Inter', sans-serif; }</style>
</head>
<body class="bg-gradient-to-br from-sage-600 via-sage-700 to-pale-600 min-h-screen">
    <?php include __DIR__ . '/../layouts/sidebar.php'; ?>

    <div class="ml-64 p-8">
        <div class="flex justify-between items-center mb-8">
            <div>
                <h1 class="text-3xl font-bold text-white">Dashboard</h1>
                <p class="text-sage-200">Welcome back, <?php echo htmlspecialchars($_SESSION['user_name'] ?? 'Admin'); ?>!</p>
            </div>
            <div class="flex items-center space-x-4">
                <!-- Notification Bell -->
                <div class="relative" id="notificationContainer">
                    <button onclick="toggleNotifications()" class="relative p-3 bg-sage-50 rounded-xl shadow-md hover:shadow-lg transition-all group border border-sage-100">
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
                    <div id="notificationDropdown" class="hidden absolute right-0 mt-2 w-80 bg-gradient-to-br from-sage-50 to-pale-50 rounded-xl shadow-2xl border border-sage-100 z-50 overflow-hidden">
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
                        <div class="bg-sage-100 px-4 py-2 border-t border-sage-200">
                            <button onclick="markAllRead()" class="text-sm text-sage-600 hover:text-sage-700 font-medium">Mark all as read</button>
                        </div>
                    </div>
                </div>
                
                <div class="text-sm text-sage-700 bg-sage-50 px-4 py-2 rounded-lg shadow-sm border border-sage-100">
                    <?php echo date('l, F j, Y'); ?>
                </div>
            </div>
        </div>

        <?php if (isset($_SESSION['success'])): ?>
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded-lg mb-6"><?php echo $_SESSION['success']; unset($_SESSION['success']); ?></div>
        <?php endif; ?>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded-lg mb-6"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></div>
        <?php endif; ?>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <div class="bg-sage-50 rounded-xl shadow-lg p-6 border-l-4 border-sage-500 hover:shadow-xl transition">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-sage-600 uppercase tracking-wide font-medium">Total Rooms</p>
                        <p class="text-3xl font-bold text-sage-800 mt-1"><?php echo $stats['total_rooms'] ?? 0; ?></p>
                    </div>
                    <div class="w-14 h-14 bg-gradient-to-br from-sage-400 to-sage-600 rounded-xl flex items-center justify-center shadow-lg">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                    </div>
                </div>
                <a href="<?php echo BASE_URL; ?>/admin/rooms" class="text-sage-600 text-sm mt-4 inline-block hover:underline font-medium">Manage rooms →</a>
            </div>

            <div class="bg-pale-50 rounded-xl shadow-lg p-6 border-l-4 border-pale-500 hover:shadow-xl transition">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-pale-600 uppercase tracking-wide font-medium">Total Customers</p>
                        <p class="text-3xl font-bold text-pale-800 mt-1"><?php echo $stats['total_customers'] ?? 0; ?></p>
                    </div>
                    <div class="w-14 h-14 bg-gradient-to-br from-pale-400 to-pale-600 rounded-xl flex items-center justify-center shadow-lg">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                    </div>
                </div>
                <a href="<?php echo BASE_URL; ?>/admin/customers" class="text-pale-600 text-sm mt-4 inline-block hover:underline font-medium">View customers →</a>
            </div>

            <div class="bg-green-50 rounded-xl shadow-lg p-6 border-l-4 border-green-500 hover:shadow-xl transition">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-green-600 uppercase tracking-wide font-medium">Total Revenue</p>
                        <p class="text-3xl font-bold text-green-800 mt-1">₱<?php echo number_format($stats['total_revenue'] ?? 0, 0); ?></p>
                    </div>
                    <div class="w-14 h-14 bg-gradient-to-br from-green-400 to-green-600 rounded-xl flex items-center justify-center shadow-lg">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                </div>
                <a href="<?php echo BASE_URL; ?>/admin/reports" class="text-green-600 text-sm mt-4 inline-block hover:underline font-medium">View reports →</a>
            </div>

            <div class="bg-amber-50 rounded-xl shadow-lg p-6 border-l-4 border-amber-500 hover:shadow-xl transition">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-amber-600 uppercase tracking-wide font-medium">Pending Payments</p>
                        <p class="text-3xl font-bold text-amber-800 mt-1"><?php echo $stats['pending_payments'] ?? 0; ?></p>
                    </div>
                    <div class="w-14 h-14 bg-gradient-to-br from-amber-400 to-amber-600 rounded-xl flex items-center justify-center shadow-lg">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                </div>
                <a href="<?php echo BASE_URL; ?>/admin/payments" class="text-amber-600 text-sm mt-4 inline-block hover:underline font-medium">Review payments →</a>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="bg-gradient-to-r from-sage-50 to-pale-50 rounded-xl shadow-lg p-6 mb-8">
            <h2 class="text-lg font-semibold text-gray-800 mb-4">Quick Actions</h2>
            <div class="flex flex-wrap gap-4">
                <a href="<?php echo BASE_URL; ?>/admin/rooms" class="flex items-center px-4 py-3 bg-gradient-to-r from-sage-500 to-sage-600 text-white rounded-lg hover:from-sage-600 hover:to-sage-700 transition shadow-md">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    Add New Room
                </a>
                <a href="<?php echo BASE_URL; ?>/admin/payments" class="flex items-center px-4 py-3 bg-gradient-to-r from-pale-500 to-pale-600 text-white rounded-lg hover:from-pale-600 hover:to-pale-700 transition shadow-md">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    Review Payments
                </a>
                <a href="<?php echo BASE_URL; ?>/admin/reports" class="flex items-center px-4 py-3 bg-gradient-to-r from-purple-500 to-purple-600 text-white rounded-lg hover:from-purple-600 hover:to-purple-700 transition shadow-md">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    Generate Report
                </a>
            </div>
        </div>

        <!-- Recent Reservations -->
        <div class="bg-gradient-to-br from-sage-50 to-pale-50 rounded-xl shadow-lg overflow-hidden">
            <div class="p-6 border-b border-sage-200 flex justify-between items-center">
                <h2 class="text-xl font-semibold text-gray-800">Recent Reservations</h2>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gradient-to-r from-sage-100 to-pale-100">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Customer</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Room</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Check-in</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Check-out</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Amount</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-sage-100">
                        <?php if (empty($recentReservations)): ?>
                            <tr><td colspan="6" class="px-6 py-12 text-center text-gray-500">No reservations found</td></tr>
                        <?php else: ?>
                            <?php foreach ($recentReservations as $reservation): ?>
                                <tr class="hover:bg-sage-100/50 transition">
                                    <td class="px-6 py-4">
                                        <div class="flex items-center">
                                            <div class="w-8 h-8 bg-gradient-to-br from-sage-400 to-sage-600 rounded-full flex items-center justify-center text-white text-sm font-medium">
                                                <?php echo strtoupper(substr($reservation['customer_name'] ?? 'U', 0, 1)); ?>
                                            </div>
                                            <span class="ml-3 text-sm text-gray-800"><?php echo htmlspecialchars($reservation['customer_name'] ?? 'Unknown'); ?></span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-800"><?php echo htmlspecialchars(($reservation['room_number'] ?? '') . ' - ' . ($reservation['room_name'] ?? $reservation['room_type'] ?? '')); ?></td>
                                    <td class="px-6 py-4 text-sm text-gray-600"><?php echo date('M d, Y', strtotime($reservation['check_in'] ?? $reservation['check_in_date'] ?? 'now')); ?></td>
                                    <td class="px-6 py-4 text-sm text-gray-600"><?php echo date('M d, Y', strtotime($reservation['check_out'] ?? $reservation['check_out_date'] ?? 'now')); ?></td>
                                    <td class="px-6 py-4">
                                        <?php 
                                        $status = strtolower($reservation['status'] ?? 'pending');
                                        $statusClass = match($status) {
                                            'pending', 'pending_payment' => 'bg-yellow-100 text-yellow-800',
                                            'confirmed', 'approved' => 'bg-green-100 text-green-800',
                                            'cancelled', 'rejected' => 'bg-red-100 text-red-800',
                                            default => 'bg-gray-100 text-gray-800'
                                        };
                                        ?>
                                        <span class="px-3 py-1 text-xs font-semibold rounded-full <?php echo $statusClass; ?>"><?php echo ucfirst($reservation['status'] ?? 'Pending'); ?></span>
                                    </td>
                                    <td class="px-6 py-4 text-sm font-semibold text-gray-800">₱<?php echo number_format($reservation['total_price'] ?? 0, 2); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
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
            // In a real app, this would make an AJAX call
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
</body>
</html>
