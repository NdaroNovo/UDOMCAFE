<?php
require_once 'config.php';

// Check if logged in as cook or manager
if (!isLoggedIn() || (!hasRole('cook') && !hasRole('manager') && !hasRole('admin'))) {
    redirect('login.php?role=cook');
}

// Get pending and preparing orders
$stmt = $conn->query("SELECT o.*, 
    GROUP_CONCAT(CONCAT(oi.quantity, 'x ', fi.name, ' (', fi.name_sw, ')') SEPARATOR ' | ') as items_list,
    SUM(oi.quantity) as total_items
    FROM orders o 
    LEFT JOIN order_items oi ON o.id = oi.order_id 
    LEFT JOIN food_items fi ON oi.food_item_id = fi.id 
    WHERE o.status IN ('pending', 'preparing') 
    AND DATE(o.created_at) = CURDATE()
    GROUP BY o.id 
    ORDER BY 
        CASE o.status 
            WHEN 'preparing' THEN 1 
            WHEN 'pending' THEN 2 
        END,
        o.created_at ASC");
$orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Get low stock alerts
$stmt = $conn->query("SELECT * FROM food_items WHERE stock_status IN ('low', 'finished') AND is_active = 1 ORDER BY stock_status DESC, name");
$lowStock = $stmt->fetchAll(PDO::FETCH_ASSOC);

$lang = getLang();
?>
<!DOCTYPE html>
<html lang="<?php echo $lang; ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo APP_NAME; ?> - <?php __('kitchen'); ?></title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <meta http-equiv="refresh" content="15">
</head>
<body>
    <div class="app-container">
        <!-- Header -->
        <header class="app-header">
            <h1>
                <svg class="logo-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"/>
                </svg>
                <?php __('kitchen'); ?>
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
                <!-- Low Stock Alerts -->
                <?php if (!empty($lowStock)): ?>
                <div class="alert alert-warning" style="margin-bottom: 1.5rem;">
                    <svg class="alert-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                    <div>
                        <strong><?php echo $lang === 'sw' ? 'Arifa za Mzigo Mdogo!' : 'Low Stock Alerts!'; ?></strong>
                        <ul style="margin-top: 0.5rem; margin-bottom: 0; padding-left: 1rem;">
                            <?php foreach ($lowStock as $item): ?>
                            <li>
                                <?php echo htmlspecialchars($item['name']); ?> / <?php echo htmlspecialchars($item['name_sw']); ?> 
                                - <strong><?php echo $item['stock_quantity']; ?> <?php echo $lang === 'sw' ? 'zilizobaki' : 'remaining'; ?></strong>
                            </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </div>
                <?php endif; ?>

                <!-- Active Orders Count -->
                <div class="stats-grid" style="grid-template-columns: repeat(2, 1fr); margin-bottom: 1.5rem;">
                    <div class="stat-card">
                        <div class="stat-icon preparing">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div class="stat-label"><?php echo $lang === 'sw' ? 'Inasubiri' : 'Pending'; ?></div>
                        <div class="stat-value"><?php echo count(array_filter($orders, fn($o) => $o['status'] === 'pending')); ?></div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon orders">
                            <div class="stat-icon preparing">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.384-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/>
                            </svg>
                        </div>
                        <div class="stat-label"><?php echo $lang === 'sw' ? 'Inaandaliwa' : 'Preparing'; ?></div>
                        <div class="stat-value"><?php echo count(array_filter($orders, fn($o) => $o['status'] === 'preparing')); ?></div>
                    </div>
                </div>

                <!-- Orders Queue -->
                <h2 style="margin-bottom: 1rem; display: flex; align-items: center; gap: 0.5rem;">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width: 24px; height: 24px; color: var(--primary);">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                    <?php echo $lang === 'sw' ? 'Foleni ya Maagizo' : 'Orders Queue'; ?>
                </h2>

                <div class="orders-list">
                    <?php if (empty($orders)): ?>
                    <div class="empty-state">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <h3><?php echo $lang === 'sw' ? 'Hakuna Maagizo' : 'No Orders'; ?></h3>
                        <p><?php echo $lang === 'sw' ? 'Maagizo yote yamekamilika' : 'All orders completed'; ?></p>
                    </div>
                    <?php else: ?>
                        <?php foreach ($orders as $order): ?>
                        <div class="order-card" style="border-left: 4px solid <?php echo $order['status'] === 'preparing' ? 'var(--warning)' : 'var(--info)'; ?>;">
                            <div class="order-header">
                                <div>
                                    <span class="order-number">#<?php echo htmlspecialchars(substr($order['order_number'], -4)); ?></span>
                                    <span class="order-time" style="margin-left: 0.5rem;">
                                        <?php echo date('H:i', strtotime($order['created_at'])); ?>
                                    </span>
                                    <!-- Estimated Time -->
                                    <span style="margin-left: 0.5rem; background: var(--primary-light); color: var(--primary-dark); padding: 0.25rem 0.5rem; border-radius: var(--radius-full); font-size: 0.75rem; font-weight: 600;">
                                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width: 12px; height: 12px; display: inline; vertical-align: middle; margin-right: 2px;">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        <?php 
                                        // Calculate estimated time
                                        $baseTime = 5 + ($order['total_items'] * 2);
                                        $waitTime = ($order['status'] === 'preparing') ? max(2, ceil($baseTime * 0.5)) : $baseTime;
                                        echo '~' . $waitTime . ' ' . ($lang === 'sw' ? 'min' : 'min');
                                        ?>
                                    </span>
                                </div>
                                <?php echo getOrderStatusBadge($order['status'], $lang); ?>
                            </div>
                            <div class="order-body">
                                <div style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 0.75rem;">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width: 20px; height: 20px; color: var(--gray-400);">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                    </svg>
                                    <span style="font-weight: 600; color: var(--gray-800); font-size: 1.125rem;">
                                        <?php echo htmlspecialchars($order['customer_name']); ?>
                                    </span>
                                </div>
                                
                                <div style="background: var(--gray-50); padding: 1rem; border-radius: var(--radius-md); margin-bottom: 0.75rem;">
                                    <div style="font-weight: 600; margin-bottom: 0.5rem; color: var(--gray-700);">
                                        <?php echo $lang === 'sw' ? 'Vitu:' : 'Items:'; ?>
                                    </div>
                                    <div style="font-size: 1rem; line-height: 1.6;">
                                        <?php echo htmlspecialchars($order['items_list']); ?>
                                    </div>
                                </div>

                                <div style="display: flex; align-items: center; gap: 0.5rem; color: var(--gray-500); font-size: 0.875rem;">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width: 16px; height: 16px;">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4h13M3 8h9m-9 4h6m4 0l4-4m0 0l4 4m-4-4v12"/>
                                    </svg>
                                    <?php echo $order['total_items']; ?> <?php echo $lang === 'sw' ? 'vitu' : 'items'; ?>
                                </div>
                            </div>
                            <div class="order-footer">
                                <span class="order-total"><?php echo formatCurrency($order['total_amount']); ?></span>
                                <div class="order-actions">
                                    <?php if ($order['status'] === 'pending'): ?>
                                    <form action="update-order.php" method="POST" style="display: inline;">
                                        <input type="hidden" name="order_id" value="<?php echo $order['id']; ?>">
                                        <input type="hidden" name="action" value="prepare">
                                        <input type="hidden" name="redirect" value="kitchen">
                                        <button type="submit" class="btn btn-primary btn-lg" style="padding: 0.75rem 2rem;">
                                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width: 20px; height: 20px;">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                            <?php echo $lang === 'sw' ? 'ANZA KUPIKA' : 'START COOKING'; ?>
                                        </button>
                                    </form>
                                    <?php elseif ($order['status'] === 'preparing'): ?>
                                    <form action="update-order.php" method="POST" style="display: inline;">
                                        <input type="hidden" name="order_id" value="<?php echo $order['id']; ?>">
                                        <input type="hidden" name="action" value="ready">
                                        <input type="hidden" name="redirect" value="kitchen">
                                        <button type="submit" class="btn btn-success btn-lg" style="padding: 0.75rem 2rem;">
                                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width: 20px; height: 20px;">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                            </svg>
                                            <?php echo $lang === 'sw' ? 'CHAKULA TAYARI' : 'FOOD READY'; ?>
                                        </button>
                                    </form>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </main>
    </div>
</body>
</html>
