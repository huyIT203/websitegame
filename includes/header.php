<?php
$currentPage = basename($_SERVER['PHP_SELF']);
$cartItems = $query->getCartItems($_SESSION['id']);
$total_price = array_reduce($cartItems, function ($total, $item) {
    return $total + $item['total_price'];
}, 0);

function countTable($table)
{
    global $query;
    $userId = $_SESSION['id'];
    $result = $query->executeQuery("SELECT COUNT(*) AS total_elements FROM $table WHERE user_id = $userId");
    $row = $result->fetch_assoc();
    return $row['total_elements'];
}
?>

<div class="humberger__menu__overlay"></div>
<div class="humberger__menu__wrapper">
    <div class="humberger__menu__logo">
        <a href="./"><img src="./src/images/logo.png" alt=""></a>
    </div>
    <div class="humberger__menu__cart">
        <ul>
            <li><a href="./heart.php"><i class="fa fa-heart"></i> <span><?= countTable('wishes'); ?></span></a>
            </li>
            <li><a href="./shoping-cart.php"><i class="fa fa-shopping-bag"></i>
                    <span><?= countTable('cart'); ?></span></a></li>
        </ul>
        <div class="header__cart__price">Total: <span>$<?= number_format($total_price, 2); ?></span></div>
    </div>
    <div class="humberger__menu__widget">
        <div class="header__top__right__auth">
            <?php if ($_SESSION['loggedin']): ?>
                <div class="header__top__right__auth__dropdown">
                    <a href="#"><i class="fa fa-user"></i> <?= htmlspecialchars($_SESSION['name']); ?> <i class="fa fa-angle-down"></i></a>
                    <div class="header__top__right__auth__dropdown__content">
                        <a href="./profile.php"><i class="fa fa-user-circle"></i> Tài khoản</a>
                        <a href="./profile.php#orders"><i class="fa fa-shopping-basket"></i> Đơn hàng</a>
                        <a href="#" onclick="logout()"><i class="fa fa-sign-out-alt"></i> Đăng xuất</a>
                    </div>
                </div>
            <?php else: ?>
                <a href="./login/"><i class="fa fa-user"></i>Login</a>
            <?php endif; ?>
        </div>
    </div>
    <nav class="humberger__menu__nav mobile-menu">
        <ul>
            <li>
                <a href="./" class="<?= ($currentPage == 'index.php') ? 'active' : ''; ?>">Home</a>
            </li>
            <?php if ($_SESSION['loggedin']): ?>
            <li>
                <a href="./profile.php" class="<?= ($currentPage == 'profile.php') ? 'active' : ''; ?>">Tài khoản</a>
            </li>
            <li>
                <a href="./profile.php#orders" class="<?= ($currentPage == 'profile.php' && isset($_GET['tab']) && $_GET['tab'] == 'orders') ? 'active' : ''; ?>">Đơn hàng</a>
            </li>
            <?php endif; ?>
            <li>
                <a href="./heart.php" class="<?= ($currentPage == 'heart.php') ? 'active' : ''; ?>">Wish List</a>
            </li>
            <li>
                <a href="./shoping-cart.php" class="<?= ($currentPage == 'shoping-cart.php') ? 'active' : ''; ?>">Cart</a>
            </li>
        </ul>
    </nav>
    <div id="mobile-menu-wrap"></div>
    <div class="header__top__right__social">
        <a href="#"><i class="fa fa-facebook"></i></a>
        <a href="#"><i class="fa fa-twitter"></i></a>
        <a href="#"><i class="fa fa-linkedin"></i></a>
        <a href="#"><i class="fa fa-pinterest-p"></i></a>
    </div>
    <div class="humberger__menu__contact">
        <ul>
            <li><i class="fa fa-envelope"></i> phamquangnamhuy1908@gmail.com </li>
            <li>Free Shipping for all Orders over $99</li>
        </ul>
    </div>
</div>

<header class="header">
    <div class="header__top">
        <div class="container">
            <div class="row">
                <div class="col-lg-6">
                    <div class="header__top__left">
                        <ul>
                            <li><i class="fa fa-envelope"></i> phamquangnamhuy1908@gmail.com </li>
                            <li>Giao hàng miễn phí cho các đơn hàng có giá từ $99 trở lên</li>
                        </ul>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="header__top__right">
                        <div class="header__top__right__social">
                            <a href="#"><i class="fa fa-facebook"></i></a>
                            <a href="#"><i class="fa fa-twitter"></i></a>
                            <a href="#"><i class="fa fa-linkedin"></i></a>
                            <a href="#"><i class="fa fa-pinterest-p"></i></a>
                        </div>
                        <div class="header__top__right__auth">
                            <?php if ($_SESSION['loggedin']): ?>
                                <div class="header__top__right__auth__dropdown">
                                    <a href="#"><i class="fa fa-user"></i> <?= htmlspecialchars($_SESSION['name']); ?> <i class="fa fa-angle-down"></i></a>
                                    <div class="header__top__right__auth__dropdown__content">
                                        <a href="./profile.php"><i class="fa fa-user-circle"></i> Tài khoản</a>
                                        <a href="./profile.php#orders"><i class="fa fa-shopping-basket"></i> Đơn hàng</a>
                                        <a href="#" onclick="logout()"><i class="fa fa-sign-out-alt"></i> Đăng xuất</a>
                                    </div>
                                </div>
                            <?php else: ?>
                                <a href="./login/"><i class="fa fa-user"></i>Login</a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="container">
        <div class="row">
            <div class="col-lg-3">
                <div class="header__logo">
                    <a href="./"><img src="./src/images/LOGO.jpg" alt=""></a>
                </div>
            </div>
            <div class="col-lg-6">
                <nav class="header__menu">
                    <ul>
                        <li>
                            <a href="./" class="<?= ($currentPage == 'index.php') ? 'active' : ''; ?>">Home</a>
                        </li>
                        <?php if ($_SESSION['loggedin']): ?>
                        <li>
                            <a href="./profile.php" class="<?= ($currentPage == 'profile.php') ? 'active' : ''; ?>">Tài khoản</a>
                        </li>
                        <li>
                            <a href="./profile.php#orders" class="<?= ($currentPage == 'profile.php' && isset($_GET['tab']) && $_GET['tab'] == 'orders') ? 'active' : ''; ?>">Đơn hàng</a>
                        </li>
                        <?php endif; ?>
                        <li>
                            <a href="./heart.php" class="<?= ($currentPage == 'heart.php') ? 'active' : ''; ?>">Wish List</a>
                        </li>
                        <li>
                            <a href="./shoping-cart.php" class="<?= ($currentPage == 'shoping-cart.php') ? 'active' : ''; ?>">Cart</a>
                        </li>
                    </ul>
                </nav>
            </div>

            <div class="col-lg-3">
                <div class="header__cart">
                    <ul>
                        <li><a href="./heart.php"><i class="fa fa-heart"></i>
                                <span><?= countTable('wishes'); ?></span></a></li>
                        <li><a href="./shoping-cart.php"><i class="fa fa-shopping-bag"></i>
                                <span><?= countTable('cart'); ?></span></a></li>
                    </ul>
                    <div class="header__cart__price">Total: <span>$<?= number_format($total_price, 2); ?></span>
                    </div>
                </div>
            </div>
        </div>

        <div class="humberger__open">
            <i class="fa fa-bars"></i>
        </div>
    </div>
</header>

<style>
    .hero__categories__all:after {
        content: '';
        display: block;
    }

    ul li a.active {
        color: #7fad39 !important;
        font-weight: bold !important;
    }
    
    /* Dropdown Styles */
    .header__top__right__auth__dropdown {
        position: relative;
        display: inline-block;
    }
    
    .header__top__right__auth__dropdown__content {
        display: none;
        position: absolute;
        background-color: #fff;
        min-width: 160px;
        box-shadow: 0 8px 16px rgba(0,0,0,0.2);
        z-index: 1000;
        border-radius: 5px;
        right: 0;
        top: 100%;
        margin-top: 10px;
    }
    
    .header__top__right__auth__dropdown:hover .header__top__right__auth__dropdown__content {
        display: block;
    }
    
    .header__top__right__auth__dropdown__content a {
        color: #333;
        padding: 12px 16px;
        text-decoration: none;
        display: block;
        transition: all 0.3s;
    }
    
    .header__top__right__auth__dropdown__content a:hover {
        background-color: #f1f1f1;
        color: #7fad39;
    }
    
    .header__top__right__auth__dropdown__content:before {
        content: '';
        position: absolute;
        top: -10px;
        right: 15px;
        border-width: 0 10px 10px 10px;
        border-style: solid;
        border-color: transparent transparent #fff transparent;
    }
</style>

<section class="hero hero-normal" style="margin-bottom: -50px;">
    <div class="container">
        <div class="row">
            <div class="col-lg-3">
                <div class="hero__categories">
                    <div class="hero__categories__all">
                        <i class="fa fa-bars"></i>
                        <span>Category</span>
                    </div>
                    <ul>
                        <?php
                        $categories = $query->select('categories', '*');
                        foreach ($categories as $category): ?>
                            <li>
                                <a
                                    href="category.php?category=<?= $category['id']; ?>"><?= $category['category_name'] ?></a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
            <div class="col-lg-9">
                <div class="hero__search">
                    <div class="hero__search__form">
                        <form action="search.php" method="GET">
                            <div class="hero__search__categories">
                                All Categories
                            </div>
                            <input type="text" name="query" placeholder="Tìm kiếm sản phẩm..." required>
                            <button type="submit" class="site-btn">SEARCH</button>
                        </form>
                    </div>
                    <div class="hero__search__phone">
                        <div class="hero__search__phone__icon">
                            <i class="fa fa-phone"></i>
                        </div>
                        <div class="hero__search__phone__text">
                            <h5>+84 0978476946</h5>
                            <span>support 24/7</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    function logout() {
        Swal.fire({
            title: 'Are you sure you want to log out?',
            text: "You cannot undo this action!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, log out!',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = './logout/';
            }
        });
    }
</script>