<?php
$pageTitle = 'Manage Customers - Room Rental';
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
                <h1 class="text-3xl font-bold text-white">Customer Management</h1>
                <p class="text-sage-200">View and manage registered customers</p>
            </div>
            <div class="flex items-center space-x-4">
                <div class="bg-sage-50 rounded-lg shadow-sm px-4 py-2 border border-sage-100">
                    <span class="text-sm text-sage-600">Total Customers:</span>
                    <span class="ml-2 font-bold text-sage-700"><?php echo count($customers ?? []); ?></span>
                </div>
            </div>
        </div>

        <?php if (isset($_SESSION['success'])): ?>
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded-lg mb-6"><?php echo $_SESSION['success']; unset($_SESSION['success']); ?></div>
        <?php endif; ?>
        <?php if (isset($_SESSION['error'])): ?>
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded-lg mb-6"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></div>
        <?php endif; ?>

        <!-- Search -->
        <div class="mb-6">
            <div class="relative max-w-md">
                <input type="text" id="searchInput" placeholder="Search customers by name or email..." 
                       class="w-full pl-10 pr-4 py-3 border-2 border-gray-200 rounded-xl focus:border-sage-500 focus:ring-0 transition"
                       onkeyup="searchTable()">
                <svg class="w-5 h-5 text-gray-400 absolute left-3 top-1/2 transform -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
            </div>
        </div>

        <!-- Customers Table -->
        <div class="bg-gradient-to-br from-sage-50 to-pale-50 rounded-xl shadow-lg overflow-hidden">
            <table id="customersTable" class="w-full">
                <thead class="bg-gradient-to-r from-sage-100 to-pale-100">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase">Customer</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase">Email</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase">Phone</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase">Role</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase">Registered</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    <?php if (empty($customers)): ?>
                        <tr><td colspan="6" class="px-6 py-12 text-center text-gray-500">No customers found</td></tr>
                    <?php else: ?>
                        <?php foreach ($customers as $customer): ?>
                            <tr class="hover:bg-gray-50 transition customer-row">
                                <td class="px-6 py-4">
                                    <div class="flex items-center">
                                        <div class="w-10 h-10 bg-gradient-to-br from-sage-400 to-pale-500 rounded-full flex items-center justify-center text-white font-semibold shadow-md">
                                            <?php echo strtoupper(substr($customer['name'], 0, 1)); ?>
                                        </div>
                                        <div class="ml-3">
                                            <p class="font-semibold text-gray-800 customer-name"><?php echo htmlspecialchars($customer['name']); ?></p>
                                            <?php if (!empty($customer['address'])): ?>
                                                <p class="text-xs text-gray-500"><?php echo htmlspecialchars($customer['address']); ?></p>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-600 customer-email"><?php echo htmlspecialchars($customer['email']); ?></td>
                                <td class="px-6 py-4 text-sm text-gray-600"><?php echo htmlspecialchars($customer['contact'] ?? $customer['phone'] ?? 'N/A'); ?></td>
                                <td class="px-6 py-4">
                                    <?php $role = $customer['role'] ?? 'customer'; ?>
                                    <span class="px-3 py-1 text-xs font-semibold rounded-full <?php echo $role === 'admin' ? 'bg-purple-100 text-purple-800' : 'bg-gray-100 text-gray-800'; ?>">
                                        <?php echo ucfirst($role); ?>
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-600"><?php echo date('M d, Y', strtotime($customer['created_at'])); ?></td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center space-x-2">
                                        <button onclick='viewCustomer(<?php echo json_encode($customer); ?>)' class="p-2 bg-pale-100 text-pale-600 rounded-lg hover:bg-pale-200 transition" title="View Details">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                        </button>
                                        <?php if (($customer['role'] ?? 'customer') !== 'admin'): ?>
                                        <form method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this customer?')">
                                            <input type="hidden" name="action" value="delete">
                                            <input type="hidden" name="id" value="<?php echo $customer['customer_id'] ?? $customer['id']; ?>">
                                            <button type="submit" class="p-2 bg-red-100 text-red-600 rounded-lg hover:bg-red-200 transition" title="Delete">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                            </button>
                                        </form>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- View Customer Modal -->
    <div id="viewCustomerModal" class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50 backdrop-blur-sm">
        <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full mx-4">
            <div class="p-6 border-b border-gray-100 flex justify-between items-center bg-gradient-to-r from-sage-500 to-pale-500 rounded-t-2xl">
                <h2 class="text-xl font-bold text-white">Customer Details</h2>
                <button onclick="closeModal('viewCustomerModal')" class="text-white/80 hover:text-white"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg></button>
            </div>
            <div class="p-6">
                <div class="flex items-center mb-6">
                    <div id="modal_avatar" class="w-16 h-16 bg-gradient-to-br from-sage-400 to-pale-500 rounded-full flex items-center justify-center text-white text-2xl font-bold shadow-lg"></div>
                    <div class="ml-4">
                        <h3 id="modal_name" class="text-xl font-bold text-gray-800"></h3>
                        <p id="modal_role" class="text-sm text-gray-500"></p>
                    </div>
                </div>
                <div class="space-y-4">
                    <div class="flex items-center p-3 bg-gray-50 rounded-lg">
                        <svg class="w-5 h-5 text-gray-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                        <span id="modal_email" class="text-gray-700"></span>
                    </div>
                    <div class="flex items-center p-3 bg-gray-50 rounded-lg">
                        <svg class="w-5 h-5 text-gray-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                        <span id="modal_phone" class="text-gray-700"></span>
                    </div>
                    <div class="flex items-center p-3 bg-gray-50 rounded-lg">
                        <svg class="w-5 h-5 text-gray-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                        <span id="modal_address" class="text-gray-700"></span>
                    </div>
                    <div class="flex items-center p-3 bg-gray-50 rounded-lg">
                        <svg class="w-5 h-5 text-gray-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        <span id="modal_date" class="text-gray-700"></span>
                    </div>
                </div>
            </div>
            <div class="p-6 border-t border-gray-100 bg-gray-50 rounded-b-2xl">
                <button onclick="closeModal('viewCustomerModal')" class="w-full px-6 py-2 bg-gradient-to-r from-sage-500 to-sage-600 text-white rounded-lg hover:from-sage-600 hover:to-sage-700 transition">Close</button>
            </div>
        </div>
    </div>

    <script>
        function openModal(id) {
            document.getElementById(id).classList.remove('hidden');
            document.getElementById(id).classList.add('flex');
        }
        function closeModal(id) {
            document.getElementById(id).classList.add('hidden');
            document.getElementById(id).classList.remove('flex');
        }
        function viewCustomer(customer) {
            document.getElementById('modal_avatar').textContent = customer.name.charAt(0).toUpperCase();
            document.getElementById('modal_name').textContent = customer.name;
            document.getElementById('modal_role').textContent = (customer.role || 'customer').charAt(0).toUpperCase() + (customer.role || 'customer').slice(1);
            document.getElementById('modal_email').textContent = customer.email;
            document.getElementById('modal_phone').textContent = customer.contact || customer.phone || 'Not provided';
            document.getElementById('modal_address').textContent = customer.address || 'Not provided';
            document.getElementById('modal_date').textContent = 'Registered: ' + new Date(customer.created_at).toLocaleDateString('en-US', {year: 'numeric', month: 'long', day: 'numeric'});
            openModal('viewCustomerModal');
        }
        function searchTable() {
            const filter = document.getElementById('searchInput').value.toLowerCase();
            const rows = document.querySelectorAll('.customer-row');
            rows.forEach(row => {
                const name = row.querySelector('.customer-name').textContent.toLowerCase();
                const email = row.querySelector('.customer-email').textContent.toLowerCase();
                row.style.display = (name.includes(filter) || email.includes(filter)) ? '' : 'none';
            });
        }
        document.querySelectorAll('[id$="Modal"]').forEach(modal => {
            modal.addEventListener('click', e => { if (e.target === modal) closeModal(modal.id); });
        });
    </script>
</body>
</html>
