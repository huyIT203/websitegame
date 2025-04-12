<?php
include 'check.php';

// Get search query and type from URL
$search_query = isset($_GET['query']) ? $query->validate($_GET['query']) : '';
$search_type = isset($_GET['type']) ? $query->validate($_GET['type']) : 'users';

// Valid search types
$valid_types = ['users', 'orders'];

// Validate search type
if (!in_array($search_type, $valid_types)) {
    $search_type = 'users';
}

// Initialize results
$search_results = [];
$result_count = 0;

// Perform search based on type
if (!empty($search_query)) {
    switch ($search_type) {
        case 'users':
            // Search users by name, email, username
            $search_sql = "SELECT * FROM accounts WHERE 
                           name LIKE '%$search_query%' OR 
                           email LIKE '%$search_query%' OR 
                           username LIKE '%$search_query%'";
            $search_results = $query->executeQuery($search_sql)->fetch_all(MYSQLI_ASSOC);
            break;
            
        case 'orders':
            // Search orders by id, user email, status
            $search_sql = "SELECT o.*, a.name, a.email 
                           FROM orders o 
                           JOIN accounts a ON o.user_id = a.id 
                           WHERE o.id LIKE '%$search_query%' OR 
                                 a.name LIKE '%$search_query%' OR 
                                 a.email LIKE '%$search_query%' OR 
                                 o.status LIKE '%$search_query%'";
            $search_results = $query->executeQuery($search_sql)->fetch_all(MYSQLI_ASSOC);
            break;
    }
    
    $result_count = count($search_results);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Search</title>
    <?php include 'includes/css.php'; ?>
    <style>
        .search-container {
            margin-bottom: 30px;
        }
        .search-form {
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        .search-results {
            margin-top: 30px;
        }
        .result-count {
            font-size: 1.1rem;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 1px solid #eee;
        }
        .nav-tabs .nav-item .nav-link {
            font-weight: 500;
        }
        .nav-tabs .nav-item .nav-link.active {
            background-color: #6c5ce7;
            color: white;
            border-color: #6c5ce7;
        }
        .table th {
            background-color: #f1f1f1;
        }
    </style>
</head>
<body class="hold-transition sidebar-mini layout-fixed">
    <div class="wrapper">
        <?php include 'includes/navbar.php'; ?>
        <?php include 'includes/aside.php'; ?>

        <div class="content-wrapper">
            <section class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1>Tìm kiếm</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                                <li class="breadcrumb-item active">Tìm kiếm</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </section>

            <section class="content">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">Tìm kiếm</h3>
                                </div>
                                <div class="card-body">
                                    <div class="search-container">
                                        <div class="search-form">
                                            <form action="" method="GET">
                                                <div class="row">
                                                    <div class="col-md-5">
                                                        <div class="form-group">
                                                            <label for="search_query">Từ khóa tìm kiếm</label>
                                                            <input type="text" class="form-control" id="search_query" name="query" value="<?= htmlspecialchars($search_query) ?>" placeholder="Nhập từ khóa tìm kiếm..." required>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-5">
                                                        <div class="form-group">
                                                            <label for="search_type">Loại tìm kiếm</label>
                                                            <select class="form-control" id="search_type" name="type">
                                                                <option value="users" <?= $search_type == 'users' ? 'selected' : '' ?>>Người dùng</option>
                                                                <option value="orders" <?= $search_type == 'orders' ? 'selected' : '' ?>>Đơn hàng</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-2">
                                                        <div class="form-group">
                                                            <label>&nbsp;</label>
                                                            <button type="submit" class="btn btn-primary btn-block">
                                                                <i class="fas fa-search"></i> Tìm kiếm
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>

                                    <?php if (!empty($search_query)): ?>
                                    <div class="search-results">
                                        <div class="result-count">
                                            <strong><?= $result_count ?></strong> kết quả được tìm thấy cho từ khóa "<strong><?= htmlspecialchars($search_query) ?></strong>"
                                        </div>

                                        <?php if ($search_type == 'users' && $result_count > 0): ?>
                                        <div class="table-responsive">
                                            <table class="table table-bordered table-striped">
                                                <thead>
                                                    <tr>
                                                        <th>ID</th>
                                                        <th>Họ tên</th>
                                                        <th>Email</th>
                                                        <th>Tên đăng nhập</th>
                                                        <th>Vai trò</th>
                                                        <th>Hành động</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php foreach ($search_results as $user): ?>
                                                    <tr>
                                                        <td><?= $user['id'] ?></td>
                                                        <td><?= htmlspecialchars($user['name']) ?></td>
                                                        <td><?= htmlspecialchars($user['email']) ?></td>
                                                        <td><?= htmlspecialchars($user['username']) ?></td>
                                                        <td>
                                                            <span class="badge <?= $user['role'] == 'admin' ? 'badge-danger' : ($user['role'] == 'seller' ? 'badge-warning' : 'badge-info') ?>">
                                                                <?= htmlspecialchars($user['role']) ?>
                                                            </span>
                                                        </td>
                                                        <td>
                                                            <a href="edit_user.php?id=<?= $user['id'] ?>" class="btn btn-primary btn-sm">
                                                                <i class="fas fa-edit"></i> Sửa
                                                            </a>
                                                        </td>
                                                    </tr>
                                                    <?php endforeach; ?>
                                                </tbody>
                                            </table>
                                        </div>
                                        <?php elseif ($search_type == 'orders' && $result_count > 0): ?>
                                        <div class="table-responsive">
                                            <table class="table table-bordered table-striped">
                                                <thead>
                                                    <tr>
                                                        <th>Mã đơn hàng</th>
                                                        <th>Người mua</th>
                                                        <th>Ngày đặt</th>
                                                        <th>Tổng tiền</th>
                                                        <th>Trạng thái</th>
                                                        <th>Hành động</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php foreach ($search_results as $order): ?>
                                                    <tr>
                                                        <td>#<?= $order['id'] ?></td>
                                                        <td>
                                                            <?= htmlspecialchars($order['name']) ?><br>
                                                            <small><?= htmlspecialchars($order['email']) ?></small>
                                                        </td>
                                                        <td><?= date('d/m/Y H:i', strtotime($order['order_date'])) ?></td>
                                                        <td>$<?= number_format($order['total_amount'], 2) ?></td>
                                                        <td>
                                                            <?php
                                                            $status_class = '';
                                                            $status_text = '';
                                                            switch ($order['status']) {
                                                                case 'pending':
                                                                    $status_class = 'badge-warning';
                                                                    $status_text = 'Đang xử lý';
                                                                    break;
                                                                case 'processing':
                                                                    $status_class = 'badge-info';
                                                                    $status_text = 'Đang chuẩn bị';
                                                                    break;
                                                                case 'shipped':
                                                                    $status_class = 'badge-primary';
                                                                    $status_text = 'Đang giao hàng';
                                                                    break;
                                                                case 'delivered':
                                                                    $status_class = 'badge-success';
                                                                    $status_text = 'Đã giao hàng';
                                                                    break;
                                                                case 'cancelled':
                                                                    $status_class = 'badge-danger';
                                                                    $status_text = 'Đã hủy';
                                                                    break;
                                                            }
                                                            ?>
                                                            <span class="badge <?= $status_class ?>"><?= $status_text ?></span>
                                                        </td>
                                                        <td>
                                                            <a href="order_detail.php?id=<?= $order['id'] ?>" class="btn btn-info btn-sm">
                                                                <i class="fas fa-eye"></i> Chi tiết
                                                            </a>
                                                        </td>
                                                    </tr>
                                                    <?php endforeach; ?>
                                                </tbody>
                                            </table>
                                        </div>
                                        <?php else: ?>
                                        <div class="alert alert-info">
                                            <i class="fas fa-info-circle"></i> Không tìm thấy kết quả nào phù hợp với từ khóa tìm kiếm.
                                        </div>
                                        <?php endif; ?>
                                    </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>

        <?php include 'includes/footer.php'; ?>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/4.6.0/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/admin-lte/3.1.0/js/adminlte.min.js"></script>
</body>
</html> 