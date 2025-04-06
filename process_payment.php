<?php
include 'check.php';

// Get JSON data
$data = json_decode(file_get_contents('php://input'), true);
$user_id = $_SESSION['id'];

try {
    // Clear the user's cart after successful payment
    $query->executeQuery("DELETE FROM cart WHERE user_id = $user_id");
    
    // You would typically add order details to an orders table here
    // For now, we'll just return success
    echo json_encode(['success' => true]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
