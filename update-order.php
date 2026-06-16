<?php
require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('index.php');
}

$orderId = $_POST['order_id'] ?? 0;
$action = $_POST['action'] ?? '';
$redirectTo = $_POST['redirect'] ?? 'cashier';

$validActions = ['prepare', 'ready', 'complete', 'cancel', 'pay'];
if (!in_array($action, $validActions)) {
    redirect($redirectTo . '.php');
}

$statusMap = [
    'prepare' => 'preparing',
    'ready' => 'ready',
    'complete' => 'completed',
    'cancel' => 'cancelled'
];

try {
    if ($action === 'pay') {
        $stmt = $conn->prepare("UPDATE orders SET payment_status = 'paid' WHERE id = ?");
        $stmt->execute([$orderId]);
    } else {
        $newStatus = $statusMap[$action];
        $completedAt = ($newStatus === 'completed') ? ', completed_at = NOW()' : '';
        $stmt = $conn->prepare("UPDATE orders SET status = ?{$completedAt} WHERE id = ?");
        $stmt->execute([$newStatus, $orderId]);
    }
    
    $_SESSION['success'] = 'Order updated successfully.';
} catch (Exception $e) {
    $_SESSION['error'] = 'Error updating order.';
}

redirect($redirectTo . '.php');
