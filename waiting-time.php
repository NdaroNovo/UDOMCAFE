<?php
/**
 * Waiting Time Estimation for CIVE Cafeteria
 * Calculates estimated wait based on queue position and order complexity
 */

require_once 'config.php';

// Get current queue status
$stmt = $conn->query("SELECT 
    COUNT(*) as total_pending,
    COUNT(CASE WHEN status = 'preparing' THEN 1 END) as currently_preparing,
    AVG(TIMESTAMPDIFF(MINUTE, created_at, NOW())) as avg_wait_time
    FROM orders 
    WHERE status IN ('pending', 'preparing', 'ready') 
    AND DATE(created_at) = CURDATE()");
$queueStats = $stmt->fetch(PDO::FETCH_ASSOC);

// Get specific order details if provided
$orderId = $_GET['order_id'] ?? 0;
$orderPosition = 0;
$estimatedMinutes = 0;

if ($orderId) {
    // Get order details
    $stmt = $conn->prepare("SELECT * FROM orders WHERE id = ?");
    $stmt->execute([$orderId]);
    $order = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($order) {
        // Calculate position in queue
        $stmt = $conn->prepare("SELECT COUNT(*) as position FROM orders 
            WHERE status IN ('pending', 'preparing') 
            AND DATE(created_at) = CURDATE()
            AND created_at <= ?");
        $stmt->execute([$order['created_at']]);
        $positionData = $stmt->fetch(PDO::FETCH_ASSOC);
        $orderPosition = $positionData['position'] ?? 0;
        
        // Calculate estimated time
        // Base time: 5 minutes per order + 2 minutes per item
        $stmt = $conn->prepare("SELECT SUM(quantity) as total_items FROM order_items WHERE order_id = ?");
        $stmt->execute([$orderId]);
        $itemsData = $stmt->fetch(PDO::FETCH_ASSOC);
        $totalItems = $itemsData['total_items'] ?? 1;
        
        // Base preparation time: 5 mins + 2 mins per item
        $basePrepTime = 5 + ($totalItems * 2);
        
        // Add time for queue position (3 minutes per order ahead)
        $queueWaitTime = ($orderPosition - 1) * 3;
        
        $estimatedMinutes = $basePrepTime + $queueWaitTime;
        
        // Adjust based on status
        if ($order['status'] === 'ready') {
            $estimatedMinutes = 0;
        } elseif ($order['status'] === 'preparing') {
            $estimatedMinutes = max(2, ceil($estimatedMinutes * 0.5)); // Half remaining time
        }
    }
}

$lang = getLang();

// Return JSON if requested
if (isset($_GET['ajax'])) {
    header('Content-Type: application/json');
    echo json_encode([
        'total_pending' => (int)$queueStats['total_pending'],
        'currently_preparing' => (int)$queueStats['currently_preparing'],
        'avg_wait_time' => round($queueStats['avg_wait_time'] ?? 0),
        'order_position' => $orderPosition,
        'estimated_minutes' => $estimatedMinutes,
        'estimated_time_formatted' => $estimatedMinutes > 0 
            ? ($estimatedMinutes < 60 
                ? $estimatedMinutes . ' ' . ($lang === 'sw' ? 'dakika' : 'mins')
                : floor($estimatedMinutes / 60) . 'h ' . ($estimatedMinutes % 60) . 'm')
            : ($lang === 'sw' ? 'Tayari!' : 'Ready!')
    ]);
    exit;
}

// Full page for order success
?>
<!DOCTYPE html>
<html lang="<?php echo $lang; ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo APP_NAME; ?> - <?php echo $lang === 'sw' ? 'Muda wa Kungoja' : 'Waiting Time'; ?></title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        .waiting-card {
            background: linear-gradient(135deg, var(--primary-light) 0%, #ffffff 100%);
            border-radius: var(--radius-xl);
            padding: 2rem;
            text-align: center;
            margin-bottom: 1.5rem;
            border: 2px solid var(--primary);
        }
        .time-display {
            font-size: 3rem;
            font-weight: 700;
            color: var(--primary);
            line-height: 1;
            margin: 1rem 0;
        }
        .time-label {
            font-size: 1.125rem;
            color: var(--gray-600);
        }
        .queue-info {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 1rem;
            margin-top: 1.5rem;
        }
        .queue-stat {
            background: var(--white);
            padding: 1rem;
            border-radius: var(--radius-md);
            box-shadow: var(--shadow-sm);
        }
        .queue-stat-value {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--primary);
        }
        .queue-stat-label {
            font-size: 0.75rem;
            color: var(--gray-500);
            text-transform: uppercase;
        }
        .progress-bar {
            width: 100%;
            height: 8px;
            background: var(--gray-200);
            border-radius: var(--radius-full);
            overflow: hidden;
            margin: 1rem 0;
        }
        .progress-fill {
            height: 100%;
            background: linear-gradient(90deg, var(--primary) 0%, var(--primary-dark) 100%);
            border-radius: var(--radius-full);
            transition: width 1s ease;
        }
        .status-timeline {
            display: flex;
            justify-content: space-between;
            margin: 2rem 0;
            position: relative;
        }
        .status-timeline::before {
            content: '';
            position: absolute;
            top: 15px;
            left: 10%;
            right: 10%;
            height: 2px;
            background: var(--gray-200);
            z-index: 0;
        }
        .timeline-step {
            display: flex;
            flex-direction: column;
            align-items: center;
            position: relative;
            z-index: 1;
        }
        .timeline-dot {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background: var(--gray-200);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.875rem;
            color: var(--gray-500);
            transition: all 0.3s;
        }
        .timeline-dot.active {
            background: var(--primary);
            color: white;
        }
        .timeline-dot.complete {
            background: var(--success);
            color: white;
        }
        .timeline-label {
            font-size: 0.75rem;
            color: var(--gray-500);
            margin-top: 0.5rem;
            text-align: center;
        }
        .live-indicator {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            background: var(--primary-light);
            color: var(--primary-dark);
            padding: 0.5rem 1rem;
            border-radius: var(--radius-full);
            font-size: 0.875rem;
            font-weight: 600;
        }
        .live-dot {
            width: 8px;
            height: 8px;
            background: var(--primary);
            border-radius: 50%;
            animation: pulse 2s infinite;
        }
    </style>
</head>
<body>
    <div class="app-container">
        <!-- Header -->
        <header class="app-header">
            <h1>
                <svg class="logo-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <?php echo $lang === 'sw' ? 'Muda wa Kungoja' : 'Waiting Time'; ?>
            </h1>
        </header>

        <!-- Main Content -->
        <main class="main-content">
            <div class="page-content">
                <?php if (isset($order) && $order): ?>
                <!-- Order-specific waiting time -->
                <div class="waiting-card">
                    <div class="live-indicator">
                        <span class="live-dot"></span>
                        <?php echo $lang === 'sw' ? 'Muda Kuishi' : 'Live'; ?>
                    </div>
                    
                    <div class="time-display" id="timeDisplay">
                        <?php 
                        if ($order['status'] === 'ready') {
                            echo $lang === 'sw' ? 'TAYARI!' : 'READY!';
                        } elseif ($order['status'] === 'completed') {
                            echo $lang === 'sw' ? 'IMEKAMILIKA' : 'COMPLETED';
                        } else {
                            echo $estimatedMinutes . '<span style="font-size: 1.5rem;">' . ($lang === 'sw' ? ' dakika' : ' mins') . '</span>';
                        }
                        ?>
                    </div>
                    
                    <div class="time-label">
                        <?php 
                        if ($order['status'] === 'ready') {
                            echo $lang === 'sw' ? 'Chakula chako chiko tayari kuokotwa' : 'Your food is ready for pickup';
                        } elseif ($order['status'] === 'completed') {
                            echo $lang === 'sw' ? 'Asante! Karibu tena' : 'Thank you! Come again';
                        } else {
                            echo $lang === 'sw' ? 'Muda unaokadiriwa wa kukamilika' : 'Estimated time to completion';
                        }
                        ?>
                    </div>

                    <?php if ($order['status'] === 'pending' || $order['status'] === 'preparing'): ?>
                    <div class="progress-bar">
                        <div class="progress-fill" id="progressBar" style="width: <?php 
                            $progress = min(90, max(10, 100 - ($estimatedMinutes / 30 * 100)));
                            if ($order['status'] === 'preparing') $progress = min(80, $progress + 30);
                            echo $progress; 
                        ?>%;"></div>
                    </div>
                    <?php endif; ?>

                    <!-- Timeline -->
                    <div class="status-timeline">
                        <div class="timeline-step">
                            <div class="timeline-dot <?php echo in_array($order['status'], ['pending', 'preparing', 'ready', 'completed']) ? 'complete' : ''; ?>">
                                ✓
                            </div>
                            <span class="timeline-label"><?php echo $lang === 'sw' ? 'Imepokelewa' : 'Received'; ?></span>
                        </div>
                        <div class="timeline-step">
                            <div class="timeline-dot <?php echo in_array($order['status'], ['preparing', 'ready', 'completed']) ? 'active' : ''; ?>" style="background: <?php echo $order['status'] === 'pending' ? 'var(--gray-200)' : ''; ?>">
                                2
                            </div>
                            <span class="timeline-label"><?php echo $lang === 'sw' ? 'Inaandaliwa' : 'Preparing'; ?></span>
                        </div>
                        <div class="timeline-step">
                            <div class="timeline-dot <?php echo in_array($order['status'], ['ready', 'completed']) ? 'active' : ''; ?>" style="background: <?php echo in_array($order['status'], ['pending', 'preparing']) ? 'var(--gray-200)' : ''; ?>">
                                3
                            </div>
                            <span class="timeline-label"><?php echo $lang === 'sw' ? 'Tayari' : 'Ready'; ?></span>
                        </div>
                        <div class="timeline-step">
                            <div class="timeline-dot <?php echo $order['status'] === 'completed' ? 'complete' : ''; ?>" style="background: <?php echo in_array($order['status'], ['pending', 'preparing', 'ready']) ? 'var(--gray-200)' : ''; ?>">
                                <?php echo $order['status'] === 'completed' ? '✓' : '4'; ?>
                            </div>
                            <span class="timeline-label"><?php echo $lang === 'sw' ? 'Imemalizika' : 'Done'; ?></span>
                        </div>
                    </div>
                </div>

                <!-- Queue Stats -->
                <div class="card">
                    <div class="card-header">
                        <h3><?php echo $lang === 'sw' ? 'Hali ya Foleni' : 'Queue Status'; ?></h3>
                    </div>
                    <div class="card-body">
                        <div class="queue-info">
                            <div class="queue-stat">
                                <div class="queue-stat-value" id="queuePosition"><?php echo $orderPosition; ?></div>
                                <div class="queue-stat-label"><?php echo $lang === 'sw' ? 'Nafasi Yako' : 'Your Position'; ?></div>
                            </div>
                            <div class="queue-stat">
                                <div class="queue-stat-value" id="totalPending"><?php echo $queueStats['total_pending']; ?></div>
                                <div class="queue-stat-label"><?php echo $lang === 'sw' ? 'Yanasubiri' : 'Waiting'; ?></div>
                            </div>
                            <div class="queue-stat">
                                <div class="queue-stat-value" id="currentlyPreparing"><?php echo $queueStats['currently_preparing']; ?></div>
                                <div class="queue-stat-label"><?php echo $lang === 'sw' ? 'Inaandaliwa' : 'Preparing'; ?></div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="text-center" style="margin-top: 1.5rem;">
                    <a href="index.php" class="btn btn-primary">
                        <?php echo $lang === 'sw' ? 'Rudi kwa Menyu' : 'Back to Menu'; ?>
                    </a>
                </div>

                <script>
                    // Auto-refresh every 30 seconds
                    setInterval(() => {
                        fetch('waiting-time.php?order_id=<?php echo $orderId; ?>&ajax=1')
                            .then(r => r.json())
                            .then(data => {
                                document.getElementById('timeDisplay').innerHTML = 
                                    data.estimated_minutes + '<span style="font-size: 1.5rem;"><?php echo $lang === 'sw' ? ' dakika' : ' mins'; ?></span>';
                                document.getElementById('queuePosition').textContent = data.order_position;
                                document.getElementById('totalPending').textContent = data.total_pending;
                                document.getElementById('currentlyPreparing').textContent = data.currently_preparing;
                            });
                    }, 30000);
                </script>
                <?php else: ?>
                <!-- General queue status -->
                <div class="card">
                    <div class="card-header">
                        <h3><?php echo $lang === 'sw' ? 'Hali ya Foleni ya Leo' : 'Today\'s Queue Status'; ?></h3>
                    </div>
                    <div class="card-body">
                        <div class="queue-info" style="grid-template-columns: repeat(3, 1fr);">
                            <div class="queue-stat">
                                <div class="queue-stat-value"><?php echo $queueStats['total_pending']; ?></div>
                                <div class="queue-stat-label"><?php echo $lang === 'sw' ? 'Maagizo Yanasubiri' : 'Pending Orders'; ?></div>
                            </div>
                            <div class="queue-stat">
                                <div class="queue-stat-value"><?php echo $queueStats['currently_preparing']; ?></div>
                                <div class="queue-stat-label"><?php echo $lang === 'sw' ? 'Inaandaliwa' : 'Being Prepared'; ?></div>
                            </div>
                            <div class="queue-stat">
                                <div class="queue-stat-value"><?php echo round($queueStats['avg_wait_time'] ?? 0); ?>m</div>
                                <div class="queue-stat-label"><?php echo $lang === 'sw' ? 'Wastani wa Kungoja' : 'Avg Wait'; ?></div>
                            </div>
                        </div>
                        
                        <div style="margin-top: 1.5rem; padding: 1rem; background: var(--gray-50); border-radius: var(--radius-md);">
                            <p style="margin: 0; color: var(--gray-600); text-align: center;">
                                <?php echo $lang === 'sw' 
                                    ? 'Weka agizo lako sasa na uokote chakula chako bila kuingia foleni!' 
                                    : 'Place your order now and pick up your food without joining the queue!'; ?>
                            </p>
                        </div>
                    </div>
                </div>

                <div class="text-center" style="margin-top: 1.5rem;">
                    <a href="index.php" class="btn btn-primary btn-lg">
                        <?php echo $lang === 'sw' ? 'Weka Agizo Sasa' : 'Order Now'; ?>
                    </a>
                </div>
                <?php endif; ?>
            </div>
        </main>
    </div>
</body>
</html>
