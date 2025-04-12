<?php
include 'check.php';

// Kiểm tra có order_id không
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header('Location: orders.php');
    exit;
}

$order_id = (int)$_GET['id'];

// Lấy thông tin đơn hàng
$order = $query->executeQuery("
    SELECT o.*, a.name as customer_name, a.email, a.number 
    FROM orders o
    JOIN accounts a ON o.user_id = a.id
    WHERE o.id = $order_id
")->fetch_assoc();

if (!$order) {
    header('Location: orders.php');
    exit;
}

// Xử lý cập nhật trạng thái đơn hàng
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_status'])) {
    $status = $query->validate($_POST['status']);
    
    try {
        $query->update('orders', ['status' => $status], "WHERE id = $order_id");
        $success_message = 'Cập nhật trạng thái đơn hàng thành công';
        // Cập nhật thông tin đơn hàng
        $order['status'] = $status;
    } catch (Exception $e) {
        $error_message = 'Có lỗi xảy ra: ' . $e->getMessage();
    }
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
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin | Chi tiết đơn hàng #<?= $order_id; ?></title>
    
    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="./plugins/fontawesome-free/css/all.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="./dist/css/adminlte.min.css">
    <style>
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
        .status-badge {
            padding: 5px 15px;
            border-radius: 20px;
            font-weight: bold;
            font-size: 14px;
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
        .btn-primary {
            background-color: #6c5ce7;
            border-color: #6c5ce7;
        }
        .btn-primary:hover {
            background-color: #5649c0;
            border-color: #5649c0;
        }
        .card-primary.card-outline {
            border-top: 3px solid #6c5ce7;
        }
    </style>
</head>

<body class="hold-transition sidebar-mini">
    <div class="wrapper">
        <!-- Navbar -->
        <?php include './includes/navbar.php'; ?>
        <!-- /.navbar -->

        <!-- Main Sidebar Container -->
        <?php include './includes/sidebar.php'; ?>

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <section class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1>Chi tiết đơn hàng #<?= $order_id; ?></h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="index.php">Trang chủ</a></li>
                                <li class="breadcrumb-item"><a href="orders.php">Đơn hàng</a></li>
                                <li class="breadcrumb-item active">Chi tiết đơn hàng #<?= $order_id; ?></li>
                            </ol>
                        </div>
                    </div>
                </div><!-- /.container-fluid -->
            </section>

            <!-- Main content -->
            <section class="content">
                <div class="container-fluid">
                    <?php if (isset($success_message)): ?>
                        <div class="alert alert-success alert-dismissible">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                            <?= $success_message; ?>
                        </div>
                    <?php endif; ?>
                    
                    <?php if (isset($error_message)): ?>
                        <div class="alert alert-danger alert-dismissible">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                            <?= $error_message; ?>
                        </div>
                    <?php endif; ?>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <!-- Order Info Card -->
                            <div class="card card-primary card-outline">
                                <div class="card-header">
                                    <h3 class="card-title">Thông tin đơn hàng</h3>
                                </div>
                                <div class="card-body">
                                    <div class="order-info">
                                        <p><span class="label">Mã đơn hàng:</span> #<?= $order_id; ?></p>
                                        <p><span class="label">Ngày đặt hàng:</span> <?= date('d/m/Y H:i', strtotime($order['order_date'])); ?></p>
                                        <p>
                                            <span class="label">Trạng thái:</span>
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
                                            <span class="status-badge <?= $status_class; ?>"><?= $status_text; ?></span>
                                        </p>
                                        <p><span class="label">Tổng tiền:</span> <span class="text-primary font-weight-bold">$<?= number_format($order['total_amount'], 2); ?></span></p>
                                        <p><span class="label">Địa chỉ giao hàng:</span> <?= htmlspecialchars($order['shipping_address'] ?: 'Không có thông tin'); ?></p>
                                    </div>
                                    
                                    <!-- Status Update Form -->
                                    <form method="POST" class="mt-4">
                                        <div class="form-group">
                                            <label>Cập nhật trạng thái:</label>
                                            <select class="form-control" name="status">
                                                <option value="pending" <?= $order['status'] === 'pending' ? 'selected' : ''; ?>>Đang xử lý</option>
                                                <option value="processing" <?= $order['status'] === 'processing' ? 'selected' : ''; ?>>Đang chuẩn bị</option>
                                                <option value="shipped" <?= $order['status'] === 'shipped' ? 'selected' : ''; ?>>Đang giao hàng</option>
                                                <option value="delivered" <?= $order['status'] === 'delivered' ? 'selected' : ''; ?>>Đã giao hàng</option>
                                                <option value="cancelled" <?= $order['status'] === 'cancelled' ? 'selected' : ''; ?>>Đã hủy</option>
                                            </select>
                                        </div>
                                        <button type="submit" name="update_status" class="btn btn-primary">Cập nhật trạng thái</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <!-- Customer Info Card -->
                            <div class="card card-primary card-outline">
                                <div class="card-header">
                                    <h3 class="card-title">Thông tin khách hàng</h3>
                                </div>
                                <div class="card-body">
                                    <div class="order-info">
                                        <p><span class="label">Tên khách hàng:</span> <?= htmlspecialchars($order['customer_name']); ?></p>
                                        <p><span class="label">Email:</span> <?= htmlspecialchars($order['email']); ?></p>
                                        <p><span class="label">Số điện thoại:</span> <?= htmlspecialchars($order['number']); ?></p>
                                    </div>
                                    
                                    <a href="users.php?id=<?= $order['user_id']; ?>" class="btn btn-info">
                                        <i class="fas fa-user mr-1"></i> Xem thông tin chi tiết
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-12">
                            <!-- Order Items Card -->
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">Sản phẩm đã đặt</h3>
                                </div>
                                <div class="card-body table-responsive">
                                    <table class="table table-striped">
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
                                                                <img src="../src/images/products/<?= htmlspecialchars($item['image_url']); ?>" alt="<?= htmlspecialchars($item['name']); ?>" class="product-img mr-3">
                                                            <?php endif; ?>
                                                            <div>
                                                                <h6 class="mb-0"><?= htmlspecialchars($item['name']); ?></h6>
                                                                <small class="text-muted">ID: <?= $item['product_id']; ?></small>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>$<?= number_format($item['price'], 2); ?></td>
                                                    <td><?= $item['quantity']; ?></td>
                                                    <td class="text-right">$<?= number_format($item['price'] * $item['quantity'], 2); ?></td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <td colspan="3" class="text-right font-weight-bold">Tổng cộng:</td>
                                                <td class="text-right font-weight-bold text-primary">$<?= number_format($order['total_amount'], 2); ?></td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row mb-4">
                        <div class="col-12">
                            <a href="orders.php" class="btn btn-secondary">
                                <i class="fas fa-arrow-left mr-1"></i> Quay lại danh sách
                            </a>
                            
                            <a href="javascript:void(0);" onclick="printOrder()" class="btn btn-primary float-right">
                                <i class="fas fa-print mr-1"></i> In đơn hàng
                            </a>
                        </div>
                    </div>
                </div>
                <!-- /.container-fluid -->
            </section>
            <!-- /.content -->
        </div>
        <!-- /.content-wrapper -->

        <footer class="main-footer">
            <div class="float-right d-none d-sm-block">
                <b>Version</b> 1.0.0
            </div>
            <strong>Copyright &copy; 2023 <a href="#">Marketplace Admin</a>.</strong> All rights reserved.
        </footer>
    </div>
    <!-- ./wrapper -->

    <!-- Print Template (hidden) -->
    <div id="print-section" style="display: none;">
        <div style="padding: 20px; font-family: Arial, sans-serif;">
            <div style="text-align: center; margin-bottom: 20px;">
                <h1 style="margin-bottom: 5px;">Chi tiết đơn hàng #<?= $order_id; ?></h1>
                <p style="color: #777;"><?= date('d/m/Y H:i', strtotime($order['order_date'])); ?></p>
            </div>
            
            <div style="margin-bottom: 30px;">
                <div style="float: left; width: 50%;">
                    <h3>Thông tin đơn hàng</h3>
                    <p><strong>Mã đơn hàng:</strong> #<?= $order_id; ?></p>
                    <p><strong>Ngày đặt hàng:</strong> <?= date('d/m/Y H:i', strtotime($order['order_date'])); ?></p>
                    <p><strong>Trạng thái:</strong> <?= $status_text; ?></p>
                    <p><strong>Tổng tiền:</strong> $<?= number_format($order['total_amount'], 2); ?></p>
                </div>
                
                <div style="float: right; width: 50%;">
                    <h3>Thông tin khách hàng</h3>
                    <p><strong>Tên khách hàng:</strong> <?= htmlspecialchars($order['customer_name']); ?></p>
                    <p><strong>Email:</strong> <?= htmlspecialchars($order['email']); ?></p>
                    <p><strong>Số điện thoại:</strong> <?= htmlspecialchars($order['number']); ?></p>
                    <p><strong>Địa chỉ giao hàng:</strong> <?= htmlspecialchars($order['shipping_address'] ?: 'Không có thông tin'); ?></p>
                </div>
                <div style="clear: both;"></div>
            </div>
            
            <h3>Sản phẩm đã đặt</h3>
            <table style="width: 100%; border-collapse: collapse; margin-bottom: 30px;">
                <thead>
                    <tr style="background-color: #f8f9fa;">
                        <th style="padding: 12px; text-align: left; border-bottom: 2px solid #ddd;">Sản phẩm</th>
                        <th style="padding: 12px; text-align: right; border-bottom: 2px solid #ddd;">Giá</th>
                        <th style="padding: 12px; text-align: center; border-bottom: 2px solid #ddd;">Số lượng</th>
                        <th style="padding: 12px; text-align: right; border-bottom: 2px solid #ddd;">Thành tiền</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($order_items as $item): ?>
                        <tr>
                            <td style="padding: 12px; text-align: left; border-bottom: 1px solid #eee;">
                                <?= htmlspecialchars($item['name']); ?>
                            </td>
                            <td style="padding: 12px; text-align: right; border-bottom: 1px solid #eee;">
                                $<?= number_format($item['price'], 2); ?>
                            </td>
                            <td style="padding: 12px; text-align: center; border-bottom: 1px solid #eee;">
                                <?= $item['quantity']; ?>
                            </td>
                            <td style="padding: 12px; text-align: right; border-bottom: 1px solid #eee;">
                                $<?= number_format($item['price'] * $item['quantity'], 2); ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="3" style="padding: 12px; text-align: right; font-weight: bold;">Tổng cộng:</td>
                        <td style="padding: 12px; text-align: right; font-weight: bold;">
                            $<?= number_format($order['total_amount'], 2); ?>
                        </td>
                    </tr>
                </tfoot>
            </table>
            
            <div style="text-align: center; margin-top: 30px; color: #777; font-size: 14px;">
                <p>Cảm ơn bạn đã mua hàng!</p>
                <p>© <?= date('Y'); ?> Marketplace</p>
            </div>
        </div>
    </div>

    <!-- jQuery -->
    <script src="./plugins/jquery/jquery.min.js"></script>
    <!-- Bootstrap 4 -->
    <script src="./plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- AdminLTE App -->
    <script src="./dist/js/adminlte.min.js"></script>
    
    <script>
        function printOrder() {
            var printContents = document.getElementById('print-section').innerHTML;
            var originalContents = document.body.innerHTML;
            
            document.body.innerHTML = printContents;
            window.print();
            document.body.innerHTML = originalContents;
        }
    </script>
</body>

</html> 