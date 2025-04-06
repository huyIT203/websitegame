<?php
include 'check.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $username = $_POST['username'];
    $number = $_POST['number'];
    // Sử dụng BCRYPT để mã hóa mật khẩu - giống như trang đăng ký
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $role = $_POST['role'];
    $status = 'active';

    // Kiểm tra email đã tồn tại chưa
    $check_email = $query->select('accounts', '*', "WHERE email = '$email'");
    if (!empty($check_email)) {
        echo "<script>alert('Email already exists!'); window.location.href='add_user.php?role=" . $role . "';</script>";
        exit;
    }

    // Kiểm tra username đã tồn tại chưa
    $check_username = $query->select('accounts', '*', "WHERE username = '$username'");
    if (!empty($check_username)) {
        echo "<script>alert('Username already exists!'); window.location.href='add_user.php?role=" . $role . "';</script>";
        exit;
    }

    $data = [
        'name' => $name,
        'email' => $email,
        'username' => $username,
        'number' => $number,
        'password' => $password,
        'role' => $role,
        'status' => $status
    ];

    $query->insert('accounts', $data);

    if ($role == 'seller') {
        header("Location: ./");
    } else {
        header("Location: ./users.php");
    }
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Add User/Seller</title>
    <?php include 'includes/css.php'; ?>
</head>
<body class="hold-transition sidebar-mini">
<div class="wrapper">
    <?php include 'includes/navbar.php'; ?>
    <?php
    include 'includes/aside.php';
    active('users', isset($_GET['role']) && $_GET['role'] == 'seller' ? 'sellers' : 'users');
    ?>

    <div class="content-wrapper">
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-6">
                        <div class="card card-primary mt-4">
                            <div class="card-header">
                                <h3 class="card-title">Add <?php echo ucfirst($_GET['role']); ?></h3>
                            </div>
                            <form method="POST">
                                <div class="card-body">
                                    <div class="form-group">
                                        <label for="name">Name</label>
                                        <input type="text" class="form-control" id="name" name="name" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="email">Email</label>
                                        <input type="email" class="form-control" id="email" name="email" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="username">Username</label>
                                        <input type="text" class="form-control" id="username" name="username" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="number">Phone Number</label>
                                        <input type="text" class="form-control" id="number" name="number" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="password">Password</label>
                                        <input type="password" class="form-control" id="password" name="password" required>
                                    </div>
                                    <input type="hidden" name="role" value="<?php echo $_GET['role']; ?>">
                                </div>
                                <div class="card-footer">
                                    <button type="submit" class="btn btn-primary">Submit</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <?php include 'includes/footer.php'; ?>
</div>

<script src="../src/js/jquery.min.js"></script>
<script src="../src/js/adminlte.js"></script>
<script src="../src/js/bootstrap.bundle.min.js"></script>
</body>
</html>
