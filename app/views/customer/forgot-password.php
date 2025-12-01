<?php
$pageTitle = 'Forgot Password - Room Rental';
include __DIR__ . '/../layouts/header.php';
?>

<div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8 bg-gradient-to-br from-sage-600 via-sage-700 to-pale-600">
    <div class="max-w-md w-full">
        <div class="bg-gradient-to-br from-sage-50 to-pale-50 rounded-2xl shadow-2xl p-8 border border-sage-200 backdrop-blur-sm">
            <!-- Header -->
            <div class="text-center mb-8">
                <div class="mx-auto w-16 h-16 bg-gradient-to-br from-amber-400 to-amber-600 rounded-2xl flex items-center justify-center mb-4 shadow-lg">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path>
                    </svg>
                </div>
                <?php if (!empty($emailVerified)): ?>
                    <h2 class="text-2xl font-bold bg-gradient-to-r from-sage-700 to-pale-600 bg-clip-text text-transparent">Reset Your Password</h2>
                    <p class="text-gray-500 mt-2">Enter your new password below.</p>
                <?php else: ?>
                    <h2 class="text-2xl font-bold bg-gradient-to-r from-sage-700 to-pale-600 bg-clip-text text-transparent">Forgot Password?</h2>
                    <p class="text-gray-500 mt-2">No worries! Enter your email to reset your password.</p>
                <?php endif; ?>
            </div>

            <!-- Messages -->
            <?php if (!empty($message)): ?>
                <div class="mb-6 p-4 <?php echo $messageType === 'success' ? 'bg-emerald-50 border-emerald-200' : 'bg-red-50 border-red-200'; ?> border rounded-xl">
                    <div class="flex items-center">
                        <?php if ($messageType === 'success'): ?>
                            <svg class="w-5 h-5 text-emerald-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <span class="text-emerald-700 text-sm"><?php echo $message; ?></span>
                        <?php else: ?>
                            <svg class="w-5 h-5 text-red-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <span class="text-red-700 text-sm"><?php echo $message; ?></span>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endif; ?>

            <?php if (!empty($_SESSION['error'])): ?>
                <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-xl">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-red-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span class="text-red-700 text-sm"><?php echo htmlspecialchars($_SESSION['error']); unset($_SESSION['error']); ?></span>
                    </div>
                </div>
            <?php endif; ?>

            <?php if (!empty($emailVerified)): ?>
                <!-- Password Reset Form (Step 2) -->
                <div class="mb-6 p-4 bg-emerald-50 border border-emerald-200 rounded-xl">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-emerald-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span class="text-emerald-700 text-sm">Email verified: <strong><?php echo htmlspecialchars($verifiedEmail); ?></strong></span>
                    </div>
                </div>

                <form method="POST" action="<?php echo BASE_URL; ?>/forgot-password">
                    <input type="hidden" name="action" value="reset_password">
                    <input type="hidden" name="verified_email" value="<?php echo htmlspecialchars($verifiedEmail); ?>">
                    
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
                                       class="w-full pl-12 pr-4 py-3 bg-sage-50/50 border-2 border-sage-200 rounded-xl focus:border-sage-500 focus:ring-2 focus:ring-sage-200 transition-all"
                                       placeholder="Enter new password (min 6 characters)">
                            </div>
                        </div>

                        <!-- Confirm Password -->
                        <div>
                            <label for="confirm_password" class="block text-sm font-medium text-gray-700 mb-2">Confirm Password</label>
                            <div class="relative">
                                <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                                    </svg>
                                </span>
                                <input type="password" id="confirm_password" name="confirm_password" required minlength="6"
                                       class="w-full pl-12 pr-4 py-3 bg-sage-50/50 border-2 border-sage-200 rounded-xl focus:border-sage-500 focus:ring-2 focus:ring-sage-200 transition-all"
                                       placeholder="Confirm new password">
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <button type="submit" class="w-full py-4 px-6 bg-gradient-to-r from-sage-500 to-pale-500 hover:from-sage-600 hover:to-pale-600 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl transition-all transform hover:scale-[1.02] flex items-center justify-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path>
                            </svg>
                            Reset Password
                        </button>
                    </div>
                </form>

                <!-- Start Over -->
                <div class="mt-6 text-center">
                    <a href="<?php echo BASE_URL; ?>/forgot-password" class="inline-flex items-center text-sage-600 hover:text-sage-700 font-medium">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        Use Different Email
                    </a>
                </div>
            <?php else: ?>
                <!-- Email Verification Form (Step 1) -->
                <form method="POST" action="<?php echo BASE_URL; ?>/forgot-password">
                    <input type="hidden" name="action" value="verify_email">
                    <div class="space-y-5">
                        <!-- Email -->
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email Address</label>
                            <div class="relative">
                                <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                    </svg>
                                </span>
                                <input type="email" id="email" name="email" required
                                       value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>"
                                       class="w-full pl-12 pr-4 py-3 bg-sage-50/50 border-2 border-sage-200 rounded-xl focus:border-sage-500 focus:ring-2 focus:ring-sage-200 transition-all"
                                       placeholder="Enter your email address">
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <button type="submit" class="w-full py-4 px-6 bg-gradient-to-r from-sage-500 to-pale-500 hover:from-sage-600 hover:to-pale-600 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl transition-all transform hover:scale-[1.02] flex items-center justify-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Verify Email
                        </button>
                    </div>
                </form>
            <?php endif; ?>

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

<?php include __DIR__ . '/../layouts/footer.php'; ?>
