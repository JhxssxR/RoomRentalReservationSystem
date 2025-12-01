<?php
$pageTitle = 'Reports - Room Rental';
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
                <h1 class="text-3xl font-bold text-white">Reports</h1>
                <p class="text-sage-200">Generate and view business reports</p>
            </div>
        </div>

        <!-- Report Filters -->
        <div class="bg-gradient-to-r from-sage-50 to-pale-50 rounded-xl shadow-lg p-6 mb-8">
            <form method="GET" class="flex flex-wrap items-end gap-4" id="reportForm">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Report Type</label>
                    <select name="type" id="reportType" class="px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-sage-500 focus:ring-0 transition min-w-[180px]">
                        <option value="revenue" <?php echo ($reportType ?? '') === 'revenue' ? 'selected' : ''; ?>>Revenue Report</option>
                        <option value="occupancy" <?php echo ($reportType ?? '') === 'occupancy' ? 'selected' : ''; ?>>Occupancy Report</option>
                        <option value="customers" <?php echo ($reportType ?? '') === 'customers' ? 'selected' : ''; ?>>Customer Report</option>
                        <option value="reservations" <?php echo ($reportType ?? '') === 'reservations' ? 'selected' : ''; ?>>Reservation Report</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Start Date</label>
                    <input type="date" name="start_date" id="startDate" value="<?php echo $startDate ?? date('Y-m-01'); ?>" 
                           class="px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-sage-500 focus:ring-0 transition">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">End Date</label>
                    <input type="date" name="end_date" id="endDate" value="<?php echo $endDate ?? date('Y-m-d'); ?>" 
                           class="px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-sage-500 focus:ring-0 transition">
                </div>
                <button type="submit" class="px-6 py-3 bg-gradient-to-r from-sage-500 to-sage-600 text-white rounded-xl hover:from-sage-600 hover:to-sage-700 transition shadow-lg flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    Generate Report
                </button>
                <button type="button" onclick="downloadPdf()" class="px-6 py-3 bg-gradient-to-r from-red-500 to-red-600 text-white rounded-xl hover:from-red-600 hover:to-red-700 transition shadow-lg flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    Download PDF
                </button>
            </form>
        </div>

        <!-- Quick Stats for Revenue Report -->
        <?php if (($reportType ?? 'revenue') === 'revenue' && !empty($reportData)): ?>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <?php 
            $totalRevenue = array_sum(array_column($reportData, 'revenue'));
            $totalTransactions = array_sum(array_column($reportData, 'transactions'));
            $avgTransaction = $totalTransactions > 0 ? $totalRevenue / $totalTransactions : 0;
            ?>
            <div class="bg-green-50 rounded-xl shadow-lg p-6 border-l-4 border-green-500">
                <p class="text-sm text-green-600 uppercase tracking-wide font-medium">Total Revenue</p>
                <p class="text-3xl font-bold text-green-800 mt-1">₱<?php echo number_format($totalRevenue, 2); ?></p>
            </div>
            <div class="bg-pale-50 rounded-xl shadow-lg p-6 border-l-4 border-pale-500">
                <p class="text-sm text-pale-600 uppercase tracking-wide font-medium">Total Transactions</p>
                <p class="text-3xl font-bold text-pale-800 mt-1"><?php echo $totalTransactions; ?></p>
            </div>
            <div class="bg-sage-50 rounded-xl shadow-lg p-6 border-l-4 border-sage-500">
                <p class="text-sm text-sage-600 uppercase tracking-wide font-medium">Average Transaction</p>
                <p class="text-3xl font-bold text-sage-800 mt-1">₱<?php echo number_format($avgTransaction, 2); ?></p>
            </div>
        </div>
        <?php endif; ?>

        <!-- Report Results -->
        <div class="bg-gradient-to-br from-sage-50 to-pale-50 rounded-xl shadow-lg overflow-hidden">
            <div class="p-6 border-b border-sage-100 flex justify-between items-center bg-gradient-to-r from-sage-100 to-pale-100">
                <div>
                    <h2 class="text-xl font-semibold text-gray-800">
                        <?php 
                        $reportTitles = [
                            'revenue' => 'Revenue Report',
                            'occupancy' => 'Occupancy Report',
                            'customers' => 'Customer Report',
                            'reservations' => 'Reservation Report'
                        ];
                        echo $reportTitles[$reportType ?? 'revenue'];
                        ?>
                    </h2>
                    <p class="text-sm text-gray-500 mt-1">
                        <?php echo date('M d, Y', strtotime($startDate ?? date('Y-m-01'))); ?> - 
                        <?php echo date('M d, Y', strtotime($endDate ?? date('Y-m-d'))); ?>
                    </p>
                </div>
                <button onclick="window.print()" class="px-4 py-2 border-2 border-gray-300 text-gray-600 rounded-lg hover:bg-gray-50 transition flex items-center">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                    Print
                </button>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase">Date</th>
                            <?php if (($reportType ?? 'revenue') === 'revenue'): ?>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase">Revenue</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase">Transactions</th>
                            <?php elseif ($reportType === 'occupancy'): ?>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase">Occupied Rooms</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase">Occupancy Rate</th>
                            <?php elseif ($reportType === 'customers'): ?>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase">New Customers</th>
                            <?php elseif ($reportType === 'reservations'): ?>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase">Total</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase">Confirmed</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase">Pending</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase">Cancelled</th>
                            <?php endif; ?>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        <?php if (empty($reportData)): ?>
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                                    <svg class="w-12 h-12 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                    No data available for the selected period
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($reportData as $row): ?>
                                <tr class="hover:bg-gray-50 transition">
                                    <td class="px-6 py-4 text-sm font-medium text-gray-800"><?php echo date('M d, Y', strtotime($row['date'])); ?></td>
                                    <?php if (($reportType ?? 'revenue') === 'revenue'): ?>
                                        <td class="px-6 py-4 text-sm font-bold text-green-600">₱<?php echo number_format($row['revenue'] ?? 0, 2); ?></td>
                                        <td class="px-6 py-4 text-sm text-gray-600"><?php echo $row['transactions'] ?? 0; ?></td>
                                    <?php elseif ($reportType === 'occupancy'): ?>
                                        <td class="px-6 py-4 text-sm text-gray-600"><?php echo $row['occupied_rooms'] ?? 0; ?> / <?php echo $row['total_rooms'] ?? 0; ?></td>
                                        <td class="px-6 py-4">
                                            <div class="flex items-center">
                                                <div class="w-24 bg-gray-200 rounded-full h-2 mr-2">
                                                    <div class="bg-gradient-to-r from-sage-500 to-pale-500 h-2 rounded-full" style="width: <?php echo $row['occupancy_rate'] ?? 0; ?>%"></div>
                                                </div>
                                                <span class="text-sm font-medium text-gray-700"><?php echo $row['occupancy_rate'] ?? 0; ?>%</span>
                                            </div>
                                        </td>
                                    <?php elseif ($reportType === 'customers'): ?>
                                        <td class="px-6 py-4">
                                            <span class="px-3 py-1 bg-pale-100 text-pale-700 text-sm font-semibold rounded-full"><?php echo $row['new_customers'] ?? 0; ?> new</span>
                                        </td>
                                    <?php elseif ($reportType === 'reservations'): ?>
                                        <td class="px-6 py-4 text-sm font-bold text-gray-800"><?php echo $row['total_reservations'] ?? 0; ?></td>
                                        <td class="px-6 py-4"><span class="px-2 py-1 bg-green-100 text-green-700 text-xs font-semibold rounded-full"><?php echo $row['confirmed'] ?? 0; ?></span></td>
                                        <td class="px-6 py-4"><span class="px-2 py-1 bg-yellow-100 text-yellow-700 text-xs font-semibold rounded-full"><?php echo $row['pending'] ?? 0; ?></span></td>
                                        <td class="px-6 py-4"><span class="px-2 py-1 bg-red-100 text-red-700 text-xs font-semibold rounded-full"><?php echo $row['cancelled'] ?? 0; ?></span></td>
                                    <?php endif; ?>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        function downloadPdf() {
            const type = document.getElementById('reportType').value;
            const startDate = document.getElementById('startDate').value;
            const endDate = document.getElementById('endDate').value;
            
            const url = '<?php echo BASE_URL; ?>/admin/reports/pdf?type=' + type + '&start_date=' + startDate + '&end_date=' + endDate;
            window.open(url, '_blank');
        }
    </script>
</body>
</html>
