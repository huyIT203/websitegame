<?php
include 'check.php';

$user_id = $_SESSION['id'];

// Kiểm tra có order_id không
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header('Location: profile.php');
    exit;
}

$order_id = (int)$_GET['id'];

// Lấy thông tin đơn hàng và kiểm tra người dùng có quyền xem đơn hàng này không
$order = $query->executeQuery("SELECT * FROM orders WHERE id = $order_id AND user_id = $user_id")->fetch_assoc();

if (!$order) {
    header('Location: profile.php');
    exit;
}

// Lấy thông tin chi tiết đơn hàng
$order_items = $query->executeQuery("
    SELECT oi.*, p.name, p.price_current, p.price_old, pi.image_url 
    FROM order_items oi
    JOIN products p ON oi.product_id = p.id
    LEFT JOIN (
        SELECT product_id, image_url 
        FROM product_images 
        GROUP BY product_id
    ) pi ON p.id = pi.product_id
    WHERE oi.order_id = $order_id
")->fetch_all(MYSQLI_ASSOC);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chi tiết đơn hàng #<?= $order_id; ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@200;300;400;600;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="./src/css/bootstrap.min.css" type="text/css">
    <link rel="stylesheet" href="./src/css/font-awesome.min.css" type="text/css">
    <link rel="stylesheet" href="./src/css/elegant-icons.css" type="text/css">
    <link rel="stylesheet" href="./src/css/nice-select.css" type="text/css">
    <link rel="stylesheet" href="./src/css/jquery-ui.min.css" type="text/css">
    <link rel="stylesheet" href="./src/css/owl.carousel.min.css" type="text/css">
    <link rel="stylesheet" href="./src/css/slicknav.min.css" type="text/css">
    <link rel="stylesheet" href="./src/css/style.css" type="text/css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <style>
        .order-container {
            background-color: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            margin-bottom: 30px;
        }
        .order-heading {
            font-size: 24px;
            color: #6c5ce7;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid #f1f1f1;
        }
        .order-info {
            margin-bottom: 30px;
        }
        .order-info p {
            margin-bottom: 10px;
            font-size: 16px;
        }
        .order-info .label {
            font-weight: bold;
            color: #333;
            margin-right: 10px;
        }
        .product-img {
            width: 80px;
            height: 80px;
            object-fit: cover;
            border-radius: 5px;
        }
        .status {
            display: inline-block;
            padding: 5px 15px;
            border-radius: 20px;
            font-weight: bold;
            text-transform: uppercase;
            font-size: 12px;
        }
        .status-pending {
            background-color: #ffeaa7;
            color: #fdcb6e;
        }
        .status-processing {
            background-color: #81ecec;
            color: #00cec9;
        }
        .status-shipped {
            background-color: #74b9ff;
            color: #0984e3;
        }
        .status-delivered {
            background-color: #55efc4;
            color: #00b894;
        }
        .status-cancelled {
            background-color: #ff7675;
            color: #d63031;
        }
        .btn-back {
            background-color: #6c5ce7;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            text-decoration: none;
            display: inline-block;
            font-weight: 600;
            transition: all 0.3s;
        }
        .btn-back:hover {
            background-color: #5649c0;
            text-decoration: none;
            color: white;
        }
        .order-total {
            text-align: right;
            margin-top: 20px;
            font-size: 18px;
            font-weight: bold;
        }
        .cancel-order-btn {
            background-color: #ff7675;
            color: white;
            border: none;
            padding: 8px 15px;
            border-radius: 5px;
            font-size: 14px;
            font-weight: 600;
            transition: all 0.3s;
        }
        .cancel-order-btn:hover {
            background-color: #d63031;
        }
    </style>
</head>

<body>
    <?php include './includes/header.php'; ?>

    <section class="order-detail-section spad">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="order-container">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h3 class="order-heading mb-0">Chi tiết đơn hàng #<?= $order_id; ?></h3>
                            <a href="profile.php" class="btn-back"><i class="fa fa-arrow-left mr-2"></i> Quay lại</a>
                        </div>
                        
                        <div class="order-info">
                            <div class="row">
                                <div class="col-md-6">
                                    <p><span class="label">Ngày đặt hàng:</span> <?= date('d/m/Y H:i', strtotime($order['order_date'])); ?></p>
                                    <p><span class="label">Trạng thái:</span>
                                        <?php
                                        $status_class = '';
                                        $status_text = '';
                                        switch ($order['status']) {
                                            case 'pending':
                                                $status_class = 'status-pending';
                                                $status_text = 'Đang xử lý';
                                                break;
                                            case 'processing':
                                                $status_class = 'status-processing';
                                                $status_text = 'Đang chuẩn bị';
                                                break;
                                            case 'shipped':
                                                $status_class = 'status-shipped';
                                                $status_text = 'Đang giao hàng';
                                                break;
                                            case 'delivered':
                                                $status_class = 'status-delivered';
                                                $status_text = 'Đã giao hàng';
                                                break;
                                            case 'cancelled':
                                                $status_class = 'status-cancelled';
                                                $status_text = 'Đã hủy';
                                                break;
                                        }
                                        ?>
                                        <span class="status <?= $status_class; ?>"><?= $status_text; ?></span>
                                    </p>
                                </div>
                                <div class="col-md-6">
                                    <p><span class="label">Địa chỉ giao hàng:</span> <?= htmlspecialchars($order['shipping_address'] ?: 'Không có thông tin'); ?></p>
                                    <p><span class="label">Tổng tiền:</span> <span class="text-primary font-weight-bold">$<?= number_format($order['total_amount'], 2); ?></span></p>
                                </div>
                            </div>
                            
                            <?php if ($order['status'] === 'pending'): ?>
                            <div class="text-right mt-3">
                                <button class="cancel-order-btn" onclick="cancelOrder(<?= $order_id; ?>)">Hủy đơn hàng</button>
                            </div>
                            <?php endif; ?>
                        </div>
                        
                        <h4 class="mb-4">Sản phẩm đã đặt</h4>
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Sản phẩm</th>
                                        <th>Giá</th>
                                        <th>Số lượng</th>
                                        <th class="text-right">Thành tiền</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($order_items as $item): ?>
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <?php if (!empty($item['image_url'])): ?>
                                                <img src="./src/images/products/<?= htmlspecialchars($item['image_url']); ?>" alt="<?= htmlspecialchars($item['name']); ?>" class="product-img mr-3">
                                                <?php endif; ?>
                                                <div>
                                                    <h6 class="mb-0"><?= htmlspecialchars($item['name']); ?></h6>
                                                </div>
                                            </div>
                                        </td>
                                        <td>$<?= number_format($item['price'], 2); ?></td>
                                        <td><?= $item['quantity']; ?></td>
                                        <td class="text-right">$<?= number_format($item['price'] * $item['quantity'], 2); ?></td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                        
                        <div class="order-total">
                            <p>Tổng cộng: <span class="text-primary">$<?= number_format($order['total_amount'], 2); ?></span></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <?php include './includes/footer.php'; ?>

    <script src="./src/js/jquery-3.3.1.min.js"></script>
    <script src="./src/js/bootstrap.min.js"></script>
    <script src="./src/js/jquery.nice-select.min.js"></script>
    <script src="./src/js/jquery-ui.min.js"></script>
    <script src="./src/js/jquery.slicknav.js"></script>
    <script src="./src/js/mixitup.min.js"></script>
    <script src="./src/js/owl.carousel.min.js"></script>
    <script src="./src/js/main.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <script>
        function cancelOrder(orderId) {
            Swal.fire({
                title: 'Xác nhận hủy đơn hàng?',
                text: "Bạn sẽ không thể hoàn tác hành động này!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d63031',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Đồng ý, hủy đơn hàng!',
                cancelButtonText: 'Không, giữ lại'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Gửi yêu cầu hủy đơn hàng
                    fetch('cancel_order.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                        },
                        body: JSON.stringify({
                            order_id: orderId
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            Swal.fire(
                                'Đã hủy!',
                                'Đơn hàng của bạn đã được hủy thành công.',
                                'success'
                            ).then(() => {
                                window.location.reload();
                            });
                        } else {
                            Swal.fire(
                                'Lỗi!',
                                data.message || 'Có lỗi xảy ra khi hủy đơn hàng.',
                                'error'
                            );
                        }
                    })
                    .catch(error => {
                        Swal.fire(
                            'Lỗi!',
                            'Có lỗi xảy ra khi xử lý yêu cầu.',
                            'error'
                        );
                    });
                }
            });
        }
    </script>
</body>

</html> 