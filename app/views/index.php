<?php
$pageTitle = 'Room Rental - Find your perfect stay';
include __DIR__ . '/layouts/header.php';
?>

<!-- Hero Section with Search Box -->
<section class="min-h-[650px] flex flex-col justify-center relative pb-32" style="background-image: url('https://images.unsplash.com/photo-1611892440504-42a792e24d32?w=1600&q=80'); background-size: cover; background-position: center;">
    <!-- Dark overlay for better text readability -->
    <div class="absolute inset-0 bg-gradient-to-b from-black/60 via-black/50 to-black/70"></div>
    
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 text-center relative z-10">
        <h1 class="text-4xl md:text-6xl font-bold text-white mb-6" style="text-shadow: 2px 2px 8px rgba(0,0,0,0.5);">
            Find Your Perfect Stay
        </h1>
        <p class="text-xl text-white mb-8 max-w-2xl mx-auto leading-relaxed" style="text-shadow: 1px 1px 4px rgba(0,0,0,0.7);">
            Discover comfortable rooms at affordable prices. Book your next stay with us and enjoy excellent amenities and service.
        </p>
        <div class="flex flex-col sm:flex-row justify-center gap-4">
            <a href="<?php echo BASE_URL; ?>/rooms" class="bg-gradient-to-r from-sage-500 to-sage-600 hover:from-sage-600 hover:to-sage-700 text-white text-lg px-8 py-4 rounded-lg font-semibold transition transform hover:scale-105 shadow-lg">
                Browse Rooms
            </a>
            <a href="<?php echo BASE_URL; ?>/register" class="bg-white text-pale-700 hover:bg-pale-50 px-8 py-4 rounded-lg font-semibold text-lg transition transform hover:scale-105 shadow-lg border border-pale-200">
                Get Started
            </a>
        </div>
    </div>
    
    <!-- Search Box (inside hero) -->
    <div class="absolute bottom-0 left-0 right-0 transform translate-y-1/2 z-20 px-4 sm:px-6 lg:px-8">
        <div class="max-w-5xl mx-auto bg-white rounded-2xl shadow-2xl p-6 md:p-8 border border-gray-100">
            <form action="<?php echo BASE_URL; ?>/rooms" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4" id="homeSearchForm">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Check-in Date</label>
                    <input type="date" name="check_in" id="home_check_in" class="w-full px-4 py-3 border-2 border-sage-200 rounded-lg focus:border-sage-500 focus:ring-2 focus:ring-sage-200 transition" min="<?php echo date('Y-m-d'); ?>">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Check-out Date</label>
                    <input type="date" name="check_out" id="home_check_out" class="w-full px-4 py-3 border-2 border-sage-200 rounded-lg focus:border-sage-500 focus:ring-2 focus:ring-sage-200 transition">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Guests</label>
                    <select name="capacity" class="w-full px-4 py-3 border-2 border-sage-200 rounded-lg focus:border-sage-500 focus:ring-2 focus:ring-sage-200 transition">
                        <option value="1">1 Guest</option>
                        <option value="2">2 Guests</option>
                        <option value="3">3 Guests</option>
                        <option value="4">4 Guests</option>
                        <option value="5">5 Guests</option>
                        <option value="6">6+ Guests</option>
                    </select>
                </div>
                <div class="flex items-end">
                    <button type="submit" class="w-full bg-gradient-to-r from-sage-600 to-pale-600 hover:from-sage-700 hover:to-pale-700 text-white py-3 px-6 rounded-lg font-semibold transition transform hover:scale-105 shadow-md flex items-center justify-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                        Search Rooms
                    </button>
                </div>
            </form>
        </div>
    </div>
</section>

<!-- Features Section -->
<section class="pt-28 pb-20 bg-gradient-to-b from-slate-200 to-slate-300">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2 class="text-3xl md:text-4xl font-bold text-gray-800 mb-4">Why Choose Us</h2>
            <p class="text-gray-600 max-w-2xl mx-auto">We provide the best accommodation experience with top-notch amenities and exceptional service.</p>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="text-center p-8 bg-white rounded-2xl shadow-lg border border-sage-100 hover:shadow-xl transition transform hover:-translate-y-1">
                <div class="w-20 h-20 bg-gradient-to-br from-sage-400 to-sage-600 rounded-full flex items-center justify-center mx-auto mb-6 shadow-lg">
                    <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-gray-800 mb-3">Best Prices</h3>
                <p class="text-gray-600">Get the best rates guaranteed. We offer competitive prices without compromising quality.</p>
            </div>
            <div class="text-center p-8 bg-white rounded-2xl shadow-lg border border-pale-100 hover:shadow-xl transition transform hover:-translate-y-1">
                <div class="w-20 h-20 bg-gradient-to-br from-pale-400 to-pale-600 rounded-full flex items-center justify-center mx-auto mb-6 shadow-lg">
                    <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-gray-800 mb-3">Secure Booking</h3>
                <p class="text-gray-600">Your data is safe with us. We use industry-standard security for all transactions.</p>
            </div>
            <div class="text-center p-8 bg-white rounded-2xl shadow-lg border border-green-100 hover:shadow-xl transition transform hover:-translate-y-1">
                <div class="w-20 h-20 bg-gradient-to-br from-green-400 to-green-600 rounded-full flex items-center justify-center mx-auto mb-6 shadow-lg">
                    <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-gray-800 mb-3">24/7 Support</h3>
                <p class="text-gray-600">Our support team is available around the clock to assist you with any questions.</p>
            </div>
        </div>
    </div>
</section>

<!-- Room Types Section -->
<section class="py-20 bg-gradient-to-b from-sage-200 to-sage-300">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2 class="text-3xl md:text-4xl font-bold text-gray-800 mb-4">Our Room Types</h2>
            <p class="text-gray-600 max-w-2xl mx-auto">Choose from our variety of room types to suit your needs and budget.</p>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- Standard Room -->
            <div class="bg-white rounded-2xl overflow-hidden shadow-lg hover:shadow-2xl transition-all transform hover:-translate-y-2 group">
                <div class="relative h-52 overflow-hidden">
                    <img src="https://images.unsplash.com/photo-1631049307264-da0ec9d70304?w=400" alt="Standard Room" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/50 to-transparent"></div>
                    <span class="absolute top-4 right-4 bg-sage-500 text-white px-4 py-1.5 rounded-full text-sm font-semibold shadow-lg">From ₱1,500</span>
                </div>
                <div class="p-6">
                    <h3 class="text-xl font-bold text-gray-800 mb-2">Standard Room</h3>
                    <p class="text-gray-600 text-sm mb-4">Comfortable and affordable. Perfect for solo travelers.</p>
                    <a href="<?php echo BASE_URL; ?>/rooms?type=Standard" class="inline-flex items-center text-sage-600 font-semibold hover:text-sage-700 transition">
                        View Details 
                        <svg class="w-5 h-5 ml-1 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </a>
                </div>
            </div>
            <!-- Deluxe Room -->
            <div class="bg-white rounded-2xl overflow-hidden shadow-lg hover:shadow-2xl transition-all transform hover:-translate-y-2 group">
                <div class="relative h-52 overflow-hidden">
                    <img src="https://images.unsplash.com/photo-1590490360182-c33d57733427?w=400" alt="Deluxe Room" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/50 to-transparent"></div>
                    <span class="absolute top-4 right-4 bg-sage-500 text-white px-4 py-1.5 rounded-full text-sm font-semibold shadow-lg">From ₱2,500</span>
                </div>
                <div class="p-6">
                    <h3 class="text-xl font-bold text-gray-800 mb-2">Deluxe Room</h3>
                    <p class="text-gray-600 text-sm mb-4">Extra space and premium amenities for added comfort.</p>
                    <a href="<?php echo BASE_URL; ?>/rooms?type=Deluxe" class="inline-flex items-center text-sage-600 font-semibold hover:text-sage-700 transition">
                        View Details 
                        <svg class="w-5 h-5 ml-1 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </a>
                </div>
            </div>
            <!-- Suite -->
            <div class="bg-white rounded-2xl overflow-hidden shadow-lg hover:shadow-2xl transition-all transform hover:-translate-y-2 group">
                <div class="relative h-52 overflow-hidden">
                    <img src="https://images.unsplash.com/photo-1582719478250-c89cae4dc85b?w=400" alt="Suite" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/50 to-transparent"></div>
                    <span class="absolute top-4 right-4 bg-sage-500 text-white px-4 py-1.5 rounded-full text-sm font-semibold shadow-lg">From ₱4,500</span>
                </div>
                <div class="p-6">
                    <h3 class="text-xl font-bold text-gray-800 mb-2">Suite</h3>
                    <p class="text-gray-600 text-sm mb-4">Luxurious suite with separate living area.</p>
                    <a href="<?php echo BASE_URL; ?>/rooms?type=Suite" class="inline-flex items-center text-sage-600 font-semibold hover:text-sage-700 transition">
                        View Details 
                        <svg class="w-5 h-5 ml-1 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </a>
                </div>
            </div>
            <!-- Family Room -->
            <div class="bg-white rounded-2xl overflow-hidden shadow-lg hover:shadow-2xl transition-all transform hover:-translate-y-2 group">
                <div class="relative h-52 overflow-hidden">
                    <img src="https://images.unsplash.com/photo-1596394516093-501ba68a0ba6?w=400" alt="Family Room" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/50 to-transparent"></div>
                    <span class="absolute top-4 right-4 bg-sage-500 text-white px-4 py-1.5 rounded-full text-sm font-semibold shadow-lg">From ₱3,500</span>
                </div>
                <div class="p-6">
                    <h3 class="text-xl font-bold text-gray-800 mb-2">Family Room</h3>
                    <p class="text-gray-600 text-sm mb-4">Spacious room perfect for families with children.</p>
                    <a href="<?php echo BASE_URL; ?>/rooms?type=Family" class="inline-flex items-center text-sage-600 font-semibold hover:text-sage-700 transition">
                        View Details 
                        <svg class="w-5 h-5 ml-1 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </a>
                </div>
            </div>
        </div>
        <!-- View All Button -->
        <div class="text-center mt-12">
            <a href="<?php echo BASE_URL; ?>/rooms" class="inline-flex items-center bg-gradient-to-r from-sage-500 to-pale-500 hover:from-sage-600 hover:to-pale-600 text-white px-8 py-4 rounded-lg font-semibold text-lg transition transform hover:scale-105 shadow-lg">
                View All Rooms
                <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                </svg>
            </a>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="py-24 bg-gradient-to-r from-sage-600 via-pale-600 to-sage-600 relative overflow-hidden">
    <div class="absolute inset-0 opacity-10">
        <div class="absolute top-0 left-0 w-72 h-72 bg-white rounded-full -translate-x-1/2 -translate-y-1/2"></div>
        <div class="absolute bottom-0 right-0 w-96 h-96 bg-white rounded-full translate-x-1/2 translate-y-1/2"></div>
    </div>
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center relative z-10">
        <h2 class="text-3xl md:text-5xl font-bold text-white mb-6 drop-shadow-lg">Ready to Book Your Stay?</h2>
        <p class="text-white/80 text-xl mb-10">Join thousands of satisfied guests who have experienced our exceptional service.</p>
        <div class="flex flex-col sm:flex-row justify-center gap-4">
            <a href="<?php echo BASE_URL; ?>/register" class="inline-block bg-white text-sage-700 hover:bg-pale-50 px-10 py-4 rounded-lg font-bold text-lg transition transform hover:scale-105 shadow-xl">
                Create an Account
            </a>
            <a href="<?php echo BASE_URL; ?>/rooms" class="inline-block bg-sage-700/50 backdrop-blur border-2 border-white text-white hover:bg-white hover:text-sage-700 px-10 py-4 rounded-lg font-bold text-lg transition transform hover:scale-105">
                Browse Rooms
            </a>
        </div>
    </div>
</section>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const checkIn = document.getElementById('home_check_in');
    const checkOut = document.getElementById('home_check_out');
    
    checkIn.addEventListener('change', function() {
        if (this.value) {
            const nextDay = new Date(this.value);
            nextDay.setDate(nextDay.getDate() + 1);
            checkOut.min = nextDay.toISOString().split('T')[0];
            if (checkOut.value && checkOut.value <= this.value) {
                checkOut.value = nextDay.toISOString().split('T')[0];
            }
        }
    });
});
</script>

<?php include __DIR__ . '/layouts/footer.php'; ?>
