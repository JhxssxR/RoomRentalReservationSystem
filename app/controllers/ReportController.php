<?php
/**
 * Report Controller
 * Handles PDF report generation
 */

class ReportController
{
    private function requireAdmin()
    {
        Auth::requireLogin();
        
        if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
            $_SESSION['error'] = 'Access denied. Admin privileges required.';
            header('Location: ' . BASE_URL . '/');
            exit;
        }
    }

    /**
     * Generate PDF Report
     */
    public function generatePdf()
    {
        $this->requireAdmin();

        require_once __DIR__ . '/../models/Report.php';
        $reportModel = new Report();

        $reportType = htmlspecialchars($_GET['type'] ?? 'revenue');
        $startDate = htmlspecialchars($_GET['start_date'] ?? date('Y-m-01'));
        $endDate = htmlspecialchars($_GET['end_date'] ?? date('Y-m-d'));

        // Get report data based on type
        switch ($reportType) {
            case 'revenue':
                $reportData = $reportModel->getRevenueReport($startDate, $endDate);
                $title = 'Revenue Report';
                break;
            case 'occupancy':
                $reportData = $reportModel->getOccupancyReport($startDate, $endDate);
                $title = 'Occupancy Report';
                break;
            case 'customers':
                $reportData = $reportModel->getCustomerReport($startDate, $endDate);
                $title = 'Customer Report';
                break;
            case 'reservations':
                $reportData = $reportModel->getReservationReport($startDate, $endDate);
                $title = 'Reservation Report';
                break;
            default:
                $reportData = [];
                $title = 'Report';
        }

        // Generate HTML for PDF
        $html = $this->generateReportHtml($title, $reportType, $reportData, $startDate, $endDate, $reportModel);
        
        // Output as downloadable HTML that will be converted to PDF by browser
        header('Content-Type: text/html; charset=utf-8');
        echo $html;
        exit;
    }

    /**
     * Generate Report HTML
     */
    private function generateReportHtml($title, $reportType, $reportData, $startDate, $endDate, $reportModel)
    {
        // Calculate totals for summary
        $totalRevenue = 0;
        $totalTransactions = 0;
        
        if ($reportType === 'revenue' && !empty($reportData)) {
            $totalRevenue = array_sum(array_column($reportData, 'revenue'));
            $totalTransactions = array_sum(array_column($reportData, 'transactions'));
        }

        $html = '<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>' . htmlspecialchars($title) . ' - Room Rental System</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { 
            font-family: "Segoe UI", Arial, sans-serif; 
            padding: 40px; 
            color: #333;
            background: #fff;
        }
        .header { 
            text-align: center; 
            margin-bottom: 30px; 
            padding-bottom: 20px;
            border-bottom: 3px solid #4a7c59;
        }
        .logo { 
            font-size: 28px; 
            font-weight: bold; 
            color: #4a7c59;
            margin-bottom: 5px;
        }
        .report-title { 
            font-size: 22px; 
            color: #333;
            margin-top: 10px;
        }
        .report-period { 
            color: #666; 
            font-size: 14px;
            margin-top: 5px;
        }
        .summary-box {
            background: linear-gradient(135deg, #f4f7f4 0%, #e4ebe4 100%);
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 25px;
            display: flex;
            justify-content: space-around;
            flex-wrap: wrap;
        }
        .summary-item {
            text-align: center;
            padding: 10px 20px;
        }
        .summary-label {
            font-size: 12px;
            color: #666;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .summary-value {
            font-size: 24px;
            font-weight: bold;
            color: #4a7c59;
            margin-top: 5px;
        }
        table { 
            width: 100%; 
            border-collapse: collapse; 
            margin-top: 20px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        th { 
            background: linear-gradient(135deg, #4a7c59 0%, #3d6549 100%);
            color: white; 
            padding: 12px 15px; 
            text-align: left;
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        td { 
            padding: 12px 15px; 
            border-bottom: 1px solid #eee;
            font-size: 14px;
        }
        tr:nth-child(even) { background: #f9fafb; }
        tr:hover { background: #f0f7ff; }
        .amount { 
            font-weight: 600; 
            color: #059669;
        }
        .badge {
            display: inline-block;
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }
        .badge-green { background: #d1fae5; color: #065f46; }
        .badge-yellow { background: #fef3c7; color: #92400e; }
        .badge-red { background: #fee2e2; color: #991b1b; }
        .badge-blue { background: #dbeafe; color: #1e40af; }
        .progress-bar {
            background: #e5e7eb;
            border-radius: 10px;
            height: 8px;
            width: 100px;
            display: inline-block;
            margin-right: 10px;
        }
        .progress-fill {
            background: linear-gradient(90deg, #4a7c59, #0c87eb);
            height: 100%;
            border-radius: 10px;
        }
        .footer { 
            margin-top: 40px; 
            text-align: center; 
            color: #888;
            font-size: 12px;
            padding-top: 20px;
            border-top: 1px solid #eee;
        }
        .no-data {
            text-align: center;
            padding: 40px;
            color: #888;
        }
        @media print {
            body { padding: 20px; }
            .no-print { display: none; }
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="logo">🏨 Room Rental System</div>
        <div class="report-title">' . htmlspecialchars($title) . '</div>
        <div class="report-period">Period: ' . date('F j, Y', strtotime($startDate)) . ' - ' . date('F j, Y', strtotime($endDate)) . '</div>
        <div class="report-period">Generated: ' . date('F j, Y g:i A') . '</div>
    </div>';

        // Add summary for revenue report
        if ($reportType === 'revenue' && !empty($reportData)) {
            $avgTransaction = $totalTransactions > 0 ? $totalRevenue / $totalTransactions : 0;
            $html .= '
    <div class="summary-box">
        <div class="summary-item">
            <div class="summary-label">Total Revenue</div>
            <div class="summary-value">₱' . number_format($totalRevenue, 2) . '</div>
        </div>
        <div class="summary-item">
            <div class="summary-label">Total Transactions</div>
            <div class="summary-value">' . $totalTransactions . '</div>
        </div>
        <div class="summary-item">
            <div class="summary-label">Average Transaction</div>
            <div class="summary-value">₱' . number_format($avgTransaction, 2) . '</div>
        </div>
    </div>';
        }

        // Table content based on report type
        if (empty($reportData)) {
            $html .= '<div class="no-data">No data available for the selected period.</div>';
        } else {
            $html .= '<table><thead><tr><th>Date</th>';
            
            switch ($reportType) {
                case 'revenue':
                    $html .= '<th>Revenue</th><th>Transactions</th>';
                    break;
                case 'occupancy':
                    $html .= '<th>Occupied Rooms</th><th>Occupancy Rate</th>';
                    break;
                case 'customers':
                    $html .= '<th>New Customers</th>';
                    break;
                case 'reservations':
                    $html .= '<th>Total</th><th>Confirmed</th><th>Pending</th><th>Cancelled</th><th>Value</th>';
                    break;
            }
            
            $html .= '</tr></thead><tbody>';

            foreach ($reportData as $row) {
                $html .= '<tr><td>' . date('M d, Y', strtotime($row['date'])) . '</td>';
                
                switch ($reportType) {
                    case 'revenue':
                        $html .= '<td class="amount">₱' . number_format($row['revenue'] ?? 0, 2) . '</td>';
                        $html .= '<td>' . ($row['transactions'] ?? 0) . '</td>';
                        break;
                    case 'occupancy':
                        $html .= '<td>' . ($row['occupied_rooms'] ?? 0) . ' / ' . ($row['total_rooms'] ?? 0) . '</td>';
                        $rate = $row['occupancy_rate'] ?? 0;
                        $html .= '<td><div class="progress-bar"><div class="progress-fill" style="width:' . $rate . '%"></div></div>' . $rate . '%</td>';
                        break;
                    case 'customers':
                        $html .= '<td><span class="badge badge-blue">' . ($row['new_customers'] ?? 0) . ' new</span></td>';
                        break;
                    case 'reservations':
                        $html .= '<td><strong>' . ($row['total_reservations'] ?? 0) . '</strong></td>';
                        $html .= '<td><span class="badge badge-green">' . ($row['confirmed'] ?? 0) . '</span></td>';
                        $html .= '<td><span class="badge badge-yellow">' . ($row['pending'] ?? 0) . '</span></td>';
                        $html .= '<td><span class="badge badge-red">' . ($row['cancelled'] ?? 0) . '</span></td>';
                        $html .= '<td class="amount">₱' . number_format($row['total_value'] ?? 0, 2) . '</td>';
                        break;
                }
                
                $html .= '</tr>';
            }

            $html .= '</tbody></table>';
        }

        $html .= '
    <div class="footer">
        <p>Room Rental Reservation System &copy; ' . date('Y') . '</p>
        <p>This report was automatically generated. Please verify all data.</p>
    </div>
    
    <script>
        // Auto-trigger print dialog when page loads
        window.onload = function() {
            window.print();
        }
    </script>
</body>
</html>';

        return $html;
    }
}
