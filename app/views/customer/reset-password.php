<?php
$pageTitle = 'Reset Password - Room Rental';
include __DIR__ . '/../layouts/header.php';
?>

<div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8 bg-gradient-to-br from-pale-50 to-sage-50">
    <div class="max-w-md w-full">
        <div class="bg-white rounded-2xl shadow-2xl p-8 border border-sage-100">
            <!-- Header -->
            <div class="text-center mb-8">
                <div class="mx-auto w-16 h-16 bg-gradient-to-br from-emerald-400 to-emerald-600 rounded-2xl flex items-center justify-center mb-4 shadow-lg">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                    </svg>
                </div>
                <h2 class="text-2xl font-bold bg-gradient-to-r from-sage-700 to-pale-600 bg-clip-text text-transparent">Reset Password</h2>
                <p class="text-gray-500 mt-2">Enter your new password below</p>
            </div>

            <!-- Error Messages -->
            <?php if (!empty($errors)): ?>
                <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-xl">
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

            <!-- Reset Password Form -->
            <form method="POST" action="<?php echo BASE_URL; ?>/reset-password?token=<?php echo htmlspecialchars($token); ?>" id="resetForm">
                <div class="space-y-5">
                    <!-- New Password -->
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-2">New Password</label>
                        <div class="relative">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                </svg>
                            </span>
                            <input type="password" id="password" name="password" required minlength="6"
                                   class="w-full pl-12 pr-12 py-3 border-2 border-gray-200 rounded-xl focus:border-sage-500 focus:ring-2 focus:ring-sage-200 transition-all"
                                   placeholder="Enter new password">
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
                        <div class="mt-2">
                            <div class="flex gap-1">
                                <div class="h-1 flex-1 rounded-full bg-gray-200" id="strength1"></div>
                                <div class="h-1 flex-1 rounded-full bg-gray-200" id="strength2"></div>
                                <div class="h-1 flex-1 rounded-full bg-gray-200" id="strength3"></div>
                                <div class="h-1 flex-1 rounded-full bg-gray-200" id="strength4"></div>
                            </div>
                            <p class="text-xs text-gray-500 mt-1" id="strengthText">Must be at least 6 characters</p>
                        </div>
                    </div>

                    <!-- Confirm New Password -->
                    <div>
                        <label for="confirm_password" class="block text-sm font-medium text-gray-700 mb-2">Confirm New Password</label>
                        <div class="relative">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                                </svg>
                            </span>
                            <input type="password" id="confirm_password" name="confirm_password" required
                                   class="w-full pl-12 pr-4 py-3 border-2 border-gray-200 rounded-xl focus:border-sage-500 focus:ring-2 focus:ring-sage-200 transition-all"
                                   placeholder="Confirm new password">
                        </div>
                        <p class="text-xs text-red-500 mt-1 hidden" id="passwordMatchError">Passwords do not match</p>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" class="w-full py-4 px-6 bg-gradient-to-r from-emerald-500 to-emerald-600 hover:from-emerald-600 hover:to-emerald-700 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl transition-all transform hover:scale-[1.02] flex items-center justify-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        Reset Password
                    </button>
                </div>
            </form>

            <!-- Back to Login -->
            <div class="mt-6 text-center">
                <a href="<?php echo BASE_URL; ?>/login" class="inline-flex items-center text-sage-600 hover:text-sage-700 font-medium">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Back to Login
                </a>
            </div>
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
</script>

<?php include __DIR__ . '/../layouts/footer.php'; ?>
