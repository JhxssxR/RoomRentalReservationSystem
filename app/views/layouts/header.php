<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $pageTitle ?? 'Room Rental Reservation'; ?></title>
    
    <!-- Favicon -->
    <link rel="icon" type="image/svg+xml" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><rect width='100' height='100' rx='20' fill='%23f0f7fa'/><path d='M50 25L20 50v25a5 5 0 005 5h15v-15a5 5 0 015-5h10a5 5 0 015 5v15h15a5 5 0 005-5V50L50 25z' fill='%235a7858'/></svg>">
    <link rel="apple-touch-icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><rect width='100' height='100' rx='20' fill='%23f0f7fa'/><path d='M50 25L20 50v25a5 5 0 005 5h15v-15a5 5 0 015-5h10a5 5 0 015 5v15h15a5 5 0 005-5V50L50 25z' fill='%235a7858'/></svg>">
    
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        sage: {
                            50: '#f6f7f6',
                            100: '#e3e7e3',
                            200: '#c7d2c7',
                            300: '#a3b5a3',
                            400: '#7a9478',
                            500: '#5a7858',
                            600: '#476046',
                            700: '#3a4d3a',
                            800: '#313f31',
                            900: '#2a352a',
                        },
                        pale: {
                            50: '#f0f7fa',
                            100: '#e0eff5',
                            200: '#b9dcea',
                            300: '#8ac5dc',
                            400: '#5aaac8',
                            500: '#3d8fb0',
                            600: '#327394',
                            700: '#2c5d78',
                            800: '#294e64',
                            900: '#264254',
                        }
                    }
                }
            }
        }
    </script>
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        body { 
            font-family: 'Poppins', sans-serif; 
        }
        .nav-gradient {
            background: linear-gradient(135deg, #5a7858 0%, #3d8fb0 100%);
        }
    </style>
</head>
<body class="bg-gradient-to-br from-pale-50 to-sage-50 min-h-screen">
    <!-- Navigation -->
    <nav class="nav-gradient shadow-md">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <!-- Logo -->
                <div class="flex items-center">
                    <a href="<?php echo BASE_URL; ?>/" class="flex items-center group">
                        <div class="w-9 h-9 bg-white/20 backdrop-blur rounded-lg flex items-center justify-center group-hover:bg-white/30 transition">
                            <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"/>
                            </svg>
                        </div>
                        <span class="ml-3 text-lg font-semibold text-white tracking-wide">RoomRental</span>
                    </a>
                </div>

                <!-- Desktop Navigation -->
                <div class="hidden md:flex items-center space-x-1">
                    <a href="<?php echo BASE_URL; ?>/" class="flex items-center text-white/90 hover:text-white hover:bg-white/10 px-4 py-2 rounded-lg text-sm font-medium transition-all">
                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                        </svg>
                        Home
                    </a>
                    <a href="<?php echo BASE_URL; ?>/rooms" class="flex items-center text-white/90 hover:text-white hover:bg-white/10 px-4 py-2 rounded-lg text-sm font-medium transition-all">
                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                        </svg>
                        Rooms
                    </a>
                    
                    <?php if (Auth::isLoggedIn()): ?>
                        <a href="<?php echo BASE_URL; ?>/dashboard" class="flex items-center text-white/90 hover:text-white hover:bg-white/10 px-4 py-2 rounded-lg text-sm font-medium transition-all">
                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path>
                            </svg>
                            Dashboard
                        </a>
                        <a href="<?php echo BASE_URL; ?>/reservations" class="flex items-center text-white/90 hover:text-white hover:bg-white/10 px-4 py-2 rounded-lg text-sm font-medium transition-all">
                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            My Bookings
                        </a>
                        
                        <div class="flex items-center ml-3 pl-3 border-l border-white/20">
                            <span class="text-white/80 text-sm mr-3">Hi, <?php echo htmlspecialchars($_SESSION['user_name'] ?? 'Guest'); ?></span>
                            <a href="<?php echo BASE_URL; ?>/logout" class="flex items-center bg-white/20 backdrop-blur hover:bg-white/30 text-white px-4 py-2 rounded-lg text-sm font-medium transition-all">
                                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                                </svg>
                                Logout
                            </a>
                        </div>
                    <?php else: ?>
                        <a href="<?php echo BASE_URL; ?>/login" class="flex items-center text-white/90 hover:text-white hover:bg-white/10 px-4 py-2 rounded-lg text-sm font-medium transition-all">
                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path>
                            </svg>
                            Login
                        </a>
                        <a href="<?php echo BASE_URL; ?>/register" class="ml-2 flex items-center bg-white text-sage-700 hover:bg-pale-100 px-5 py-2 rounded-lg text-sm font-semibold transition-all shadow-sm">
                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                            </svg>
                            Register
                        </a>
                    <?php endif; ?>
                </div>

                <!-- Mobile menu button -->
                <div class="md:hidden flex items-center">
                    <button id="mobile-menu-button" class="text-white/90 hover:text-white hover:bg-white/10 p-2 rounded-lg transition-all">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile Navigation -->
        <div id="mobile-menu" class="hidden md:hidden border-t border-white/10">
            <div class="px-3 py-3 space-y-1">
                <a href="<?php echo BASE_URL; ?>/" class="block text-white/90 hover:text-white hover:bg-white/10 px-4 py-2.5 rounded-lg text-sm font-medium transition-all">Home</a>
                <a href="<?php echo BASE_URL; ?>/rooms" class="block text-white/90 hover:text-white hover:bg-white/10 px-4 py-2.5 rounded-lg text-sm font-medium transition-all">Rooms</a>
                
                <?php if (Auth::isLoggedIn()): ?>
                    <a href="<?php echo BASE_URL; ?>/dashboard" class="block text-white/90 hover:text-white hover:bg-white/10 px-4 py-2.5 rounded-lg text-sm font-medium transition-all">Dashboard</a>
                    <a href="<?php echo BASE_URL; ?>/reservations" class="block text-white/90 hover:text-white hover:bg-white/10 px-4 py-2.5 rounded-lg text-sm font-medium transition-all">My Bookings</a>
                    <div class="pt-2 mt-2 border-t border-white/10">
                        <a href="<?php echo BASE_URL; ?>/logout" class="block text-white/90 hover:text-white hover:bg-white/10 px-4 py-2.5 rounded-lg text-sm font-medium transition-all">Logout</a>
                    </div>
                <?php else: ?>
                    <div class="pt-2 mt-2 border-t border-white/10 space-y-1">
                        <a href="<?php echo BASE_URL; ?>/login" class="block text-white/90 hover:text-white hover:bg-white/10 px-4 py-2.5 rounded-lg text-sm font-medium transition-all">Login</a>
                        <a href="<?php echo BASE_URL; ?>/register" class="block bg-white/20 text-white hover:bg-white/30 px-4 py-2.5 rounded-lg text-sm font-medium transition-all">Register</a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </nav>

    <!-- Flash Messages -->
    <?php if (isset($_SESSION['success'])): ?>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4">
            <div class="bg-sage-100 border border-sage-300 text-sage-800 px-4 py-3 rounded-lg flex justify-between items-center shadow-sm">
                <div class="flex items-center">
                    <svg class="w-5 h-5 mr-2 text-sage-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    <span><?php echo htmlspecialchars($_SESSION['success']); ?></span>
                </div>
                <button onclick="this.parentElement.remove()" class="text-sage-600 hover:text-sage-800 transition">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                    </svg>
                </button>
            </div>
        </div>
        <?php unset($_SESSION['success']); ?>
    <?php endif; ?>

    <?php if (isset($_SESSION['error'])): ?>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4">
            <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg flex justify-between items-center shadow-sm">
                <div class="flex items-center">
                    <svg class="w-5 h-5 mr-2 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                    </svg>
                    <span><?php echo htmlspecialchars($_SESSION['error']); ?></span>
                </div>
                <button onclick="this.parentElement.remove()" class="text-red-600 hover:text-red-800 transition">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                    </svg>
                </button>
            </div>
        </div>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>

    <?php if (isset($_SESSION['info'])): ?>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4">
            <div class="bg-pale-100 border border-pale-300 text-pale-800 px-4 py-3 rounded-lg flex justify-between items-center shadow-sm">
                <div class="flex items-center">
                    <svg class="w-5 h-5 mr-2 text-pale-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                    </svg>
                    <span><?php echo htmlspecialchars($_SESSION['info']); ?></span>
                </div>
                <button onclick="this.parentElement.remove()" class="text-pale-600 hover:text-pale-800 transition">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                    </svg>
                </button>
            </div>
        </div>
        <?php unset($_SESSION['info']); ?>
    <?php endif; ?>

    <!-- Main Content -->
    <main>
