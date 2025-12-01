<?php
$pageTitle = 'Register - Room Rental';
include __DIR__ . '/../layouts/header.php';
?>

<div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8 bg-gradient-to-br from-sage-600 via-sage-700 to-pale-600">
    <div class="max-w-lg w-full">
        <div class="bg-gradient-to-br from-sage-50 to-pale-50 rounded-xl shadow-xl p-6 border border-sage-200 backdrop-blur-sm">
            <!-- Header -->
            <div class="text-center mb-5">
                <div class="mx-auto w-12 h-12 bg-gradient-to-br from-sage-400 to-pale-500 rounded-lg flex items-center justify-center mb-3 shadow-md">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                    </svg>
                </div>
                <h2 class="text-xl font-bold bg-gradient-to-r from-sage-700 to-pale-600 bg-clip-text text-transparent">Create Account</h2>
                <p class="text-gray-500 text-sm mt-1">Join us and start booking rooms</p>
            </div>

            <!-- Error Messages -->
            <?php if (!empty($errors)): ?>
                <div class="mb-4 p-3 bg-red-50 border border-red-200 rounded-lg">
                    <div class="flex items-start">
                        <svg class="w-5 h-5 text-red-500 mr-2 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <ul class="text-red-700 text-sm space-y-1">
                            <?php foreach ($errors as $error): ?>
                                <li><?php echo htmlspecialchars($error); ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Success Message -->
            <?php if (!empty($_SESSION['success'])): ?>
                <div class="mb-4 p-3 bg-emerald-50 border border-emerald-200 rounded-lg">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-emerald-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span class="text-emerald-700 text-sm"><?php echo htmlspecialchars($_SESSION['success']); unset($_SESSION['success']); ?></span>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Registration Form -->
            <form method="POST" action="<?php echo BASE_URL; ?>/register" id="registerForm">
                <div class="space-y-3">
                    <!-- Full Name -->
                    <div>
                        <label for="name" class="block text-xs font-medium text-gray-700 mb-1">Full Name</label>
                        <div class="relative">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                            </span>
                            <input type="text" id="name" name="name" required
                                   value="<?php echo htmlspecialchars($_POST['name'] ?? ''); ?>"
                                   class="w-full pl-10 pr-4 py-2.5 bg-sage-50/50 border border-sage-200 rounded-lg focus:border-sage-500 focus:ring-1 focus:ring-sage-200 transition-all text-sm"
                                   placeholder="Enter your full name">
                        </div>
                    </div>

                    <!-- Email -->
                    <div>
                        <label for="email" class="block text-xs font-medium text-gray-700 mb-1">Email Address</label>
                        <div class="relative">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                </svg>
                            </span>
                            <input type="email" id="email" name="email" required
                                   value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>"
                                   class="w-full pl-10 pr-4 py-2.5 bg-sage-50/50 border border-sage-200 rounded-lg focus:border-sage-500 focus:ring-1 focus:ring-sage-200 transition-all text-sm"
                                   placeholder="Enter your email">
                        </div>
                    </div>

                    <!-- Phone -->
                    <div>
                        <label for="phone" class="block text-xs font-medium text-gray-700 mb-1">Phone Number</label>
                        <div class="relative">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                </svg>
                            </span>
                            <input type="tel" id="phone" name="phone"
                                   value="<?php echo htmlspecialchars($_POST['phone'] ?? ''); ?>"
                                   class="w-full pl-10 pr-4 py-2.5 bg-sage-50/50 border border-sage-200 rounded-lg focus:border-sage-500 focus:ring-1 focus:ring-sage-200 transition-all text-sm"
                                   placeholder="Enter your phone number">
                        </div>
                    </div>

                    <!-- Address -->
                    <div>
                        <label for="address" class="block text-xs font-medium text-gray-700 mb-1">Address <span class="text-gray-400">(Optional)</span></label>
                        <div class="relative">
                            <span class="absolute left-4 top-3 text-gray-400">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                            </span>
                            <textarea id="address" name="address" rows="2"
                                   class="w-full pl-10 pr-4 py-2.5 bg-sage-50/50 border border-sage-200 rounded-lg focus:border-sage-500 focus:ring-1 focus:ring-sage-200 transition-all resize-none text-sm"
                                   placeholder="Enter your address"><?php echo htmlspecialchars($_POST['address'] ?? ''); ?></textarea>
                        </div>
                    </div>

                    <!-- Password -->
                    <div>
                        <label for="password" class="block text-xs font-medium text-gray-700 mb-1">Password</label>
                        <div class="relative">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                </svg>
                            </span>
                            <input type="password" id="password" name="password" required minlength="6"
                                   class="w-full pl-10 pr-10 py-2.5 bg-sage-50/50 border border-sage-200 rounded-lg focus:border-sage-500 focus:ring-1 focus:ring-sage-200 transition-all text-sm"
                                   placeholder="Create a password">
                            <button type="button" onclick="togglePassword('password')" 
                                    class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-sage-600 transition-colors">
                                <svg class="w-5 h-5 eye-open" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                                <svg class="w-5 h-5 eye-closed hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"></path>
                                </svg>
                            </button>
                        </div>
                        <!-- Password Strength Indicator -->
                        <div class="mt-1">
                            <div class="flex gap-1">
                                <div class="h-0.5 flex-1 rounded-full bg-gray-200" id="strength1"></div>
                                <div class="h-0.5 flex-1 rounded-full bg-gray-200" id="strength2"></div>
                                <div class="h-0.5 flex-1 rounded-full bg-gray-200" id="strength3"></div>
                                <div class="h-0.5 flex-1 rounded-full bg-gray-200" id="strength4"></div>
                            </div>
                            <p class="text-xs text-gray-500 mt-1" id="strengthText">Must be at least 6 characters</p>
                        </div>
                    </div>

                    <!-- Confirm Password -->
                    <div>
                        <label for="confirm_password" class="block text-xs font-medium text-gray-700 mb-1">Confirm Password</label>
                        <div class="relative">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                                </svg>
                            </span>
                            <input type="password" id="confirm_password" name="confirm_password" required
                                   class="w-full pl-10 pr-4 py-2.5 bg-sage-50/50 border border-sage-200 rounded-lg focus:border-sage-500 focus:ring-1 focus:ring-sage-200 transition-all text-sm"
                                   placeholder="Confirm your password">
                        </div>
                        <p class="text-xs text-red-500 mt-1 hidden" id="passwordMatchError">Passwords do not match</p>
                    </div>

                    <!-- Terms -->
                    <div class="flex items-start">
                        <input type="checkbox" id="terms" name="terms" required
                               class="mt-0.5 w-4 h-4 text-sage-600 border border-gray-300 rounded focus:ring-sage-500 cursor-pointer">
                        <label for="terms" class="ml-2 text-xs text-gray-600">
                            I agree to the 
                            <button type="button" onclick="openTermsModal()" class="text-sage-600 hover:text-sage-700 font-medium underline">Terms of Service</button> 
                            and 
                            <button type="button" onclick="openPrivacyModal()" class="text-sage-600 hover:text-sage-700 font-medium underline">Privacy Policy</button>
                        </label>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" class="w-full py-3 px-4 bg-gradient-to-r from-sage-500 to-pale-500 hover:from-sage-600 hover:to-pale-600 text-white font-semibold rounded-lg shadow-md hover:shadow-lg transition-all transform hover:scale-[1.02] flex items-center justify-center text-sm">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                        </svg>
                        Create Account
                    </button>
                </div>
            </form>

            <!-- Login Link -->
            <div class="mt-4 text-center">
                <p class="text-gray-600 text-sm">
                    Already have an account? 
                    <a href="<?php echo BASE_URL; ?>/login" class="text-sage-600 hover:text-sage-700 font-medium">Sign in</a>
                </p>
            </div>
        </div>
    </div>
</div>

<!-- Terms of Service Modal -->
<div id="termsModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 hidden items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-2xl max-w-2xl w-full max-h-[80vh] overflow-hidden">
        <div class="p-6 bg-gradient-to-r from-sage-500 to-pale-500 text-white">
            <div class="flex justify-between items-center">
                <h3 class="text-xl font-bold">Terms of Service</h3>
                <button onclick="closeTermsModal()" class="text-white/80 hover:text-white">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
        </div>
        <div class="p-6 overflow-y-auto max-h-[60vh] prose prose-sm">
            <p class="text-gray-500 text-sm mb-4">Last updated: November 30, 2025</p>
            
            <h4 class="font-semibold text-gray-800 mt-4">1. Acceptance of Terms</h4>
            <p class="text-gray-600 text-sm">By accessing and using our Room Rental Reservation System, you accept and agree to be bound by the terms and provision of this agreement.</p>
            
            <h4 class="font-semibold text-gray-800 mt-4">2. Reservation Policy</h4>
            <p class="text-gray-600 text-sm">All reservations are subject to availability. A valid payment method is required to confirm your reservation. Reservation requests are subject to approval by our management team.</p>
            
            <h4 class="font-semibold text-gray-800 mt-4">3. Cancellation Policy</h4>
            <p class="text-gray-600 text-sm">Free cancellation is available up to 24 hours before the check-in date. Cancellations made within 24 hours of check-in may incur a cancellation fee equal to one night's stay.</p>
            
            <h4 class="font-semibold text-gray-800 mt-4">4. Check-in/Check-out</h4>
            <p class="text-gray-600 text-sm">Standard check-in time is 2:00 PM and check-out time is 12:00 PM. Early check-in or late check-out may be arranged subject to availability and additional charges.</p>
            
            <h4 class="font-semibold text-gray-800 mt-4">5. Guest Responsibilities</h4>
            <p class="text-gray-600 text-sm">Guests are responsible for any damage to the room or property during their stay. Smoking is prohibited in all rooms. Quiet hours are from 10:00 PM to 7:00 AM.</p>
            
            <h4 class="font-semibold text-gray-800 mt-4">6. Payment Terms</h4>
            <p class="text-gray-600 text-sm">Payment must be made in full upon confirmation of reservation or at check-in. We accept major credit cards, debit cards, and cash payments.</p>
            
            <h4 class="font-semibold text-gray-800 mt-4">7. Limitation of Liability</h4>
            <p class="text-gray-600 text-sm">We are not liable for any loss, theft, or damage to personal belongings during your stay. We recommend using the in-room safe for valuables.</p>
        </div>
        <div class="p-4 border-t border-gray-100 bg-gray-50">
            <button onclick="closeTermsModal()" class="w-full py-3 bg-sage-500 hover:bg-sage-600 text-white font-medium rounded-xl transition-colors">
                I Understand
            </button>
        </div>
    </div>
</div>

<!-- Privacy Policy Modal -->
<div id="privacyModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 hidden items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-2xl max-w-2xl w-full max-h-[80vh] overflow-hidden">
        <div class="p-6 bg-gradient-to-r from-pale-500 to-sage-500 text-white">
            <div class="flex justify-between items-center">
                <h3 class="text-xl font-bold">Privacy Policy</h3>
                <button onclick="closePrivacyModal()" class="text-white/80 hover:text-white">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
        </div>
        <div class="p-6 overflow-y-auto max-h-[60vh] prose prose-sm">
            <p class="text-gray-500 text-sm mb-4">Last updated: November 30, 2025</p>
            
            <h4 class="font-semibold text-gray-800 mt-4">1. Information We Collect</h4>
            <p class="text-gray-600 text-sm">We collect personal information you provide when registering, including your name, email address, phone number, and address. We also collect reservation details and payment information.</p>
            
            <h4 class="font-semibold text-gray-800 mt-4">2. How We Use Your Information</h4>
            <p class="text-gray-600 text-sm">Your information is used to process reservations, communicate with you about your bookings, improve our services, and send promotional offers (with your consent).</p>
            
            <h4 class="font-semibold text-gray-800 mt-4">3. Information Security</h4>
            <p class="text-gray-600 text-sm">We implement appropriate security measures to protect your personal information. Passwords are encrypted and stored securely. We use secure connections for all data transmission.</p>
            
            <h4 class="font-semibold text-gray-800 mt-4">4. Data Retention</h4>
            <p class="text-gray-600 text-sm">We retain your personal information for as long as your account is active or as needed to provide services. You may request deletion of your account at any time.</p>
            
            <h4 class="font-semibold text-gray-800 mt-4">5. Third-Party Sharing</h4>
            <p class="text-gray-600 text-sm">We do not sell or rent your personal information to third parties. We may share information with service providers who assist in our operations.</p>
            
            <h4 class="font-semibold text-gray-800 mt-4">6. Your Rights</h4>
            <p class="text-gray-600 text-sm">You have the right to access, correct, or delete your personal information. Contact us to exercise these rights or if you have any privacy-related questions.</p>
            
            <h4 class="font-semibold text-gray-800 mt-4">7. Contact Us</h4>
            <p class="text-gray-600 text-sm">For privacy concerns, please contact us at privacy@roomrental.com or call our support line.</p>
        </div>
        <div class="p-4 border-t border-gray-100 bg-gray-50">
            <button onclick="closePrivacyModal()" class="w-full py-3 bg-pale-500 hover:bg-pale-600 text-white font-medium rounded-xl transition-colors">
                I Understand
            </button>
        </div>
    </div>
</div>

<script>
// Toggle password visibility
function togglePassword(fieldId) {
    const field = document.getElementById(fieldId);
    const button = field.nextElementSibling;
    const eyeOpen = button.querySelector('.eye-open');
    const eyeClosed = button.querySelector('.eye-closed');
    
    if (field.type === 'password') {
        field.type = 'text';
        eyeOpen.classList.add('hidden');
        eyeClosed.classList.remove('hidden');
    } else {
        field.type = 'password';
        eyeOpen.classList.remove('hidden');
        eyeClosed.classList.add('hidden');
    }
}

// Password strength checker
document.getElementById('password').addEventListener('input', function() {
    const password = this.value;
    let strength = 0;
    
    if (password.length >= 6) strength++;
    if (password.length >= 8) strength++;
    if (/[A-Z]/.test(password) && /[a-z]/.test(password)) strength++;
    if (/[0-9]/.test(password) || /[^A-Za-z0-9]/.test(password)) strength++;
    
    const colors = ['bg-red-500', 'bg-orange-500', 'bg-yellow-500', 'bg-emerald-500'];
    const texts = ['Weak', 'Fair', 'Good', 'Strong'];
    
    for (let i = 1; i <= 4; i++) {
        const bar = document.getElementById('strength' + i);
        bar.className = 'h-1 flex-1 rounded-full transition-colors ' + 
            (i <= strength ? colors[strength - 1] : 'bg-gray-200');
    }
    
    const strengthText = document.getElementById('strengthText');
    if (password.length === 0) {
        strengthText.textContent = 'Must be at least 6 characters';
        strengthText.className = 'text-xs text-gray-500 mt-1';
    } else {
        strengthText.textContent = 'Password strength: ' + (texts[strength - 1] || 'Too weak');
        strengthText.className = 'text-xs mt-1 ' + (strength >= 3 ? 'text-emerald-600' : strength >= 2 ? 'text-yellow-600' : 'text-red-600');
    }
});

// Password match checker
document.getElementById('confirm_password').addEventListener('input', function() {
    const password = document.getElementById('password').value;
    const confirm = this.value;
    const error = document.getElementById('passwordMatchError');
    
    if (confirm.length > 0 && password !== confirm) {
        error.classList.remove('hidden');
        this.classList.add('border-red-300');
    } else {
        error.classList.add('hidden');
        this.classList.remove('border-red-300');
    }
});

// Modal functions
function openTermsModal() {
    document.getElementById('termsModal').classList.remove('hidden');
    document.getElementById('termsModal').classList.add('flex');
    document.body.style.overflow = 'hidden';
}

function closeTermsModal() {
    document.getElementById('termsModal').classList.add('hidden');
    document.getElementById('termsModal').classList.remove('flex');
    document.body.style.overflow = '';
}

function openPrivacyModal() {
    document.getElementById('privacyModal').classList.remove('hidden');
    document.getElementById('privacyModal').classList.add('flex');
    document.body.style.overflow = 'hidden';
}

function closePrivacyModal() {
    document.getElementById('privacyModal').classList.add('hidden');
    document.getElementById('privacyModal').classList.remove('flex');
    document.body.style.overflow = '';
}

// Close modal on backdrop click
document.getElementById('termsModal').addEventListener('click', function(e) {
    if (e.target === this) closeTermsModal();
});
document.getElementById('privacyModal').addEventListener('click', function(e) {
    if (e.target === this) closePrivacyModal();
});

// Close modal on Escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeTermsModal();
        closePrivacyModal();
    }
});
</script>

<?php include __DIR__ . '/../layouts/footer.php'; ?>
