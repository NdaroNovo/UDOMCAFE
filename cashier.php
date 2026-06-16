<?php
require_once 'config.php';

// Check if logged in as cashier or manager
if (!isLoggedIn() || (!hasRole('cashier') && !hasRole('manager') && !hasRole('admin'))) {
    redirect('login.php?role=cashier');
}

// Get orders
$status = $_GET['status'] ?? 'all';
$params = [];

$sql = "SELECT o.*, 
        GROUP_CONCAT(CONCAT(oi.quantity, 'x ', fi.name) SEPARATOR ', ') as items_list,
        COUNT(oi.id) as item_count
        FROM orders o 
        LEFT JOIN order_items oi ON o.id = oi.order_id 
        LEFT JOIN food_items fi ON oi.food_item_id = fi.id 
        WHERE DATE(o.created_at) = CURDATE()";

if ($status !== 'all') {
    $sql .= " AND o.status = ?";
    $params[] = $status;
}

$sql .= " GROUP BY o.id ORDER BY o.created_at DESC";

$stmt = $conn->prepare($sql);
$stmt->execute($params);
$orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

$lang = getLang();
?>
<!DOCTYPE html>
<html lang="<?php echo $lang; ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo APP_NAME; ?> - <?php __('cashier'); ?></title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <meta http-equiv="refresh" content="30">
</head>
<body>
    <div class="app-container">
        <!-- Header -->
        <header class="app-header">
            <h1>
                <svg class="logo-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                </svg>
                <?php __('cashier'); ?>
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
                <!-- Filter Tabs -->
                <div class="tabs">
                    <a href="?status=all" class="tab-btn <?php echo $status === 'all' ? 'active' : ''; ?>">
                        <?php echo $lang === 'sw' ? 'Vyote' : 'All'; ?>
                    </a>
                    <a href="?status=pending" class="tab-btn <?php echo $status === 'pending' ? 'active' : ''; ?>">
                        <?php __('pending'); ?>
                    </a>
                    <a href="?status=preparing" class="tab-btn <?php echo $status === 'preparing' ? 'active' : ''; ?>">
                        <?php echo $lang === 'sw' ? 'Inaandaliwa' : 'Preparing'; ?>
                    </a>
                    <a href="?status=ready" class="tab-btn <?php echo $status === 'ready' ? 'active' : ''; ?>">
                        <?php __('ready'); ?>
                    </a>
                    <a href="?status=completed" class="tab-btn <?php echo $status === 'completed' ? 'active' : ''; ?>">
                        <?php __('completed'); ?>
                    </a>
                </div>

                <!-- Orders Count -->
                <div class="stat-card" style="margin-bottom: 1.5rem;">
                    <div class="stat-label"><?php echo $lang === 'sw' ? 'Maagizo ya Leo' : 'Today\'s Orders'; ?></div>
                    <div class="stat-value"><?php echo count($orders); ?></div>
                </div>

                <!-- Orders List -->
                <div class="orders-list">
                    <?php if (empty($orders)): ?>
                    <div class="empty-state">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                        <h3><?php echo $lang === 'sw' ? 'Hakuna Maagizo' : 'No Orders'; ?></h3>
                        <p><?php echo $lang === 'sw' ? 'Maagizo yataonekana hapa' : 'Orders will appear here'; ?></p>
                    </div>
                    <?php else: ?>
                        <?php foreach ($orders as $order): ?>
                        <div class="order-card">
                            <div class="order-header">
                                <div>
                                    <span class="order-number"><?php echo htmlspecialchars($order['order_number']); ?></span>
                                    <span class="order-time" style="margin-left: 0.5rem;">
                                        <?php echo date('H:i', strtotime($order['created_at'])); ?>
                                    </span>
                                </div>
                                <?php echo getOrderStatusBadge($order['status'], $lang); ?>
                            </div>
                            <div class="order-body">
                                <div class="order-customer"><?php echo htmlspecialchars($order['customer_name']); ?></div>
                                <div class="order-items">
                                    <?php 
                                    $items = explode(', ', $order['items_list']);
                                    foreach ($items as $item): 
                                    ?>
                                    <div class="order-item">
                                        <span class="order-item-name"><?php echo htmlspecialchars($item); ?></span>
                                    </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                            <div class="order-footer">
                                <span class="order-total"><?php echo formatCurrency($order['total_amount']); ?></span>
                                <div class="order-actions">
                                    <?php if ($order['status'] === 'pending'): ?>
                                    <form action="update-order.php" method="POST" style="display: inline;">
                                        <input type="hidden" name="order_id" value="<?php echo $order['id']; ?>">
                                        <input type="hidden" name="action" value="prepare">
                                        <button type="submit" class="btn btn-sm btn-secondary">
                                            <?php echo $lang === 'sw' ? 'Anza' : 'Start'; ?>
                                        </button>
                                    </form>
                                    <?php elseif ($order['status'] === 'preparing'): ?>
                                    <form action="update-order.php" method="POST" style="display: inline;">
                                        <input type="hidden" name="order_id" value="<?php echo $order['id']; ?>">
                                        <input type="hidden" name="action" value="ready">
                                        <button type="submit" class="btn btn-sm btn-success">
                                            <?php __('mark_ready'); ?>
                                        </button>
                                    </form>
                                    <?php elseif ($order['status'] === 'ready'): ?>
                                    <form action="update-order.php" method="POST" style="display: inline;">
                                        <input type="hidden" name="order_id" value="<?php echo $order['id']; ?>">
                                        <input type="hidden" name="action" value="complete">
                                        <button type="submit" class="btn btn-sm btn-primary">
                                            <?php echo $lang === 'sw' ? 'Maliza' : 'Complete'; ?>
                                        </button>
                                    </form>
                                    <?php endif; ?>
                                    
                                    <?php if ($order['payment_status'] === 'unpaid' && $order['status'] !== 'cancelled'): ?>
                                    <form action="update-order.php" method="POST" style="display: inline;">
                                        <input type="hidden" name="order_id" value="<?php echo $order['id']; ?>">
                                        <input type="hidden" name="action" value="pay">
                                        <button type="submit" class="btn btn-sm btn-outline">
                                            <?php echo CURRENCY; ?> ✓
                                        </button>
                                    </form>
                                    <?php endif; ?>
                                    
                                    <?php if ($order['status'] !== 'completed' && $order['status'] !== 'cancelled'): ?>
                                    <form action="update-order.php" method="POST" style="display: inline;" onsubmit="return confirm('<?php echo $lang === 'sw' ? 'Una uhakika?' : 'Are you sure?'; ?>');">
                                        <input type="hidden" name="order_id" value="<?php echo $order['id']; ?>">
                                        <input type="hidden" name="action" value="cancel">
                                        <button type="submit" class="btn btn-sm btn-danger">
                                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width: 16px; height: 16px;">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                            </svg>
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
