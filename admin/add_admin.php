<?php
include 'check.php';

// Check if current user is admin
if ($_SESSION['role'] !== 'admin') {
    header('Location: ../index.php');
    exit;
}

$success = false;
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate form data
    $name = $query->validate($_POST['name']);
    $email = $query->validate($_POST['email']);
    $username = $query->validate($_POST['username']);
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirm_password'];
    $number = $query->validate($_POST['number']);
    
    // Validate input
    if (empty($name) || empty($email) || empty($username) || empty($password) || empty($confirmPassword)) {
        $error = 'Vui lòng điền đầy đủ thông tin.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Email không hợp lệ.';
    } elseif ($password !== $confirmPassword) {
        $error = 'Mật khẩu và xác nhận mật khẩu không khớp.';
    } elseif (strlen($password) < 6) {
        $error = 'Mật khẩu phải có ít nhất 6 ký tự.';
    } else {
        // Check if username or email already exists
        $exists = $query->executeQuery("SELECT id FROM accounts WHERE username = '$username' OR email = '$email'")->num_rows;
        if ($exists > 0) {
            $error = 'Tên đăng nhập hoặc email đã tồn tại.';
        } else {
            // Hash password
            $hashedPassword = $query->hashPassword($password);
            
            // Insert new admin
            $data = [
                'name' => $name,
                'email' => $email,
                'username' => $username,
                'password' => $hashedPassword,
                'number' => $number,
                'role' => 'admin'
            ];
            
            $inserted = $query->insert('accounts', $data);
            
            if ($inserted) {
                $success = true;
                // Reset form data
                $name = $email = $username = $number = '';
            } else {
                $error = 'Đã xảy ra lỗi khi tạo tài khoản.';
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thêm tài khoản Admin</title>
    <?php include 'includes/css.php'; ?>
    <style>
        .form-container {
            max-width: 800px;
            margin: 0 auto;
        }
        .required-field::after {
            content: "*";
            color: red;
            margin-left: 4px;
        }
        .password-strength {
            margin-top: 5px;
            height: 5px;
            border-radius: 3px;
        }
        .password-feedback {
            font-size: 0.8rem;
            margin-top: 5px;
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
                            <h1>Thêm tài khoản Admin</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                                <li class="breadcrumb-item"><a href="users.php">Quản lý người dùng</a></li>
                                <li class="breadcrumb-item active">Thêm Admin</li>
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
                                    <h3 class="card-title">Thêm tài khoản Admin mới</h3>
                                </div>
                                <div class="card-body">
                                    <?php if ($success): ?>
                                    <div class="alert alert-success">
                                        <i class="icon fas fa-check"></i> Tài khoản admin đã được tạo thành công!
                                    </div>
                                    <?php endif; ?>
                                    
                                    <?php if (!empty($error)): ?>
                                    <div class="alert alert-danger">
                                        <i class="icon fas fa-ban"></i> <?= $error ?>
                                    </div>
                                    <?php endif; ?>
                                
                                    <div class="form-container">
                                        <form action="" method="POST" id="addAdminForm">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="name" class="required-field">Họ và tên</label>
                                                        <input type="text" class="form-control" id="name" name="name" value="<?= isset($name) ? htmlspecialchars($name) : '' ?>" required>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="email" class="required-field">Email</label>
                                                        <input type="email" class="form-control" id="email" name="email" value="<?= isset($email) ? htmlspecialchars($email) : '' ?>" required>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="username" class="required-field">Tên đăng nhập</label>
                                                        <input type="text" class="form-control" id="username" name="username" value="<?= isset($username) ? htmlspecialchars($username) : '' ?>" required>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="number">Số điện thoại</label>
                                                        <input type="text" class="form-control" id="number" name="number" value="<?= isset($number) ? htmlspecialchars($number) : '' ?>">
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="password" class="required-field">Mật khẩu</label>
                                                        <input type="password" class="form-control" id="password" name="password" required>
                                                        <div class="password-strength bg-secondary"></div>
                                                        <div class="password-feedback text-muted"></div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="confirm_password" class="required-field">Xác nhận mật khẩu</label>
                                                        <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <div class="row mt-3">
                                                <div class="col-md-6">
                                                    <button type="submit" class="btn btn-primary">
                                                        <i class="fas fa-user-plus"></i> Tạo tài khoản
                                                    </button>
                                                    <a href="users.php" class="btn btn-default ml-2">
                                                        <i class="fas fa-arrow-left"></i> Quay lại
                                                    </a>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
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
    
    <script>
        $(document).ready(function() {
            // Password strength feedback
            $('#password').on('input', function() {
                const password = $(this).val();
                const strength = calculatePasswordStrength(password);
                updatePasswordFeedback(strength, password);
            });
            
            // Password confirmation validation
            $('#confirm_password').on('input', function() {
                validatePasswordMatch();
            });
            
            // Form validation before submit
            $('#addAdminForm').on('submit', function(e) {
                const password = $('#password').val();
                const confirmPassword = $('#confirm_password').val();
                
                if (password !== confirmPassword) {
                    e.preventDefault();
                    alert('Mật khẩu và xác nhận mật khẩu không khớp.');
                    return false;
                }
                
                if (password.length < 6) {
                    e.preventDefault();
                    alert('Mật khẩu phải có ít nhất 6 ký tự.');
                    return false;
                }
                
                return true;
            });
            
            function calculatePasswordStrength(password) {
                if (!password) return 0;
                
                let strength = 0;
                
                // Length check
                if (password.length >= 8) strength += 25;
                else if (password.length >= 6) strength += 10;
                
                // Complexity checks
                if (password.match(/[a-z]+/)) strength += 15;
                if (password.match(/[A-Z]+/)) strength += 20;
                if (password.match(/[0-9]+/)) strength += 20;
                if (password.match(/[!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?]+/)) strength += 20;
                
                return Math.min(100, strength);
            }
            
            function updatePasswordFeedback(strength, password) {
                const strengthBar = $('.password-strength');
                const feedback = $('.password-feedback');
                
                // Update strength bar
                strengthBar.removeClass('bg-danger bg-warning bg-info bg-success bg-secondary');
                
                if (strength > 80) {
                    strengthBar.addClass('bg-success');
                    feedback.text('Mật khẩu mạnh');
                    feedback.removeClass('text-danger text-warning text-info text-success text-muted').addClass('text-success');
                } else if (strength > 60) {
                    strengthBar.addClass('bg-info');
                    feedback.text('Mật khẩu khá mạnh');
                    feedback.removeClass('text-danger text-warning text-info text-success text-muted').addClass('text-info');
                } else if (strength > 30) {
                    strengthBar.addClass('bg-warning');
                    feedback.text('Mật khẩu trung bình');
                    feedback.removeClass('text-danger text-warning text-info text-success text-muted').addClass('text-warning');
                } else if (password.length > 0) {
                    strengthBar.addClass('bg-danger');
                    feedback.text('Mật khẩu yếu');
                    feedback.removeClass('text-danger text-warning text-info text-success text-muted').addClass('text-danger');
                } else {
                    strengthBar.addClass('bg-secondary');
                    feedback.text('');
                    feedback.removeClass('text-danger text-warning text-info text-success').addClass('text-muted');
                }
                
                // Set the width of the strength bar
                strengthBar.css('width', strength + '%');
            }
            
            function validatePasswordMatch() {
                const password = $('#password').val();
                const confirmPassword = $('#confirm_password').val();
                
                if (confirmPassword.length > 0) {
                    if (password !== confirmPassword) {
                        $('#confirm_password').addClass('is-invalid');
                    } else {
                        $('#confirm_password').removeClass('is-invalid').addClass('is-valid');
                    }
                } else {
                    $('#confirm_password').removeClass('is-invalid is-valid');
                }
            }
        });
    </script>
</body>
</html> 