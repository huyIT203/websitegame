<?php
include 'check.php';

// Xử lý thay đổi trạng thái đơn hàng
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_status'])) {
    $order_id = (int)$_POST['order_id'];
    $status = $query->validate($_POST['status']);
    
    try {
        $query->update('orders', ['status' => $status], "WHERE id = $order_id");
        $success_message = 'Cập nhật trạng thái đơn hàng thành công';
    } catch (Exception $e) {
        $error_message = 'Có lỗi xảy ra: ' . $e->getMessage();
    }
}

// Filter orders
$status_filter = isset($_GET['status']) ? $query->validate($_GET['status']) : '';
$date_from = isset($_GET['date_from']) ? $query->validate($_GET['date_from']) : '';
$date_to = isset($_GET['date_to']) ? $query->validate($_GET['date_to']) : '';

// Build the query
$filter_condition = '';
if (!empty($status_filter)) {
    $filter_condition .= " AND o.status = '$status_filter'";
}
if (!empty($date_from)) {
    $filter_condition .= " AND o.order_date >= '$date_from 00:00:00'";
}
if (!empty($date_to)) {
    $filter_condition .= " AND o.order_date <= '$date_to 23:59:59'";
}

// Get total orders count for pagination
$total_orders = $query->executeQuery("SELECT COUNT(*) as total FROM orders o WHERE 1=1 $filter_condition")->fetch_assoc()['total'];

// Pagination
$limit = 10;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;
$total_pages = ceil($total_orders / $limit);

// Get orders with user info
$orders = $query->executeQuery("
    SELECT o.*, a.name as customer_name, a.email, a.number
    FROM orders o
    JOIN accounts a ON o.user_id = a.id
    WHERE 1=1 $filter_condition
    ORDER BY o.order_date DESC
    LIMIT $offset, $limit
")->fetch_all(MYSQLI_ASSOC);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin | Quản lý đơn hàng</title>
    
    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="./plugins/fontawesome-free/css/all.min.css">
    <!-- DataTables -->
    <link rel="stylesheet" href="./plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="./dist/css/adminlte.min.css">
    <style>
        .status-badge {
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
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
        .table th {
            vertical-align: middle;
        }
        .filter-form {
            padding: 15px;
            background: #f8f9fa;
            border-radius: 5px;
            margin-bottom: 20px;
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
                            <h1>Quản lý đơn hàng</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="index.php">Trang chủ</a></li>
                                <li class="breadcrumb-item active">Đơn hàng</li>
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
                    
                    <!-- Filter Form -->
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">Lọc đơn hàng</h3>
                                </div>
                                <div class="card-body filter-form">
                                    <form method="GET" action="orders.php">
                                        <div class="row">
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label>Trạng thái:</label>
                                                    <select class="form-control" name="status">
                                                        <option value="">Tất cả</option>
                                                        <option value="pending" <?= $status_filter === 'pending' ? 'selected' : ''; ?>>Đang xử lý</option>
                                                        <option value="processing" <?= $status_filter === 'processing' ? 'selected' : ''; ?>>Đang chuẩn bị</option>
                                                        <option value="shipped" <?= $status_filter === 'shipped' ? 'selected' : ''; ?>>Đang giao hàng</option>
                                                        <option value="delivered" <?= $status_filter === 'delivered' ? 'selected' : ''; ?>>Đã giao hàng</option>
                                                        <option value="cancelled" <?= $status_filter === 'cancelled' ? 'selected' : ''; ?>>Đã hủy</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label>Từ ngày:</label>
                                                    <input type="date" class="form-control" name="date_from" value="<?= $date_from; ?>">
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label>Đến ngày:</label>
                                                    <input type="date" class="form-control" name="date_to" value="<?= $date_to; ?>">
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label>&nbsp;</label>
                                                    <div>
                                                        <button type="submit" class="btn btn-primary">Lọc</button>
                                                        <a href="orders.php" class="btn btn-default">Đặt lại</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">Danh sách đơn hàng</h3>
                                </div>
                                <!-- /.card-header -->
                                <div class="card-body table-responsive">
                                    <table id="ordersTable" class="table table-bordered table-striped">
                                        <thead>
                                            <tr>
                                                <th>Mã đơn hàng</th>
                                                <th>Tên khách hàng</th>
                                                <th>Ngày đặt</th>
                                                <th>Tổng tiền</th>
                                                <th>Trạng thái</th>
                                                <th>Thao tác</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($orders as $order): ?>
                                                <tr>
                                                    <td>#<?= $order['id']; ?></td>
                                                    <td>
                                                        <?= htmlspecialchars($order['customer_name']); ?><br>
                                                        <small><?= htmlspecialchars($order['email']); ?></small><br>
                                                        <small><?= htmlspecialchars($order['number']); ?></small>
                                                    </td>
                                                    <td><?= date('d/m/Y H:i', strtotime($order['order_date'])); ?></td>
                                                    <td>$<?= number_format($order['total_amount'], 2); ?></td>
                                                    <td>
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
                                                    </td>
                                                    <td>
                                                        <div class="btn-group">
                                                            <a href="order_detail.php?id=<?= $order['id']; ?>" class="btn btn-info btn-sm">
                                                                <i class="fas fa-eye"></i> Xem
                                                            </a>
                                                            <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#updateStatusModal_<?= $order['id']; ?>">
                                                                <i class="fas fa-edit"></i> Cập nhật
                                                            </button>
                                                        </div>
                                                        
                                                        <!-- Modal Update Status -->
                                                        <div class="modal fade" id="updateStatusModal_<?= $order['id']; ?>" tabindex="-1" role="dialog" aria-labelledby="updateStatusModalLabel" aria-hidden="true">
                                                            <div class="modal-dialog" role="document">
                                                                <div class="modal-content">
                                                                    <div class="modal-header">
                                                                        <h5 class="modal-title" id="updateStatusModalLabel">Cập nhật trạng thái đơn hàng #<?= $order['id']; ?></h5>
                                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                            <span aria-hidden="true">&times;</span>
                                                                        </button>
                                                                    </div>
                                                                    <form method="POST">
                                                                        <div class="modal-body">
                                                                            <input type="hidden" name="order_id" value="<?= $order['id']; ?>">
                                                                            <div class="form-group">
                                                                                <label>Trạng thái mới:</label>
                                                                                <select class="form-control" name="status">
                                                                                    <option value="pending" <?= $order['status'] === 'pending' ? 'selected' : ''; ?>>Đang xử lý</option>
                                                                                    <option value="processing" <?= $order['status'] === 'processing' ? 'selected' : ''; ?>>Đang chuẩn bị</option>
                                                                                    <option value="shipped" <?= $order['status'] === 'shipped' ? 'selected' : ''; ?>>Đang giao hàng</option>
                                                                                    <option value="delivered" <?= $order['status'] === 'delivered' ? 'selected' : ''; ?>>Đã giao hàng</option>
                                                                                    <option value="cancelled" <?= $order['status'] === 'cancelled' ? 'selected' : ''; ?>>Đã hủy</option>
                                                                                </select>
                                                                            </div>
                                                                        </div>
                                                                        <div class="modal-footer">
                                                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng</button>
                                                                            <button type="submit" name="update_status" class="btn btn-primary">Cập nhật</button>
                                                                        </div>
                                                                    </form>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                                <!-- /.card-body -->
                                
                                <!-- Pagination -->
                                <div class="card-footer clearfix">
                                    <ul class="pagination pagination-sm m-0 float-right">
                                        <?php if ($page > 1): ?>
                                            <li class="page-item"><a class="page-link" href="?page=1<?= !empty($status_filter) ? '&status='.$status_filter : ''; ?><?= !empty($date_from) ? '&date_from='.$date_from : ''; ?><?= !empty($date_to) ? '&date_to='.$date_to : ''; ?>">&laquo;</a></li>
                                        <?php endif; ?>
                                        
                                        <?php for ($i = max(1, $page - 2); $i <= min($page + 2, $total_pages); $i++): ?>
                                            <li class="page-item <?= $i == $page ? 'active' : ''; ?>">
                                                <a class="page-link" href="?page=<?= $i; ?><?= !empty($status_filter) ? '&status='.$status_filter : ''; ?><?= !empty($date_from) ? '&date_from='.$date_from : ''; ?><?= !empty($date_to) ? '&date_to='.$date_to : ''; ?>"><?= $i; ?></a>
                                            </li>
                                        <?php endfor; ?>
                                        
                                        <?php if ($page < $total_pages): ?>
                                            <li class="page-item"><a class="page-link" href="?page=<?= $total_pages; ?><?= !empty($status_filter) ? '&status='.$status_filter : ''; ?><?= !empty($date_from) ? '&date_from='.$date_from : ''; ?><?= !empty($date_to) ? '&date_to='.$date_to : ''; ?>">&raquo;</a></li>
                                        <?php endif; ?>
                                    </ul>
                                </div>
                            </div>
                            <!-- /.card -->
                        </div>
                        <!-- /.col -->
                    </div>
                    <!-- /.row -->
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

    <!-- jQuery -->
    <script src="./plugins/jquery/jquery.min.js"></script>
    <!-- Bootstrap 4 -->
    <script src="./plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- DataTables  & Plugins -->
    <script src="./plugins/datatables/jquery.dataTables.min.js"></script>
    <script src="./plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
    <!-- AdminLTE App -->
    <script src="./dist/js/adminlte.min.js"></script>
    
    <script>
        $(function () {
            $('#ordersTable').DataTable({
                "paging": false,
                "lengthChange": false,
                "searching": true,
                "ordering": true,
                "info": false,
                "autoWidth": false,
                "responsive": true,
            });
        });
    </script>
</body>

</html> 