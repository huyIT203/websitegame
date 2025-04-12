<?php
include 'check.php';

// Nhận dữ liệu JSON
$data = json_decode(file_get_contents('php://input'), true);
$user_id = $_SESSION['id'];

// Kiểm tra dữ liệu
if (!isset($data['order_id']) || empty($data['order_id'])) {
    echo json_encode(['success' => false, 'message' => 'Thiếu thông tin đơn hàng']);
    exit;
}

$order_id = (int)$data['order_id'];

// Kiểm tra đơn hàng tồn tại và thuộc về người dùng hiện tại
$order = $query->executeQuery("SELECT * FROM orders WHERE id = $order_id AND user_id = $user_id")->fetch_assoc();

if (!$order) {
    echo json_encode(['success' => false, 'message' => 'Không tìm thấy đơn hàng']);
    exit;
}

// Kiểm tra trạng thái đơn hàng có thể hủy không
if ($order['status'] !== 'pending') {
    echo json_encode(['success' => false, 'message' => 'Không thể hủy đơn hàng này vì đã được xử lý']);
    exit;
}

try {
    // Cập nhật trạng thái đơn hàng thành 'cancelled'
    $query->update('orders', ['status' => 'cancelled'], "WHERE id = $order_id");
    
    echo json_encode(['success' => true]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
} 