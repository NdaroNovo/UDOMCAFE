<?php
require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('dashboard.php');
}

$foodId = $_POST['food_id'] ?? 0;
$quantity = intval($_POST['quantity'] ?? 0);

if ($foodId <= 0 || $quantity <= 0) {
    $_SESSION['error'] = 'Invalid input.';
    redirect('dashboard.php');
}

try {
    // Update stock
    $stmt = $conn->prepare("UPDATE food_items SET 
        stock_quantity = stock_quantity + ?,
        stock_status = CASE 
            WHEN stock_quantity + ? <= 0 THEN 'finished'
            WHEN stock_quantity + ? <= low_stock_threshold THEN 'low'
            ELSE 'available'
        END
        WHERE id = ?");
    $stmt->execute([$quantity, $quantity, $quantity, $foodId]);

    // Log stock change
    $stmt = $conn->prepare("INSERT INTO stock_logs (food_item_id, quantity_change, change_type, reason) VALUES (?, ?, 'addition', 'Stock added by manager')");
    $stmt->execute([$foodId, $quantity]);

    $_SESSION['success'] = 'Stock updated successfully.';
} catch (Exception $e) {
    $_SESSION['error'] = 'Error updating stock.';
}

redirect('dashboard.php');
