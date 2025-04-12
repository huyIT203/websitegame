<?php
include 'check.php';

$user_id = $_SESSION['id'];
$user = $query->executeQuery("SELECT * FROM accounts WHERE id = $user_id")->fetch_assoc();

// Xử lý cập nhật thông tin
$success_message = '';
$error_message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_profile'])) {
    $name = $query->validate($_POST['name']);
    $email = $query->validate($_POST['email']);
    $number = $query->validate($_POST['number']);
    
    $updateData = [
        'name' => $name,
        'email' => $email,
        'number' => $number
    ];
    
    // Kiểm tra có thay đổi mật khẩu không
    if (!empty($_POST['new_password']) && !empty($_POST['current_password'])) {
        $current_password = $_POST['current_password'];
        $new_password = $_POST['new_password'];
        $confirm_password = $_POST['confirm_password'];
        
        // Xác thực mật khẩu hiện tại
        if ($query->hashPassword($current_password) !== $user['password']) {
            $error_message = 'Mật khẩu hiện tại không đúng';
        } elseif ($new_password !== $confirm_password) {
            $error_message = 'Mật khẩu mới không khớp';
        } else {
            $updateData['password'] = $query->hashPassword($new_password);
        }
    }
    
    if (empty($error_message)) {
        $query->update('accounts', $updateData, "WHERE id = $user_id");
        $success_message = 'Cập nhật thông tin thành công';
        // Cập nhật lại thông tin người dùng
        $user = $query->executeQuery("SELECT * FROM accounts WHERE id = $user_id")->fetch_assoc();
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thông tin tài khoản</title>
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
        .profile-container {
            background-color: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            margin-bottom: 30px;
        }
        .profile-heading {
            font-size: 24px;
            color: #6c5ce7;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid #f1f1f1;
        }
        .nav-tabs .nav-link {
            color: #6c5ce7;
        }
        .nav-tabs .nav-link.active {
            color: #fff;
            background-color: #6c5ce7;
            border-color: #6c5ce7;
        }
        .btn-primary {
            background-color: #6c5ce7;
            border-color: #6c5ce7;
        }
        .btn-primary:hover {
            background-color: #5649c0;
            border-color: #5649c0;
        }
        .alert {
            border-radius: 5px;
            margin-bottom: 20px;
        }
        .form-group {
            margin-bottom: 20px;
        }
        .form-control {
            border-radius: 5px;
        }
    </style>
</head>

<body>
    <?php include './includes/header.php'; ?>

    <section class="profile-section spad">
        <div class="container">
            <div class="row">
                <div class="col-lg-3">
                    <div class="profile-container">
                        <div class="text-center mb-4">
                            <img src="./src/images/avatar-placeholder.png" alt="Avatar" class="img-fluid rounded-circle" style="width: 150px; height: 150px; object-fit: cover;">
                            <h4 class="mt-3"><?= htmlspecialchars($user['name']); ?></h4>
                            <p class="text-muted"><?= htmlspecialchars($user['role']); ?></p>
                        </div>
                        <ul class="nav nav-tabs flex-column" id="profileTab" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" id="profile-tab" data-toggle="tab" href="#profile" role="tab">Thông tin tài khoản</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="orders-tab" data-toggle="tab" href="#orders" role="tab">Đơn hàng của tôi</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="password-tab" data-toggle="tab" href="#password" role="tab">Đổi mật khẩu</a>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="col-lg-9">
                    <div class="profile-container">
                        <?php if (!empty($success_message)): ?>
                            <div class="alert alert-success"><?= $success_message; ?></div>
                        <?php endif; ?>
                        <?php if (!empty($error_message)): ?>
                            <div class="alert alert-danger"><?= $error_message; ?></div>
                        <?php endif; ?>
                        
                        <div class="tab-content" id="profileTabContent">
                            <!-- Thông tin tài khoản -->
                            <div class="tab-pane fade show active" id="profile" role="tabpanel">
                                <h3 class="profile-heading">Thông tin tài khoản</h3>
                                <form action="" method="POST">
                                    <div class="form-group">
                                        <label for="name">Họ và tên</label>
                                        <input type="text" class="form-control" id="name" name="name" value="<?= htmlspecialchars($user['name']); ?>">
                                    </div>
                                    <div class="form-group">
                                        <label for="email">Email</label>
                                        <input type="email" class="form-control" id="email" name="email" value="<?= htmlspecialchars($user['email']); ?>">
                                    </div>
                                    <div class="form-group">
                                        <label for="username">Tên đăng nhập</label>
                                        <input type="text" class="form-control" id="username" value="<?= htmlspecialchars($user['username']); ?>" readonly>
                                    </div>
                                    <div class="form-group">
                                        <label for="number">Số điện thoại</label>
                                        <input type="text" class="form-control" id="number" name="number" value="<?= htmlspecialchars($user['number']); ?>">
                                    </div>
                                    <button type="submit" name="update_profile" class="btn btn-primary">Cập nhật thông tin</button>
                                </form>
                            </div>
                            
                            <!-- Đơn hàng của tôi -->
                            <div class="tab-pane fade" id="orders" role="tabpanel">
                                <h3 class="profile-heading">Đơn hàng của tôi</h3>
                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>Mã đơn hàng</th>
                                                <th>Ngày đặt</th>
                                                <th>Tổng tiền</th>
                                                <th>Trạng thái</th>
                                                <th>Hành động</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $orders = $query->select('orders', '*', "WHERE user_id = $user_id ORDER BY order_date DESC");
                                            if (count($orders) > 0):
                                                foreach ($orders as $order):
                                            ?>
                                            <tr>
                                                <td>#<?= $order['id']; ?></td>
                                                <td><?= date('d/m/Y H:i', strtotime($order['order_date'])); ?></td>
                                                <td>$<?= number_format($order['total_amount'], 2); ?></td>
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
                                                    <span class="badge <?= $status_class; ?>"><?= $status_text; ?></span>
                                                </td>
                                                <td>
                                                    <a href="order_detail.php?id=<?= $order['id']; ?>" class="btn btn-sm btn-info">Chi tiết</a>
                                                </td>
                                            </tr>
                                            <?php
                                                endforeach;
                                            else:
                                            ?>
                                            <tr>
                                                <td colspan="5" class="text-center">Bạn chưa có đơn hàng nào</td>
                                            </tr>
                                            <?php endif; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            
                            <!-- Đổi mật khẩu -->
                            <div class="tab-pane fade" id="password" role="tabpanel">
                                <h3 class="profile-heading">Đổi mật khẩu</h3>
                                <form action="" method="POST">
                                    <div class="form-group">
                                        <label for="current_password">Mật khẩu hiện tại</label>
                                        <input type="password" class="form-control" id="current_password" name="current_password" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="new_password">Mật khẩu mới</label>
                                        <input type="password" class="form-control" id="new_password" name="new_password" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="confirm_password">Xác nhận mật khẩu mới</label>
                                        <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                                    </div>
                                    <button type="submit" name="update_profile" class="btn btn-primary">Cập nhật mật khẩu</button>
                                </form>
                            </div>
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
        // Check if the hash is #orders and show notification
        document.addEventListener('DOMContentLoaded', function() {
            if(window.location.hash === '#orders') {
                // Activate the orders tab
                document.getElementById('orders-tab').click();
                
                // Show notification only if coming from checkout (check for referrer)
                const referrer = document.referrer;
                if(referrer.includes('checkout.php')) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Thank you for your order!',
                        text: 'You can track your order status here.',
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 5000
                    });
                }
            }
        });
    </script>
</body>

</html> 