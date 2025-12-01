<?php
$pageTitle = 'Manage Payments - Room Rental';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $pageTitle; ?></title>
    
    <!-- Favicon -->
    <link rel="icon" type="image/svg+xml" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><rect width='100' height='100' rx='20' fill='%23f0f7fa'/><path d='M50 25L20 50v25a5 5 0 005 5h15v-15a5 5 0 015-5h10a5 5 0 015 5v15h15a5 5 0 005-5V50L50 25z' fill='%235a7858'/></svg>">
    
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        sage: { 50: '#f4f7f4', 100: '#e4ebe4', 200: '#c9d9c9', 300: '#a3bfa3', 400: '#7a9f7a', 500: '#4a7c59', 600: '#3d6549', 700: '#33523d', 800: '#2b4333', 900: '#24382b' },
                        pale: { 50: '#f0f7ff', 100: '#e0efff', 200: '#baddff', 300: '#7cc2ff', 400: '#36a3ff', 500: '#0c87eb', 600: '#006bc9', 700: '#0055a3', 800: '#054986', 900: '#0a3d6f' }
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
                <h1 class="text-3xl font-bold text-white">Payment Management</h1>
                <p class="text-sage-200">Review and approve customer payments</p>
            </div>
            <div class="flex items-center space-x-4">
                <?php 
                $pendingCount = 0;
                foreach ($payments ?? [] as $p) {
                    if (strtolower($p['status'] ?? '') === 'pending') $pendingCount++;
                }
                ?>
                <?php if ($pendingCount > 0): ?>
                <div class="bg-amber-50 border border-amber-300 rounded-lg px-4 py-2 flex items-center">
                    <svg class="w-5 h-5 text-amber-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <span class="text-amber-800 font-semibold"><?php echo $pendingCount; ?> pending approval</span>
                </div>
                <?php endif; ?>
            </div>
        </div>

        <?php if (isset($_SESSION['success'])): ?>
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded-lg mb-6"><?php echo $_SESSION['success']; unset($_SESSION['success']); ?></div>
        <?php endif; ?>
        <?php if (isset($_SESSION['error'])): ?>
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded-lg mb-6"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></div>
        <?php endif; ?>

        <!-- Filter Tabs -->
        <div class="mb-6 flex space-x-2">
            <button onclick="filterPayments('all')" class="filter-btn active px-4 py-2 rounded-lg bg-sage-500 text-white transition" data-filter="all">All Payments</button>
            <button onclick="filterPayments('pending')" class="filter-btn px-4 py-2 rounded-lg bg-sage-100 text-sage-700 hover:bg-sage-200 transition" data-filter="pending">Pending</button>
            <button onclick="filterPayments('approved')" class="filter-btn px-4 py-2 rounded-lg bg-sage-100 text-sage-700 hover:bg-sage-200 transition" data-filter="approved">Approved</button>
            <button onclick="filterPayments('rejected')" class="filter-btn px-4 py-2 rounded-lg bg-sage-100 text-sage-700 hover:bg-sage-200 transition" data-filter="rejected">Rejected</button>
        </div>

        <!-- Payments Table -->
        <div class="bg-gradient-to-br from-sage-50 to-pale-50 rounded-xl shadow-lg overflow-hidden">
            <table class="w-full">
                <thead class="bg-gradient-to-r from-sage-100 to-pale-100">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase">Payment ID</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase">Customer</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase">Room</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase">Amount</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase">Date</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase">Status</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-sage-100">
                    <?php if (empty($payments)): ?>
                        <tr><td colspan="7" class="px-6 py-12 text-center text-gray-500">No payments found</td></tr>
                    <?php else: ?>
                        <?php foreach ($payments as $payment): ?>
                            <?php $status = strtolower($payment['status'] ?? 'pending'); ?>
                            <tr class="hover:bg-sage-100/50 transition payment-row" data-status="<?php echo $status; ?>">
                                <td class="px-6 py-4">
                                    <span class="text-sm font-mono bg-sage-100 px-2 py-1 rounded">#<?php echo $payment['payment_id'] ?? $payment['id'] ?? 'N/A'; ?></span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center">
                                        <div class="w-8 h-8 bg-gradient-to-br from-sage-400 to-pale-500 rounded-full flex items-center justify-center text-white text-sm font-semibold">
                                            <?php echo strtoupper(substr($payment['customer_name'] ?? 'U', 0, 1)); ?>
                                        </div>
                                        <span class="ml-3 text-sm text-gray-800"><?php echo htmlspecialchars($payment['customer_name'] ?? 'Unknown'); ?></span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-600">
                                    <?php echo htmlspecialchars(($payment['room_number'] ?? '') . ($payment['room_name'] ? ' - ' . $payment['room_name'] : '')); ?>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="text-lg font-bold text-gray-800">₱<?php echo number_format($payment['amount'] ?? 0, 2); ?></span>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-600">
                                    <?php echo date('M d, Y', strtotime($payment['payment_date'] ?? $payment['created_at'] ?? 'now')); ?>
                                </td>
                                <td class="px-6 py-4">
                                    <?php 
                                    $statusClass = match($status) {
                                        'pending' => 'bg-yellow-100 text-yellow-800 border-yellow-300',
                                        'approved', 'confirmed' => 'bg-green-100 text-green-800 border-green-300',
                                        'rejected' => 'bg-red-100 text-red-800 border-red-300',
                                        default => 'bg-gray-100 text-gray-800 border-gray-300'
                                    };
                                    ?>
                                    <span class="px-3 py-1 text-xs font-semibold rounded-full border <?php echo $statusClass; ?>">
                                        <?php echo ucfirst($status); ?>
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <?php if ($status === 'pending'): ?>
                                        <div class="flex items-center space-x-2">
                                            <form method="POST" class="inline">
                                                <input type="hidden" name="payment_id" value="<?php echo $payment['payment_id'] ?? $payment['id']; ?>">
                                                <input type="hidden" name="action" value="approve">
                                                <button type="submit" class="flex items-center px-3 py-2 bg-green-500 text-white text-sm rounded-lg hover:bg-green-600 transition shadow-sm" onclick="return confirm('Approve this payment?')">
                                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                                    Approve
                                                </button>
                                            </form>
                                            <form method="POST" class="inline">
                                                <input type="hidden" name="payment_id" value="<?php echo $payment['payment_id'] ?? $payment['id']; ?>">
                                                <input type="hidden" name="action" value="reject">
                                                <button type="submit" class="flex items-center px-3 py-2 bg-red-500 text-white text-sm rounded-lg hover:bg-red-600 transition shadow-sm" onclick="return confirm('Reject this payment?')">
                                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                                    Reject
                                                </button>
                                            </form>
                                        </div>
                                    <?php elseif ($status === 'approved' || $status === 'confirmed'): ?>
                                        <span class="flex items-center text-green-600 text-sm">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                            Approved
                                        </span>
                                    <?php else: ?>
                                        <span class="flex items-center text-red-600 text-sm">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                            Rejected
                                        </span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <script>
        function filterPayments(status) {
            // Update button styles
            document.querySelectorAll('.filter-btn').forEach(btn => {
                btn.classList.remove('bg-sage-500', 'text-white');
                btn.classList.add('bg-sage-100', 'text-sage-700');
            });
            document.querySelector(`.filter-btn[data-filter="${status}"]`).classList.remove('bg-sage-100', 'text-sage-700');
            document.querySelector(`.filter-btn[data-filter="${status}"]`).classList.add('bg-sage-500', 'text-white');
            
            // Filter rows
            document.querySelectorAll('.payment-row').forEach(row => {
                if (status === 'all' || row.dataset.status === status || (status === 'approved' && row.dataset.status === 'confirmed')) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        }
    </script>
</body>
</html>
