<?php
require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('index.php');
}

// Get form data
$customerName = sanitizeInput($_POST['customer_name'] ?? '');
$customerPhone = sanitizeInput($_POST['customer_phone'] ?? '');
$orderItemsJson = $_POST['order_items'] ?? '';

if (empty($customerName) || empty($orderItemsJson)) {
    $_SESSION['error'] = 'Please fill in all required fields.';
    redirect('index.php');
}

$orderItems = json_decode($orderItemsJson, true);
if (empty($orderItems)) {
    $_SESSION['error'] = 'Your order is empty.';
    redirect('index.php');
}

try {
    $conn->beginTransaction();

    // Calculate total
    $totalAmount = 0;
    foreach ($orderItems as $item) {
        $totalAmount += $item['price'] * $item['quantity'];
    }

    // Generate order number
    $orderNumber = generateOrderNumber();

    // Insert order
    $stmt = $conn->prepare("INSERT INTO orders (order_number, customer_name, customer_phone, total_amount, status, created_at) VALUES (?, ?, ?, ?, 'pending', NOW())");
    $stmt->execute([$orderNumber, $customerName, $customerPhone, $totalAmount]);
    $orderId = $conn->lastInsertId();

    // Insert order items and update stock
    $stmtItem = $conn->prepare("INSERT INTO order_items (order_id, food_item_id, quantity, unit_price, subtotal) VALUES (?, ?, ?, ?, ?)");
    $stmtStock = $conn->prepare("UPDATE food_items SET stock_quantity = stock_quantity - ?, stock_status = CASE 
        WHEN stock_quantity - ? <= 0 THEN 'finished'
        WHEN stock_quantity - ? <= low_stock_threshold THEN 'low'
        ELSE 'available'
        END WHERE id = ?");
    $stmtStockLog = $conn->prepare("INSERT INTO stock_logs (food_item_id, quantity_change, change_type, reason) VALUES (?, ?, 'order_consumption', 'Order placed')");

    foreach ($orderItems as $item) {
        $subtotal = $item['price'] * $item['quantity'];
        $stmtItem->execute([$orderId, $item['id'], $item['quantity'], $item['price'], $subtotal]);
        $stmtStock->execute([$item['quantity'], $item['quantity'], $item['quantity'], $item['id']]);
        $stmtStockLog->execute([$item['id'], -$item['quantity']]);
    }

    // Update daily sales
    $today = date('Y-m-d');
    $stmt = $conn->prepare("INSERT INTO daily_sales (sale_date, total_orders, total_revenue, total_items_sold) VALUES (?, 1, ?, ?) ON DUPLICATE KEY UPDATE total_orders = total_orders + 1, total_revenue = total_revenue + VALUES(total_revenue), total_items_sold = total_items_sold + VALUES(total_items_sold)");
    $totalItems = array_sum(array_column($orderItems, 'quantity'));
    $stmt->execute([$today, $totalAmount, $totalItems]);

    $conn->commit();

    // Store order in session for confirmation page
    $_SESSION['last_order'] = [
        'order_number' => $orderNumber,
        'customer_name' => $customerName,
        'total' => $totalAmount,
        'items' => $orderItems
    ];

    redirect('order-success.php');

} catch (Exception $e) {
    $conn->rollBack();
    $_SESSION['error'] = 'Error placing order. Please try again.';
    redirect('index.php');
}
