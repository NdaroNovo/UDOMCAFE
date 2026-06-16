<?php
require_once 'config.php';

// This is a simple lookup page where students can check their order status
// In a real app, this might use session or phone number lookup

$lang = getLang();
?>
<!DOCTYPE html>
<html lang="<?php echo $lang; ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo APP_NAME; ?> - <?php __('my_order'); ?></title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
    <div class="app-container">
        <!-- Header -->
        <header class="app-header">
            <h1>
                <svg class="logo-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                </svg>
                <?php __('my_order'); ?>
            </h1>
            <div class="header-actions">
                <div class="lang-toggle">
                    <button class="lang-btn <?php echo $lang === 'en' ? 'active' : ''; ?>" onclick="location.href='set-language.php?lang=en&redirect=my-order.php'">EN</button>
                    <button class="lang-btn <?php echo $lang === 'sw' ? 'active' : ''; ?>" onclick="location.href='set-language.php?lang=sw&redirect=my-order.php'">SW</button>
                </div>
            </div>
        </header>

        <!-- Main Content -->
        <main class="main-content">
            <div class="page-content">
                <div class="empty-state">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                    </svg>
                    <h3><?php echo $lang === 'sw' ? 'Angalia Hali ya Agizo' : 'Check Order Status'; ?></h3>
                    <p><?php echo $lang === 'sw' ? 'Weka namba ya agizo lako kuhakiki hali yake' : 'Enter your order number to check status'; ?></p>
                </div>
                
                <div class="card">
                    <div class="card-body">
                        <form id="checkOrderForm">
                            <div class="form-group">
                                <label class="form-label"><?php echo $lang === 'sw' ? 'Namba ya Agizo' : 'Order Number'; ?></label>
                                <input type="text" id="orderNumber" class="form-input" placeholder="ORD20250615-XXXX" style="text-transform: uppercase;">
                            </div>
                            <button type="submit" class="btn btn-primary btn-lg btn-block">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width: 20px; height: 20px;">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                </svg>
                                <?php echo $lang === 'sw' ? 'Tafuta Agizo' : 'Find Order'; ?>
                            </button>
                        </form>
                    </div>
                </div>
                
                <!-- Order Status Result -->
                <div id="orderResult" style="margin-top: 1.5rem; display: none;"></div>
                
                <!-- Tip -->
                <div class="alert alert-info" style="margin-top: 2rem;">
                    <svg class="alert-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span><?php echo $lang === 'sw' ? 'Weka jina lako wakati wa kuagiza ili kurahisisha kutafuta agizo lako.' : 'Use your name when ordering to easily track your order.'; ?></span>
                </div>
            </div>
        </main>

        <!-- Bottom Navigation -->
        <nav class="bottom-nav">
            <a href="index.php" class="nav-item">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
                <?php __('menu'); ?>
            </a>
            <a href="my-order.php" class="nav-item active">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                </svg>
                <?php __('my_order'); ?>
            </a>
            <a href="feedback.php" class="nav-item">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/>
                </svg>
                <?php __('feedback'); ?>
            </a>
        </nav>
    </div>
    
    <script>
        document.getElementById('checkOrderForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const orderNum = document.getElementById('orderNumber').value.trim();
            if (orderNum) {
                // In a real app, this would make an AJAX call to check order status
                // For now, redirect to cashier page which shows all orders
                alert('<?php echo $lang === 'sw' ? 'Tafadhali muulize mhudumu kuhusu agizo lako.' : 'Please ask a staff member about your order.'; ?>');
            }
        });
    </script>
</body>
</html>
