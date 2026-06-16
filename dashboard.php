<?php
require_once 'config.php';

// Check if logged in as manager or admin
if (!isLoggedIn() || (!hasRole('manager') && !hasRole('admin'))) {
    redirect('login.php?role=manager');
}

// Today's stats
$today = date('Y-m-d');
$stmt = $conn->prepare("SELECT * FROM daily_sales WHERE sale_date = ?");
$stmt->execute([$today]);
$todayStats = $stmt->fetch(PDO::FETCH_ASSOC);

// All food items with stock
$stmt = $conn->query("SELECT * FROM food_items WHERE is_active = 1 ORDER BY category, name");
$foodItems = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Today's orders
$stmt = $conn->query("SELECT o.*, GROUP_CONCAT(fi.name SEPARATOR ', ') as items 
    FROM orders o 
    LEFT JOIN order_items oi ON o.id = oi.order_id 
    LEFT JOIN food_items fi ON oi.food_item_id = fi.id 
    WHERE DATE(o.created_at) = CURDATE() 
    GROUP BY o.id ORDER BY o.created_at DESC LIMIT 10");
$recentOrders = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Low stock items
$stmt = $conn->query("SELECT * FROM food_items WHERE stock_status IN ('low', 'finished') AND is_active = 1");
$lowStockItems = $stmt->fetchAll(PDO::FETCH_ASSOC);

$lang = getLang();
?>
<!DOCTYPE html>
<html lang="<?php echo $lang; ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo APP_NAME; ?> - <?php __('dashboard'); ?></title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
    <div class="app-container">
        <!-- Header -->
        <header class="app-header">
            <h1>
                <svg class="logo-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                </svg>
                <?php __('dashboard'); ?>
            </h1>
            <div class="header-actions">
                <div class="lang-toggle">
                    <button class="lang-btn <?php echo $lang === 'en' ? 'active' : ''; ?>" onclick="location.href='set-language.php?lang=en&redirect=dashboard.php'">EN</button>
                    <button class="lang-btn <?php echo $lang === 'sw' ? 'active' : ''; ?>" onclick="location.href='set-language.php?lang=sw&redirect=dashboard.php'">SW</button>
                </div>
                <a href="logout.php" class="btn btn-sm btn-danger"><?php __('logout'); ?></a>
            </div>
        </header>

        <!-- Main Content -->
        <main class="main-content">
            <div class="page-content">
                <!-- Navigation Cards -->
                <div class="menu-grid" style="margin-bottom: 1.5rem;">
                    <a href="cashier.php" class="card" style="text-decoration: none; color: inherit; text-align: center; padding: 1.5rem;">
                        <div style="width: 60px; height: 60px; background: #dbeafe; border-radius: var(--radius-lg); display: flex; align-items: center; justify-content: center; margin: 0 auto 1rem; color: var(--info);">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width: 32px; height: 32px;">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                            </svg>
                        </div>
                        <h3><?php __('cashier'); ?></h3>
                        <p style="color: var(--gray-500); font-size: 0.875rem;"><?php echo $lang === 'sw' ? 'Simamia maagizo' : 'Manage orders'; ?></p>
                    </a>
                    <a href="kitchen.php" class="card" style="text-decoration: none; color: inherit; text-align: center; padding: 1.5rem;">
                        <div style="width: 60px; height: 60px; background: #fef3c7; border-radius: var(--radius-lg); display: flex; align-items: center; justify-content: center; margin: 0 auto 1rem; color: var(--warning);">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width: 32px; height: 32px;">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"/>
                            </svg>
                        </div>
                        <h3><?php __('kitchen'); ?></h3>
                        <p style="color: var(--gray-500); font-size: 0.875rem;"><?php echo $lang === 'sw' ? 'Angalia maagizo' : 'View orders'; ?></p>
                    </a>
                    <a href="sales-report.php" class="card" style="text-decoration: none; color: inherit; text-align: center; padding: 1.5rem;">
                        <div style="width: 60px; height: 60px; background: var(--primary-light); border-radius: var(--radius-lg); display: flex; align-items: center; justify-content: center; margin: 0 auto 1rem; color: var(--primary);">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width: 32px; height: 32px;">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                        </div>
                        <h3><?php echo $lang === 'sw' ? 'Ripoti ya Mauzo' : 'Sales Report'; ?></h3>
                        <p style="color: var(--gray-500); font-size: 0.875rem;"><?php echo $lang === 'sw' ? 'Angalia takwimu' : 'View reports'; ?></p>
                    </a>
                    <a href="waiting-time.php" class="card" style="text-decoration: none; color: inherit; text-align: center; padding: 1.5rem;">
                        <div style="width: 60px; height: 60px; background: #fce7f3; border-radius: var(--radius-lg); display: flex; align-items: center; justify-content: center; margin: 0 auto 1rem; color: #ec4899;">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width: 32px; height: 32px;">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <h3><?php echo $lang === 'sw' ? 'Muda wa Kungoja' : 'Waiting Time'; ?></h3>
                        <p style="color: var(--gray-500); font-size: 0.875rem;"><?php echo $lang === 'sw' ? 'Hali ya foleni' : 'Queue status'; ?></p>
                    </a>
                </div>

                <!-- Today's Stats -->
                <h2 style="margin-bottom: 1rem;"><?php echo $lang === 'sw' ? 'Takwimu za Leo' : 'Today\'s Stats'; ?></h2>
                <div class="stats-grid" style="margin-bottom: 1.5rem;">
                    <div class="stat-card">
                        <div class="stat-icon orders">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                            </svg>
                        </div>
                        <div class="stat-label"><?php __('total_orders'); ?></div>
                        <div class="stat-value"><?php echo $todayStats['total_orders'] ?? 0; ?></div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon revenue">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div class="stat-label"><?php __('revenue'); ?></div>
                        <div class="stat-value"><?php echo formatCurrency($todayStats['total_revenue'] ?? 0); ?></div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon stock">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                            </svg>
                        </div>
                        <div class="stat-label"><?php echo $lang === 'sw' ? 'Vitu Vilivyouzwa' : 'Items Sold'; ?></div>
                        <div class="stat-value"><?php echo $todayStats['total_items_sold'] ?? 0; ?></div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon feedback">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                            </svg>
                        </div>
                        <div class="stat-label"><?php echo $lang === 'sw' ? 'Wastani wa Agizo' : 'Avg Order'; ?></div>
                        <div class="stat-value">
                            <?php 
                            $avg = ($todayStats['total_orders'] ?? 0) > 0 
                                ? ($todayStats['total_revenue'] / $todayStats['total_orders']) 
                                : 0;
                            echo formatCurrency($avg);
                            ?>
                        </div>
                    </div>
                </div>

                <!-- Stock Management -->
                <div class="card" style="margin-bottom: 1.5rem;">
                    <div class="card-header">
                        <h3 style="display: flex; align-items: center; gap: 0.5rem;">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width: 24px; height: 24px; color: var(--warning);">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                            </svg>
                            <?php echo $lang === 'sw' ? 'Simamia Stock' : 'Manage Stock'; ?>
                        </h3>
                    </div>
                    <div class="card-body" style="padding: 0;">
                        <div style="overflow-x: auto;">
                            <table style="width: 100%; border-collapse: collapse;">
                                <thead>
                                    <tr style="background: var(--gray-50);">
                                        <th style="padding: 1rem; text-align: left; font-size: 0.875rem; color: var(--gray-600);"><?php echo $lang === 'sw' ? 'Chakula' : 'Food'; ?></th>
                                        <th style="padding: 1rem; text-align: center; font-size: 0.875rem; color: var(--gray-600);"><?php echo $lang === 'sw' ? 'Bei' : 'Price'; ?></th>
                                        <th style="padding: 1rem; text-align: center; font-size: 0.875rem; color: var(--gray-600);"><?php __('stock'); ?></th>
                                        <th style="padding: 1rem; text-align: center; font-size: 0.875rem; color: var(--gray-600);"><?php __('status'); ?></th>
                                        <th style="padding: 1rem; text-align: right; font-size: 0.875rem; color: var(--gray-600);"><?php echo $lang === 'sw' ? 'Ongeza' : 'Add'; ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($foodItems as $item): ?>
                                    <tr style="border-top: 1px solid var(--gray-100);">
                                        <td style="padding: 1rem;">
                                            <div style="font-weight: 600;"><?php echo htmlspecialchars($item['name']); ?></div>
                                            <div style="font-size: 0.875rem; color: var(--gray-500);"><?php echo htmlspecialchars($item['name_sw']); ?></div>
                                        </td>
                                        <td style="padding: 1rem; text-align: center;"><?php echo formatCurrency($item['price']); ?></td>
                                        <td style="padding: 1rem; text-align: center; font-weight: 600;">
                                            <span style="color: <?php echo $item['stock_quantity'] <= $item['low_stock_threshold'] ? 'var(--danger)' : 'inherit'; ?>">
                                                <?php echo $item['stock_quantity']; ?>
                                            </span>
                                        </td>
                                        <td style="padding: 1rem; text-align: center;">
                                            <?php echo getStockStatusBadge($item['stock_status'], $lang); ?>
                                        </td>
                                        <td style="padding: 1rem; text-align: right;">
                                            <form action="update-stock.php" method="POST" style="display: flex; gap: 0.5rem; justify-content: flex-end;">
                                                <input type="hidden" name="food_id" value="<?php echo $item['id']; ?>">
                                                <input type="number" name="quantity" class="form-input" style="width: 80px; padding: 0.5rem;" min="1" value="10" required>
                                                <button type="submit" class="btn btn-sm btn-success">
                                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width: 16px; height: 16px;">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                                    </svg>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Recent Orders -->
                <h2 style="margin-bottom: 1rem;"><?php echo $lang === 'sw' ? 'Maagizo ya Hivi Karibuni' : 'Recent Orders'; ?></h2>
                <div class="orders-list" style="margin-bottom: 2rem;">
                    <?php foreach ($recentOrders as $order): ?>
                    <div class="order-card">
                        <div class="order-header">
                            <span class="order-number"><?php echo htmlspecialchars($order['order_number']); ?></span>
                            <?php echo getOrderStatusBadge($order['status'], $lang); ?>
                        </div>
                        <div class="order-body">
                            <div class="order-customer"><?php echo htmlspecialchars($order['customer_name']); ?></div>
                            <div style="font-size: 0.875rem; color: var(--gray-500); margin-bottom: 0.5rem;">
                                <?php echo htmlspecialchars($order['items']); ?>
                            </div>
                        </div>
                        <div class="order-footer">
                            <span class="order-total"><?php echo formatCurrency($order['total_amount']); ?></span>
                            <span style="font-size: 0.875rem; color: var(--gray-500);">
                                <?php echo date('H:i', strtotime($order['created_at'])); ?>
                            </span>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </main>
    </div>
</body>
</html>
