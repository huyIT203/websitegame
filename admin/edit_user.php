<?php
include 'check.php';

if (!isset($_GET['id']) || !isset($_GET['role'])) {
    header("Location: ./");
    exit;
}

$userId = $_GET['id'];
$role = $_GET['role'];

$user = $query->select('accounts', '*', "WHERE id = '$userId'");
if (empty($user)) {
    header("Location: ./");
    exit;
}
$user = $user[0];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $username = $_POST['username'];
    $number = $_POST['number'];
    
    $data = [
        'name' => $name,
        'email' => $email,
        'username' => $username,
        'number' => $number
    ];

    // Only update password if a new one is provided
    if (!empty($_POST['password'])) {
        $data['password'] = password_hash($_POST['password'], PASSWORD_DEFAULT);
    }

    $query->update('accounts', $data, "WHERE id = '$userId'");

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
    <title>Edit <?php echo ucfirst($role); ?></title>
    <?php include 'includes/css.php'; ?>
</head>
<body class="hold-transition sidebar-mini">
<div class="wrapper">
    <?php include 'includes/navbar.php'; ?>
    <?php
    include 'includes/aside.php';
    active('users', $role == 'seller' ? 'sellers' : 'users');
    ?>

    <div class="content-wrapper">
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-6">
                        <div class="card card-primary mt-4">
                            <div class="card-header">
                                <h3 class="card-title">Edit <?php echo ucfirst($role); ?></h3>
                            </div>
                            <form method="POST">
                                <div class="card-body">
                                    <div class="form-group">
                                        <label for="name">Name</label>
                                        <input type="text" class="form-control" id="name" name="name" value="<?php echo htmlspecialchars($user['name']); ?>" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="email">Email</label>
                                        <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="username">Username</label>
                                        <input type="text" class="form-control" id="username" name="username" value="<?php echo htmlspecialchars($user['username']); ?>" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="number">Phone Number</label>
                                        <input type="text" class="form-control" id="number" name="number" value="<?php echo htmlspecialchars($user['number']); ?>" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="password">New Password (leave blank to keep current)</label>
                                        <input type="password" class="form-control" id="password" name="password">
                                    </div>
                                </div>
                                <div class="card-footer">
                                    <button type="submit" class="btn btn-primary">Update</button>
                                    <a href="<?php echo $role == 'seller' ? './' : './users.php'; ?>" class="btn btn-default">Cancel</a>
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
