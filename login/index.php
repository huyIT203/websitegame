<?php
session_start();

include '../config.php';
$query = new Database();

if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
    if ($_SESSION['role'] == 'admin') {
        header("Location: ../admin/");
        exit;
    } else if ($_SESSION['role'] == 'seller') {
        header("Location: ../seller/");
    } else {
        header("Location: ../");
        exit;
    }
}

if (isset($_COOKIE['username']) && isset($_COOKIE['session_token'])) {

    if (session_id() !== $_COOKIE['session_token']) {
        session_write_close();
        session_id($_COOKIE['session_token']);
        session_start();
    }

    $username = $_COOKIE['username'];

    $result = $query->select('accounts', 'id, role', "WHERE username = '$username'");
 
    if (!empty($result)) {
        $user = $result[0];

        $_SESSION['loggedin'] = true;
        $_SESSION['username'] = $_COOKIE['username'];
        $_SESSION['id'] = $user['id'];
        $_SESSION['role'] = $user['role'];

        if ($user['role'] == 'admin') {
            header("Location: ../admin/");
            exit;
        } else if ($user['role'] == 'seller') {
            header("Location: ../seller/");
        } else {
            header("Location: ../");
            exit;
        }
    }
}

$error = '';

if (isset($_POST['submit'])) {
    $username = isset($_POST['username']) ? trim($_POST['username']) : '';
    $password = isset($_POST['password']) ? trim($_POST['password']) : '';


    if ($username && $password) {
        $user = $query->authenticate($username, $password, 'accounts');

        if ($user) {

            $_SESSION['loggedin'] = true;
            $_SESSION['id'] = isset($user[0]['id']) ? $user[0]['id'] : null;
            $_SESSION['name'] = isset($user[0]['name']) ? $user[0]['name'] : null;
            $_SESSION['number'] = isset($user[0]['number']) ? $user[0]['number'] : null;
            $_SESSION['email'] = isset($user[0]['email']) ? $user[0]['email'] : null;
            $_SESSION['username'] = isset($user[0]['username']) ? $user[0]['username'] : null;
            $_SESSION['role'] = isset($user[0]['role']) ? $user[0]['role'] : 'user';

            setcookie('username', $username, time() + (86400 * 30), "/", "", true, true);
            setcookie('session_token',  session_id(), time() + (86400 * 30), "/", "", true, true);

            if ($user[0]['role'] == 'admin') {
                header("Location: ../admin/");
                exit;
            } else if ($user[0]['role'] == 'seller') {
                header("Location: ../seller/");
            } else {
                header("Location: ../");
                exit;
            }
        } else {
            $error = "The login or password is incorrect.";
        }
    } else {
        $error = "Please fill in all the fields.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="icon" href="../favicon.ico">
    <link rel="stylesheet" href="../src/css/login.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background: linear-gradient(135deg, #6c5ce7, #a29bfe);
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0;
        }

        .login-container {
            background: white;
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
        }

        .login-logo {
            text-align: center;
            margin-bottom: 30px;
        }

        .login-logo h2 {
            color: #6c5ce7;
            font-weight: 700;
            font-size: 28px;
            margin: 0;
        }

        .login-form h3 {
            color: #2d3436;
            font-size: 24px;
            margin-bottom: 30px;
            text-align: center;
        }

        .form-group {
            margin-bottom: 25px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: #2d3436;
            font-weight: 500;
        }

        .form-control {
            width: 100%;
            padding: 15px;
            border: 1px solid #dfe6e9;
            border-radius: 8px;
            background: #f7f7f7;
            font-size: 16px;
            transition: all 0.3s;
        }

        .form-control:focus {
            border-color: #6c5ce7;
            background: white;
            box-shadow: 0 0 0 3px rgba(108, 92, 231, 0.2);
            outline: none;
        }

        .login-btn {
            background: #6c5ce7;
            color: white;
            border: none;
            padding: 15px;
            width: 100%;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
        }

        .login-btn:hover {
            background: #5649c0;
            transform: translateY(-3px);
            box-shadow: 0 7px 14px rgba(108, 92, 231, 0.3);
        }

        .register-link {
            text-align: center;
            margin-top: 20px;
            color: #636e72;
        }

        .register-link a {
            color: #6c5ce7;
            text-decoration: none;
            font-weight: 600;
        }

        .register-link a:hover {
            text-decoration: underline;
        }
    </style>
</head>

<body>
    <div class="form-container">
        <h1>Login</h1>
        <form method="post" action="">
            <div class="form-group">
                <label for="username">Username or Email</label>
                <input type="text" id="username" name="username" required maxlength="255">
                <p class="error-message" id="username-error"></p>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <div class="password-container">
                    <input type="password" id="password" name="password" required maxlength="255">
                    <button type="button" id="toggle-password" class="password-toggle"><i class="fas fa-eye"></i></button>
                </div>
            </div>
            <div class="form-group">
                <button type="submit" name="submit" id="submit">Login</button>
            </div>
        </form>

        <div class="text-center">
            <p>Don't have an account? <a href="../signup/">Sign Up</a></p>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            <?php if ($error): ?>
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: '<?php echo $error; ?>',
                    position: 'top-end',
                    toast: true,
                    showConfirmButton: false,
                    timer: 3000
                });
            <?php endif; ?>

            document.getElementById('toggle-password').addEventListener('click', function() {
                const passwordField = document.getElementById('password');
                const toggleIcon = this.querySelector('i');

                if (passwordField.type === 'password') {
                    passwordField.type = 'text';
                    toggleIcon.classList.replace('fa-eye', 'fa-eye-slash');
                } else {
                    passwordField.type = 'password';
                    toggleIcon.classList.replace('fa-eye-slash', 'fa-eye');
                }
            });

            const usernameInput = document.getElementById('username');
            const errorElement = document.getElementById('username-error');
            const usernameRegex = /^[a-zA-Z0-9_]+$/;

            usernameInput.addEventListener('input', function() {
                const usernameValue = this.value;

                if (usernameValue && !usernameRegex.test(usernameValue)) {
                    errorElement.textContent = "Username can only contain letters, numbers, and underscores!";
                } else {
                    errorElement.textContent = "";
                }
            });

            const form = document.querySelector('form');
            form.addEventListener('submit', function(event) {
                if (!usernameRegex.test(usernameInput.value)) {
                    event.preventDefault();
                    errorElement.textContent = "Username can only contain letters, numbers, and underscores!";
                }
            });
        });
    </script>

</body>

</html>