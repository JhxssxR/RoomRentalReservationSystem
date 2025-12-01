<!-- Admin Sidebar -->
<aside class="w-64 min-h-screen fixed left-0 top-0 bg-gradient-to-b from-sage-800 to-sage-900 shadow-2xl">
    <!-- Logo/Brand -->
    <div class="p-6 border-b border-sage-700">
        <a href="<?php echo BASE_URL; ?>/admin/dashboard" class="flex items-center">
            <div class="w-10 h-10 bg-gradient-to-br from-pale-400 to-pale-600 rounded-lg flex items-center justify-center">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                </svg>
            </div>
            <div class="ml-3">
                <span class="text-white font-bold text-lg">Room Rental</span>
                <span class="block text-sage-300 text-xs">Admin Panel</span>
            </div>
        </a>
    </div>

    <!-- Admin Profile -->
    <div class="p-4">
        <div class="p-4 bg-sage-700/50 rounded-xl backdrop-blur">
            <div class="flex items-center">
                <div class="w-10 h-10 bg-gradient-to-br from-pale-400 to-sage-400 rounded-full flex items-center justify-center ring-2 ring-pale-300">
                    <span class="text-white font-semibold"><?php echo strtoupper(substr($_SESSION['user_name'] ?? 'A', 0, 1)); ?></span>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-semibold text-white"><?php echo htmlspecialchars($_SESSION['user_name'] ?? 'Admin'); ?></p>
                    <p class="text-xs text-sage-300">Administrator</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Navigation -->
    <nav class="px-4 space-y-1">
        <p class="text-xs font-semibold text-sage-400 uppercase tracking-wider px-4 mb-2">Main Menu</p>
        
        <a href="<?php echo BASE_URL; ?>/admin/dashboard" 
           class="flex items-center px-4 py-3 rounded-xl transition-all duration-200 <?php echo (strpos($_SERVER['REQUEST_URI'], '/admin/dashboard') !== false || $_SERVER['REQUEST_URI'] === '/admin' || $_SERVER['REQUEST_URI'] === '/RoomRentalReservation/public/admin') ? 'bg-gradient-to-r from-pale-500 to-sage-500 text-white shadow-lg' : 'text-sage-200 hover:bg-sage-700/50 hover:text-white'; ?>">
            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
            </svg>
            Dashboard
        </a>

        <a href="<?php echo BASE_URL; ?>/admin/rooms" 
           class="flex items-center px-4 py-3 rounded-xl transition-all duration-200 <?php echo strpos($_SERVER['REQUEST_URI'], '/admin/rooms') !== false ? 'bg-gradient-to-r from-pale-500 to-sage-500 text-white shadow-lg' : 'text-sage-200 hover:bg-sage-700/50 hover:text-white'; ?>">
            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
            </svg>
            Rooms
        </a>

        <a href="<?php echo BASE_URL; ?>/admin/customers" 
           class="flex items-center px-4 py-3 rounded-xl transition-all duration-200 <?php echo strpos($_SERVER['REQUEST_URI'], '/admin/customers') !== false ? 'bg-gradient-to-r from-pale-500 to-sage-500 text-white shadow-lg' : 'text-sage-200 hover:bg-sage-700/50 hover:text-white'; ?>">
            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
            </svg>
            Customers
        </a>

        <a href="<?php echo BASE_URL; ?>/admin/payments" 
           class="flex items-center px-4 py-3 rounded-xl transition-all duration-200 <?php echo strpos($_SERVER['REQUEST_URI'], '/admin/payments') !== false ? 'bg-gradient-to-r from-pale-500 to-sage-500 text-white shadow-lg' : 'text-sage-200 hover:bg-sage-700/50 hover:text-white'; ?>">
            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
            </svg>
            Payments
        </a>

        <a href="<?php echo BASE_URL; ?>/admin/reports" 
           class="flex items-center px-4 py-3 rounded-xl transition-all duration-200 <?php echo strpos($_SERVER['REQUEST_URI'], '/admin/reports') !== false ? 'bg-gradient-to-r from-pale-500 to-sage-500 text-white shadow-lg' : 'text-sage-200 hover:bg-sage-700/50 hover:text-white'; ?>">
            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
            </svg>
            Reports
        </a>

        <div class="pt-4 mt-4 border-t border-sage-700">
            <p class="text-xs font-semibold text-sage-400 uppercase tracking-wider px-4 mb-2">Other</p>
            
            <a href="<?php echo BASE_URL; ?>/" target="_blank"
               class="flex items-center px-4 py-3 rounded-xl text-sage-200 hover:bg-sage-700/50 hover:text-white transition-all duration-200">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                </svg>
                View Website
            </a>

            <a href="<?php echo BASE_URL; ?>/logout" 
               class="flex items-center px-4 py-3 rounded-xl text-red-300 hover:bg-red-500/20 hover:text-red-200 transition-all duration-200">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                </svg>
                Logout
            </a>
        </div>
    </nav>
</aside>
