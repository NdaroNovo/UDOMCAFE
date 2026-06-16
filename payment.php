<?php
/**
 * Mobile Payment Integration for CIVE Cafeteria
 * Supports: M-Pesa, Tigo Pesa, Airtel Money
 */

require_once 'config.php';

// Get order details from session or query
$orderId = $_GET['order_id'] ?? 0;

if (!$orderId) {
    redirect('index.php');
}

// Fetch order details
$stmt = $conn->prepare("SELECT * FROM orders WHERE id = ?");
$stmt->execute([$orderId]);
$order = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$order) {
    redirect('index.php');
}

$lang = getLang();

// Handle payment submission
$paymentStatus = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $paymentMethod = $_POST['payment_method'] ?? '';
    $phoneNumber = sanitizeInput($_POST['phone_number'] ?? '');
    
    if (empty($paymentMethod) || empty($phoneNumber)) {
        $error = $lang === 'sw' ? 'Tafadhali chagua njia ya kulipa na weka namba ya simu' : 'Please select payment method and enter phone number';
    } else {
        // In a real implementation, this would integrate with payment APIs
        // For demo purposes, we'll simulate successful payment
        
        // Update order payment status
        $stmt = $conn->prepare("UPDATE orders SET payment_status = 'paid', payment_method = ? WHERE id = ?");
        $stmt->execute([$paymentMethod, $orderId]);
        
        $paymentStatus = 'success';
        
        // Store in session for confirmation
        $_SESSION['payment_success'] = [
            'order_id' => $orderId,
            'method' => $paymentMethod,
            'amount' => $order['total_amount']
        ];
    }
}
?>
<!DOCTYPE html>
<html lang="<?php echo $lang; ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo APP_NAME; ?> - <?php echo $lang === 'sw' ? 'Lipa kwa Simu' : 'Mobile Payment'; ?></title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        .payment-methods { display: flex; flex-direction: column; gap: 1rem; margin-bottom: 1.5rem; }
        .payment-method {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 1rem;
            border: 2px solid var(--gray-200);
            border-radius: var(--radius-lg);
            cursor: pointer;
            transition: all 0.2s;
            background: var(--white);
        }
        .payment-method:hover { border-color: var(--primary); }
        .payment-method.selected {
            border-color: var(--primary);
            background: var(--primary-light);
        }
        .payment-method input { display: none; }
        .payment-icon {
            width: 48px;
            height: 48px;
            border-radius: var(--radius-md);
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 0.75rem;
            color: white;
        }
        .mpesa { background: #00a650; }
        .tigopesa { background: #0066b3; }
        .airtelmoney { background: #e31e24; }
        .payment-info { flex: 1; }
        .payment-name { font-weight: 600; color: var(--gray-900); }
        .payment-desc { font-size: 0.875rem; color: var(--gray-500); }
        .order-summary {
            background: var(--gray-50);
            padding: 1rem;
            border-radius: var(--radius-md);
            margin-bottom: 1.5rem;
        }
        .success-animation {
            width: 100px;
            height: 100px;
            background: var(--primary-light);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
            animation: scaleIn 0.5s ease;
        }
        @keyframes scaleIn {
            0% { transform: scale(0); }
            50% { transform: scale(1.1); }
            100% { transform: scale(1); }
        }
        .instructions {
            background: #f0f9ff;
            border-left: 4px solid var(--info);
            padding: 1rem;
            border-radius: var(--radius-md);
            margin-bottom: 1.5rem;
            font-size: 0.875rem;
        }
    </style>
</head>
<body>
    <div class="app-container">
        <!-- Header -->
        <header class="app-header">
            <h1>
                <svg class="logo-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a1 1 0 11-2 0 1 1 0 012 0z"/>
                </svg>
                <?php echo $lang === 'sw' ? 'Malipo' : 'Payment'; ?>
            </h1>
            <div class="header-actions">
                <div class="lang-toggle">
                    <button class="lang-btn <?php echo $lang === 'en' ? 'active' : ''; ?>" onclick="location.href='set-language.php?lang=en&redirect=payment.php?order_id=<?php echo $orderId; ?>'">EN</button>
                    <button class="lang-btn <?php echo $lang === 'sw' ? 'active' : ''; ?>" onclick="location.href='set-language.php?lang=sw&redirect=payment.php?order_id=<?php echo $orderId; ?>'">SW</button>
                </div>
            </div>
        </header>

        <!-- Main Content -->
        <main class="main-content">
            <div class="page-content">
                <?php if ($paymentStatus === 'success'): ?>
                <!-- Payment Success -->
                <div class="text-center" style="padding: 2rem 0;">
                    <div class="success-animation">
                        <svg fill="none" stroke="#10b981" viewBox="0 0 24 24" style="width: 50px; height: 50px;">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/>
                        </svg>
                    </div>
                    <h2 style="margin-bottom: 0.5rem;"><?php echo $lang === 'sw' ? 'Malipo Yamekamilika!' : 'Payment Successful!'; ?></h2>
                    <p style="color: var(--gray-500); margin-bottom: 1.5rem;">
                        <?php echo $lang === 'sw' ? 'Asante kwa kulipa. Agizo lako sasa linaandaliwa.' : 'Thank you for your payment. Your order is now being prepared.'; ?>
                    </p>
                    
                    <div class="order-number-display" style="font-size: 1.25rem; margin: 1rem 0;">
                        <?php echo htmlspecialchars($order['order_number']); ?>
                    </div>
                    
                    <p style="font-size: 1.125rem; color: var(--primary); font-weight: 700; margin-bottom: 2rem;">
                        <?php echo formatCurrency($order['total_amount']); ?>
                    </p>
                    
                    <a href="index.php" class="btn btn-primary btn-lg">
                        <?php echo $lang === 'sw' ? 'Rudi kwa Menyu' : 'Back to Menu'; ?>
                    </a>
                </div>
                
                <?php else: ?>
                <!-- Payment Form -->
                <div class="order-summary">
                    <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem;">
                        <span style="color: var(--gray-500);"><?php echo $lang === 'sw' ? 'Namba ya Agizo:' : 'Order Number:'; ?></span>
                        <span style="font-weight: 600;"><?php echo htmlspecialchars($order['order_number']); ?></span>
                    </div>
                    <div style="display: flex; justify-content: space-between; font-size: 1.125rem;">
                        <span style="font-weight: 600;"><?php echo $lang === 'sw' ? 'Jumla ya Kulipa:' : 'Total to Pay:'; ?></span>
                        <span style="font-weight: 700; color: var(--primary);"><?php echo formatCurrency($order['total_amount']); ?></span>
                    </div>
                </div>

                <div class="instructions">
                    <strong><?php echo $lang === 'sw' ? 'Maelekezo:' : 'Instructions:'; ?></strong>
                    <p style="margin: 0.5rem 0 0;">
                        <?php echo $lang === 'sw' 
                            ? '1. Chagua njia ya kulipa (M-Pesa, Tigo Pesa, au Airtel Money)' 
                            : '1. Select payment method (M-Pesa, Tigo Pesa, or Airtel Money)'; ?><br>
                        <?php echo $lang === 'sw' 
                            ? '2. Weka namba yako ya simu' 
                            : '2. Enter your phone number'; ?><br>
                        <?php echo $lang === 'sw' 
                            ? '3. Bonyeza "Lipa Sasa" kupokea ombi la malipo' 
                            : '3. Click "Pay Now" to receive payment request'; ?>
                    </p>
                </div>

                <?php if ($error): ?>
                <div class="alert alert-error" style="margin-bottom: 1rem;">
                    <?php echo htmlspecialchars($error); ?>
                </div>
                <?php endif; ?>

                <form method="POST" action="">
                    <h3 style="margin-bottom: 1rem;"><?php echo $lang === 'sw' ? 'Chagua Njia ya Kulipa' : 'Select Payment Method'; ?></h3>
                    
                    <div class="payment-methods">
                        <label class="payment-method">
                            <input type="radio" name="payment_method" value="mpesa" required>
                            <div class="payment-icon mpesa">M-Pesa</div>
                            <div class="payment-info">
                                <div class="payment-name">M-Pesa</div>
                                <div class="payment-desc"><?php echo $lang === 'sw' ? 'Lipa kwa M-Pesa' : 'Pay with M-Pesa'; ?></div>
                            </div>
                        </label>
                        
                        <label class="payment-method">
                            <input type="radio" name="payment_method" value="tigopesa" required>
                            <div class="payment-icon tigopesa">Tigo Pesa</div>
                            <div class="payment-info">
                                <div class="payment-name">Tigo Pesa</div>
                                <div class="payment-desc"><?php echo $lang === 'sw' ? 'Lipa kwa Tigo Pesa' : 'Pay with Tigo Pesa'; ?></div>
                            </div>
                        </label>
                        
                        <label class="payment-method">
                            <input type="radio" name="payment_method" value="airtelmoney" required>
                            <div class="payment-icon airtelmoney">Airtel</div>
                            <div class="payment-info">
                                <div class="payment-name">Airtel Money</div>
                                <div class="payment-desc"><?php echo $lang === 'sw' ? 'Lipa kwa Airtel Money' : 'Pay with Airtel Money'; ?></div>
                            </div>
                        </label>
                    </div>

                    <div class="form-group">
                        <label class="form-label"><?php echo $lang === 'sw' ? 'Namba yako ya Simu' : 'Your Phone Number'; ?> *</label>
                        <input type="tel" name="phone_number" class="form-input" placeholder="07XX XXX XXX" 
                               pattern="[0-9]{10}" required maxlength="10">
                        <small style="color: var(--gray-500);">
                            <?php echo $lang === 'sw' ? 'Mfano: 0712345678' : 'Example: 0712345678'; ?>
                        </small>
                    </div>

                    <button type="submit" class="btn btn-primary btn-lg btn-block" style="margin-top: 1rem;">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width: 20px; height: 20px;">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a1 1 0 11-2 0 1 1 0 012 0z"/>
                        </svg>
                        <?php echo $lang === 'sw' ? 'Lipa Sasa' : 'Pay Now'; ?> - <?php echo formatCurrency($order['total_amount']); ?>
                    </button>
                </form>
                
                <div style="margin-top: 1.5rem; text-align: center;">
                    <a href="order-success.php?order_id=<?php echo $orderId; ?>" style="color: var(--gray-500); font-size: 0.875rem;">
                        <?php echo $lang === 'sw' ? '← Rudi' : '← Back'; ?>
                    </a>
                </div>
                <?php endif; ?>
            </div>
        </main>
    </div>

    <script>
        // Payment method selection
        document.querySelectorAll('.payment-method').forEach(method => {
            method.addEventListener('click', function() {
                document.querySelectorAll('.payment-method').forEach(m => m.classList.remove('selected'));
                this.classList.add('selected');
                this.querySelector('input').checked = true;
            });
        });
    </script>
</body>
</html>
