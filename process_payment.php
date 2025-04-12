<?php
include 'check.php';

// Get JSON data
$data = json_decode(file_get_contents('php://input'), true);
$user_id = $_SESSION['id'];

try {
    // Start transaction
    $query->executeQuery("START TRANSACTION");
    
    // Get cart items before clearing
    $cartItems = $query->getCartItems($user_id);
    $total = $data['total'];
    $shipping_address = isset($data['shipping_address']) ? $data['shipping_address'] : '';
    
    // Create new order
    $orderData = [
        'user_id' => $user_id,
        'total_amount' => $total,
        'shipping_address' => $shipping_address,
        'order_date' => date('Y-m-d H:i:s'),
        'status' => 'pending'
    ];
    
    $query->insert('orders', $orderData);
    $order_id = $query->lastInsertId();
    
    // Insert order items
    foreach ($cartItems as $item) {
        $orderItemData = [
            'order_id' => $order_id,
            'product_id' => $item['id'],
            'quantity' => $item['number_of_products'],
            'price' => $item['price_current']
        ];
        $query->insert('order_items', $orderItemData);
    }
    
    // Clear the user's cart after successful payment
    $query->executeQuery("DELETE FROM cart WHERE user_id = $user_id");
    
    // Commit transaction
    $query->executeQuery("COMMIT");
    
    echo json_encode(['success' => true, 'order_id' => $order_id]);
} catch (Exception $e) {
    // Rollback on error
    $query->executeQuery("ROLLBACK");
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
