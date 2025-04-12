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
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin | Thông tin tài khoản</title>
    
    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="./plugins/fontawesome-free/css/all.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="./dist/css/adminlte.min.css">
    <style>
        .profile-pic {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            object-fit: cover;
            margin-bottom: 15px;
        }
        .card-primary.card-outline {
            border-top: 3px solid #6c5ce7;
        }
        .btn-primary {
            background-color: #6c5ce7;
            border-color: #6c5ce7;
        }
        .btn-primary:hover {
            background-color: #5649c0;
            border-color: #5649c0;
        }
        .nav-pills .nav-link.active {
            background-color: #6c5ce7;
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
                            <h1>Thông tin tài khoản</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="index.php">Trang chủ</a></li>
                                <li class="breadcrumb-item active">Thông tin tài khoản</li>
                            </ol>
                        </div>
                    </div>
                </div><!-- /.container-fluid -->
            </section>

            <!-- Main content -->
            <section class="content">
                <div class="container-fluid">
                    <?php if (!empty($success_message)): ?>
                        <div class="alert alert-success"><?= $success_message; ?></div>
                    <?php endif; ?>
                    <?php if (!empty($error_message)): ?>
                        <div class="alert alert-danger"><?= $error_message; ?></div>
                    <?php endif; ?>
                    
                    <div class="row">
                        <div class="col-md-3">
                            <!-- Profile Image -->
                            <div class="card card-primary card-outline">
                                <div class="card-body box-profile">
                                    <div class="text-center">
                                        <img class="profile-pic" src="../src/images/avatar-placeholder.png" alt="User profile picture">
                                    </div>

                                    <h3 class="profile-username text-center"><?= htmlspecialchars($user['name']); ?></h3>
                                    <p class="text-muted text-center"><?= ucfirst(htmlspecialchars($user['role'])); ?></p>
                                </div>
                                <!-- /.card-body -->
                            </div>
                            <!-- /.card -->
                        </div>
                        <!-- /.col -->
                        <div class="col-md-9">
                            <div class="card">
                                <div class="card-header p-2">
                                    <ul class="nav nav-pills">
                                        <li class="nav-item"><a class="nav-link active" href="#profile" data-toggle="tab">Thông tin cá nhân</a></li>
                                        <li class="nav-item"><a class="nav-link" href="#password" data-toggle="tab">Đổi mật khẩu</a></li>
                                    </ul>
                                </div><!-- /.card-header -->
                                <div class="card-body">
                                    <div class="tab-content">
                                        <!-- Profile Tab -->
                                        <div class="active tab-pane" id="profile">
                                            <form class="form-horizontal" method="POST">
                                                <div class="form-group row">
                                                    <label for="name" class="col-sm-2 col-form-label">Họ và tên</label>
                                                    <div class="col-sm-10">
                                                        <input type="text" class="form-control" id="name" name="name" value="<?= htmlspecialchars($user['name']); ?>">
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label for="email" class="col-sm-2 col-form-label">Email</label>
                                                    <div class="col-sm-10">
                                                        <input type="email" class="form-control" id="email" name="email" value="<?= htmlspecialchars($user['email']); ?>">
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label for="username" class="col-sm-2 col-form-label">Tên đăng nhập</label>
                                                    <div class="col-sm-10">
                                                        <input type="text" class="form-control" id="username" value="<?= htmlspecialchars($user['username']); ?>" readonly>
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label for="number" class="col-sm-2 col-form-label">Số điện thoại</label>
                                                    <div class="col-sm-10">
                                                        <input type="text" class="form-control" id="number" name="number" value="<?= htmlspecialchars($user['number']); ?>">
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <div class="offset-sm-2 col-sm-10">
                                                        <button type="submit" name="update_profile" class="btn btn-primary">Cập nhật thông tin</button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                        <!-- /.tab-pane -->

                                        <!-- Password Change Tab -->
                                        <div class="tab-pane" id="password">
                                            <form class="form-horizontal" method="POST">
                                                <div class="form-group row">
                                                    <label for="current_password" class="col-sm-3 col-form-label">Mật khẩu hiện tại</label>
                                                    <div class="col-sm-9">
                                                        <input type="password" class="form-control" id="current_password" name="current_password" required>
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label for="new_password" class="col-sm-3 col-form-label">Mật khẩu mới</label>
                                                    <div class="col-sm-9">
                                                        <input type="password" class="form-control" id="new_password" name="new_password" required>
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label for="confirm_password" class="col-sm-3 col-form-label">Xác nhận mật khẩu mới</label>
                                                    <div class="col-sm-9">
                                                        <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <div class="offset-sm-3 col-sm-9">
                                                        <button type="submit" name="update_profile" class="btn btn-primary">Đổi mật khẩu</button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                        <!-- /.tab-pane -->
                                    </div>
                                    <!-- /.tab-content -->
                                </div><!-- /.card-body -->
                            </div>
                            <!-- /.card -->
                        </div>
                        <!-- /.col -->
                    </div>
                    <!-- /.row -->
                </div><!-- /.container-fluid -->
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
    <!-- AdminLTE App -->
    <script src="./dist/js/adminlte.min.js"></script>
</body>

</html> 