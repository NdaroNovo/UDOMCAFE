<?php
/**
 * Sales Report Page for CIVE Cafeteria Manager
 */

require_once 'config.php';

// Check if logged in as manager
if (!isLoggedIn() || (!hasRole('manager') && !hasRole('admin'))) {
    redirect('login.php?role=manager');
}

// Get date range
$period = $_GET['period'] ?? 'today';
$startDate = $_GET['start'] ?? date('Y-m-d');
$endDate = $_GET['end'] ?? date('Y-m-d');

switch ($period) {
    case 'yesterday':
        $startDate = $endDate = date('Y-m-d', strtotime('-1 day'));
        break;
    case 'week':
        $startDate = date('Y-m-d', strtotime('-7 days'));
        $endDate = date('Y-m-d');
        break;
    case 'month':
        $startDate = date('Y-m-01');
        $endDate = date('Y-m-d');
        break;
}

// Fetch sales data
$stmt = $conn->prepare("SELECT 
    DATE(o.created_at) as sale_date,
    COUNT(*) as total_orders,
    SUM(o.total_amount) as total_revenue,
    SUM(oi.quantity) as total_items,
    AVG(o.total_amount) as avg_order_value
    FROM orders o
    LEFT JOIN order_items oi ON o.id = oi.order_id
    WHERE DATE(o.created_at) BETWEEN ? AND ?
    AND o.status != 'cancelled'
    GROUP BY DATE(o.created_at)
    ORDER BY sale_date DESC");
$stmt->execute([$startDate, $endDate]);
$salesData = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Summary stats
$stmt = $conn->prepare("SELECT 
    COUNT(*) as total_orders,
    SUM(total_amount) as total_revenue,
    AVG(total_amount) as avg_order_value
    FROM orders 
    WHERE DATE(created_at) BETWEEN ? AND ?
    AND status != 'cancelled'");
$stmt->execute([$startDate, $endDate]);
$summary = $stmt->fetch(PDO::FETCH_ASSOC);

// Popular items
$stmt = $conn->prepare("SELECT 
    fi.name,
    fi.name_sw,
    SUM(oi.quantity) as total_sold,
    SUM(oi.subtotal) as total_revenue
    FROM order_items oi
    JOIN food_items fi ON oi.food_item_id = fi.id
    JOIN orders o ON oi.order_id = o.id
    WHERE DATE(o.created_at) BETWEEN ? AND ?
    AND o.status != 'cancelled'
    GROUP BY oi.food_item_id
    ORDER BY total_sold DESC
    LIMIT 10");
$stmt->execute([$startDate, $endDate]);
$popularItems = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Payment methods breakdown
$stmt = $conn->prepare("SELECT 
    payment_method,
    COUNT(*) as count,
    SUM(total_amount) as total
    FROM orders 
    WHERE DATE(created_at) BETWEEN ? AND ?
    AND status != 'cancelled'
    GROUP BY payment_method");
$stmt->execute([$startDate, $endDate]);
$paymentBreakdown = $stmt->fetchAll(PDO::FETCH_ASSOC);

$lang = getLang();
?>
<!DOCTYPE html>
<html lang="<?php echo $lang; ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo APP_NAME; ?> - <?php echo $lang === 'sw' ? 'Ripoti ya Mauzo' : 'Sales Report'; ?></title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        .report-header {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            color: white;
            padding: 2rem;
            border-radius: var(--radius-lg);
            margin-bottom: 1.5rem;
        }
        .report-header h2 { color: white; margin-bottom: 0.5rem; }
        .date-range { opacity: 0.9; }
        .summary-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            margin-bottom: 1.5rem;
        }
        .summary-card {
            background: var(--white);
            padding: 1.5rem;
            border-radius: var(--radius-lg);
            box-shadow: var(--shadow-sm);
            border-left: 4px solid var(--primary);
        }
        .summary-label {
            font-size: 0.875rem;
            color: var(--gray-500);
            margin-bottom: 0.5rem;
        }
        .summary-value {
            font-size: 1.75rem;
            font-weight: 700;
            color: var(--gray-900);
        }
        .data-table {
            width: 100%;
            border-collapse: collapse;
            background: var(--white);
            border-radius: var(--radius-lg);
            overflow: hidden;
            box-shadow: var(--shadow-sm);
        }
        .data-table th {
            background: var(--gray-50);
            padding: 1rem;
            text-align: left;
            font-size: 0.875rem;
            font-weight: 600;
            color: var(--gray-600);
            text-transform: uppercase;
        }
        .data-table td {
            padding: 1rem;
            border-top: 1px solid var(--gray-100);
        }
        .data-table tr:hover {
            background: var(--gray-50);
        }
        .period-selector {
            display: flex;
            gap: 0.5rem;
            margin-bottom: 1.5rem;
            flex-wrap: wrap;
        }
        .period-btn {
            padding: 0.75rem 1.5rem;
            border-radius: var(--radius-full);
            font-weight: 500;
            border: 2px solid var(--gray-200);
            background: var(--white);
            color: var(--gray-700);
            cursor: pointer;
            transition: all 0.2s;
        }
        .period-btn:hover, .period-btn.active {
            border-color: var(--primary);
            color: var(--primary);
            background: var(--primary-light);
        }
        .chart-placeholder {
            background: var(--gray-50);
            height: 200px;
            border-radius: var(--radius-lg);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--gray-400);
            margin-bottom: 1.5rem;
        }
        .export-btn {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.75rem 1.5rem;
            background: var(--gray-800);
            color: white;
            border-radius: var(--radius-md);
            font-weight: 500;
            text-decoration: none;
            transition: all 0.2s;
        }
        .export-btn:hover {
            background: var(--gray-900);
            color: white;
        }
    </style>
</head>
<body>
    <div class="app-container">
        <!-- Header -->
        <header class="app-header">
            <h1>
                <svg class="logo-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                <?php echo $lang === 'sw' ? 'Ripoti ya Mauzo' : 'Sales Report'; ?>
            </h1>
            <div class="header-actions">
                <a href="dashboard.php" class="btn btn-sm btn-secondary" style="color: white; background: rgba(255,255,255,0.2);">
                    <?php __('dashboard'); ?>
                </a>
                <a href="logout.php" class="btn btn-sm btn-danger"><?php __('logout'); ?></a>
            </div>
        </header>

        <!-- Main Content -->
        <main class="main-content">
            <div class="page-content">
                <!-- Period Selector -->
                <div class="period-selector">
                    <a href="?period=today" class="period-btn <?php echo $period === 'today' ? 'active' : ''; ?>">
                        <?php __('today'); ?>
                    </a>
                    <a href="?period=yesterday" class="period-btn <?php echo $period === 'yesterday' ? 'active' : ''; ?>">
                        <?php __('yesterday'); ?>
                    </a>
                    <a href="?period=week" class="period-btn <?php echo $period === 'week' ? 'active' : ''; ?>">
                        <?php echo $lang === 'sw' ? 'Wiki' : 'Week'; ?>
                    </a>
                    <a href="?period=month" class="period-btn <?php echo $period === 'month' ? 'active' : ''; ?>">
                        <?php echo $lang === 'sw' ? 'Mwezi' : 'Month'; ?>
                    </a>
                </div>

                <!-- Report Header -->
                <div class="report-header">
                    <h2><?php echo $lang === 'sw' ? 'Muhtasari wa Mauzo' : 'Sales Summary'; ?></h2>
                    <div class="date-range">
                        <?php 
                        echo date('d M Y', strtotime($startDate)); 
                        if ($startDate !== $endDate) {
                            echo ' - ' . date('d M Y', strtotime($endDate));
                        }
                        ?>
                    </div>
                </div>

                <!-- Summary Cards -->
                <div class="summary-grid">
                    <div class="summary-card">
                        <div class="summary-label"><?php echo $lang === 'sw' ? 'Jumla ya Maagizo' : 'Total Orders'; ?></div>
                        <div class="summary-value"><?php echo $summary['total_orders'] ?? 0; ?></div>
                    </div>
                    <div class="summary-card">
                        <div class="summary-label"><?php echo $lang === 'sw' ? 'Mapato Jumla' : 'Total Revenue'; ?></div>
                        <div class="summary-value" style="color: var(--primary);"><?php echo formatCurrency($summary['total_revenue'] ?? 0); ?></div>
                    </div>
                    <div class="summary-card">
                        <div class="summary-label"><?php echo $lang === 'sw' ? 'Wastani wa Agizo' : 'Average Order'; ?></div>
                        <div class="summary-value"><?php echo formatCurrency($summary['avg_order_value'] ?? 0); ?></div>
                    </div>
                </div>

                <!-- Payment Methods -->
                <div class="card" style="margin-bottom: 1.5rem;">
                    <div class="card-header">
                        <h3><?php echo $lang === 'sw' ? 'Njia za Malipo' : 'Payment Methods'; ?></h3>
                    </div>
                    <div class="card-body">
                        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 1rem;">
                            <?php foreach ($paymentBreakdown as $payment): ?>
                            <div style="text-align: center; padding: 1rem; background: var(--gray-50); border-radius: var(--radius-md);">
                                <div style="font-weight: 600; color: var(--gray-900); text-transform: uppercase;">
                                    <?php echo $payment['payment_method'] ?: ($lang === 'sw' ? 'Haijalipiwa' : 'Unpaid'); ?>
                                </div>
                                <div style="font-size: 1.25rem; font-weight: 700; color: var(--primary); margin: 0.5rem 0;">
                                    <?php echo formatCurrency($payment['total']); ?>
                                </div>
                                <div style="font-size: 0.875rem; color: var(--gray-500);">
                                    <?php echo $payment['count']; ?> <?php echo $lang === 'sw' ? 'maagizo' : 'orders'; ?>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>

                <!-- Popular Items -->
                <div class="card" style="margin-bottom: 1.5rem;">
                    <div class="card-header">
                        <h3><?php echo $lang === 'sw' ? 'Vyakula Vinavyouzwa Zaidi' : 'Most Popular Items'; ?></h3>
                    </div>
                    <div class="card-body" style="padding: 0;">
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th><?php echo $lang === 'sw' ? 'Chakula' : 'Food Item'; ?></th>
                                    <th style="text-align: center;"><?php echo $lang === 'sw' ? 'Kiasi' : 'Quantity'; ?></th>
                                    <th style="text-align: right;"><?php echo $lang === 'sw' ? 'Mapato' : 'Revenue'; ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($popularItems as $item): ?>
                                <tr>
                                    <td>
                                        <div style="font-weight: 600;"><?php echo htmlspecialchars($item['name']); ?></div>
                                        <div style="font-size: 0.875rem; color: var(--gray-500);"><?php echo htmlspecialchars($item['name_sw']); ?></div>
                                    </td>
                                    <td style="text-align: center; font-weight: 600;"><?php echo $item['total_sold']; ?></td>
                                    <td style="text-align: right; font-weight: 600; color: var(--primary);">
                                        <?php echo formatCurrency($item['total_revenue']); ?>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Daily Breakdown -->
                <div class="card">
                    <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
                        <h3><?php echo $lang === 'sw' ? 'Mauzo ya Kila Siku' : 'Daily Sales'; ?></h3>
                        <a href="?export=1&period=<?php echo $period; ?>" class="export-btn" onclick="alert('<?php echo $lang === 'sw' ? 'Kusoma PDF/Excel bado haijatekelezwa' : 'PDF/Excel export not yet implemented'; ?>'); return false;">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width: 16px; height: 16px;">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                            </svg>
                            <?php echo $lang === 'sw' ? 'Pakua Ripoti' : 'Download Report'; ?>
                        </a>
                    </div>
                    <div class="card-body" style="padding: 0;">
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th><?php echo $lang === 'sw' ? 'Tarehe' : 'Date'; ?></th>
                                    <th style="text-align: center;"><?php echo $lang === 'sw' ? 'Maagizo' : 'Orders'; ?></th>
                                    <th style="text-align: center;"><?php echo $lang === 'sw' ? 'Vitu' : 'Items'; ?></th>
                                    <th style="text-align: right;"><?php echo $lang === 'sw' ? 'Mapato' : 'Revenue'; ?></th>
                                    <th style="text-align: right;"><?php echo $lang === 'sw' ? 'Wastani' : 'Average'; ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($salesData as $day): ?>
                                <tr>
                                    <td><?php echo date('D, d M Y', strtotime($day['sale_date'])); ?></td>
                                    <td style="text-align: center; font-weight: 600;"><?php echo $day['total_orders']; ?></td>
                                    <td style="text-align: center;"><?php echo $day['total_items'] ?? 0; ?></td>
                                    <td style="text-align: right; font-weight: 600; color: var(--primary);">
                                        <?php echo formatCurrency($day['total_revenue']); ?>
                                    </td>
                                    <td style="text-align: right;">
                                        <?php echo formatCurrency($day['avg_order_value']); ?>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </main>
    </div>
</body>
</html>
