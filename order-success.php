<?php
require_once 'config.php';

$order = $_SESSION['last_order'] ?? null;
if (!$order) {
    redirect('index.php');
}

// Clear the order from session
unset($_SESSION['last_order']);

$lang = getLang();
?>
<!DOCTYPE html>
<html lang="<?php echo $lang; ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo APP_NAME; ?> - <?php echo $lang === 'sw' ? 'Agizo Limewekwa' : 'Order Placed'; ?></title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
    <div class="app-container">
        <div class="success-container">
            <div class="success-icon">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/>
                </svg>
            </div>
            
            <h2><?php __('thank_you'); ?></h2>
            <p><?php __('order_placed'); ?></p>
            
            <div class="order-number-display">
                <?php echo htmlspecialchars($order['order_number']); ?>
            </div>
            
            <p style="color: var(--gray-500); margin-bottom: 0.5rem;">
                <?php echo $lang === 'sw' ? 'Jina:' : 'Name:'; ?> 
                <strong><?php echo htmlspecialchars($order['customer_name']); ?></strong>
            </p>
            <p style="color: var(--gray-500); margin-bottom: 1.5rem;">
                <?php echo $lang === 'sw' ? 'Jumla:' : 'Total:'; ?> 
                <strong style="color: var(--primary);"><?php echo formatCurrency($order['total']); ?></strong>
            </p>
            
            <!-- Estimated Time -->
            <div style="background: linear-gradient(135deg, var(--primary-light) 0%, #ffffff 100%); border: 2px solid var(--primary); border-radius: var(--radius-lg); padding: 1.5rem; margin: 1.5rem 0; max-width: 400px; width: 100%;">
                <div style="display: flex; align-items: center; justify-content: center; gap: 0.5rem; margin-bottom: 0.5rem;">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width: 24px; height: 24px; color: var(--primary);">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span style="color: var(--primary-dark); font-weight: 600;">
                        <?php echo $lang === 'sw' ? 'Muda Unaokadiriwa' : 'Estimated Time'; ?>
                    </span>
                </div>
                <div style="font-size: 2rem; font-weight: 700; color: var(--primary);">
                    ~15-20 <?php echo $lang === 'sw' ? 'dakika' : 'minutes'; ?>
                </div>
                <p style="color: var(--gray-500); font-size: 0.875rem; margin: 0.5rem 0 0;">
                    <?php echo $lang === 'sw' 
                        ? 'Muda unaweza kubadilika kulingana na idadi ya maagizo' 
                        : 'Time may vary depending on order volume'; ?>
                </p>
            </div>

            <!-- Action Buttons -->
            <div style="display: flex; flex-direction: column; gap: 1rem; max-width: 400px; width: 100%;">
                <a href="waiting-time.php?order_id=<?php echo $order['order_number'] ?? 0; ?>" class="btn btn-primary btn-lg" style="justify-content: center;">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width: 20px; height: 20px;">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <?php echo $lang === 'sw' ? 'Fuatilia Muda' : 'Track Waiting Time'; ?>
                </a>
                
                <a href="payment.php?order_id=<?php echo $order['id'] ?? 0; ?>" class="btn btn-success btn-lg" style="justify-content: center;">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width: 20px; height: 20px;">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a1 1 0 11-2 0 1 1 0 012 0z"/>
                    </svg>
                    <?php echo $lang === 'sw' ? 'Lipa Sasa' : 'Pay Now'; ?> (M-Pesa/Tigo/Airtel)
                </a>
                
                <a href="index.php" class="btn btn-secondary btn-lg" style="justify-content: center;">
                    <?php __('back_to_menu'); ?>
                </a>
            </div>
            
            <!-- Order Summary -->
            <div class="card" style="margin-top: 2rem; width: 100%; max-width: 400px;">
                <div class="card-header">
                    <h3><?php echo $lang === 'sw' ? 'Muhtasari wa Agizo' : 'Order Summary'; ?></h3>
                </div>
                <div class="card-body">
                    <?php foreach ($order['items'] as $item): ?>
                    <div style="display: flex; justify-content: space-between; padding: 0.5rem 0; border-bottom: 1px solid var(--gray-100);">
                        <span><?php echo htmlspecialchars($item['name']); ?> x <?php echo $item['quantity']; ?></span>
                        <span style="font-weight: 600;"><?php echo formatCurrency($item['price'] * $item['quantity']); ?></span>
                    </div>
                    <?php endforeach; ?>
                    <div style="display: flex; justify-content: space-between; padding-top: 1rem; margin-top: 0.5rem; border-top: 2px solid var(--gray-200);">
                        <span style="font-weight: 700;"><?php __('total'); ?></span>
                        <span style="font-weight: 700; color: var(--primary);"><?php echo formatCurrency($order['total']); ?></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
