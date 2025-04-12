<?php include 'check.php'; 

// Get search query from URL
$search_query = isset($_GET['query']) ? $query->validate($_GET['query']) : '';
?>

<!DOCTYPE html>
<html lang="eng">

<head>
    <meta charset="UTF-8">
    <meta name="description" content="Ogani Template">
    <meta name="keywords" content="Ogani, unica, creative, html">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="icon" href="./favicon.ico">
    <title>Search Results: <?= htmlspecialchars($search_query) ?></title>
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
</head>

<body>
    <?php include './includes/header.php'; ?>

    <section class="product spad">
        <div class="container">
            <div class="row">
                <div class="col-lg-3 col-md-5">
                    <div class="sidebar">
                        <div class="sidebar__item">
                            <h4>Danh mục</h4>
                            <ul>
                                <?php
                                $categories = $query->select('categories', '*');
                                foreach ($categories as $category): ?>
                                    <li><a href="category.php?category=<?php echo $category['id'] ?>"><?php echo $category['category_name']; ?></a>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                        <div class="sidebar__item">
                            <h4>Giá</h4>
                            <?php
                            $result = $query->executeQuery("SELECT MIN(price_current) AS min_price, MAX(price_current) AS max_price FROM products");
                            $row = $result->fetch_assoc();
                            $min_price = $row['min_price'];
                            $max_price = $row['max_price'];
                            ?>

                            <div class="price-range-wrap">
                                <div class="price-range ui-slider ui-corner-all ui-slider-horizontal ui-widget ui-widget-content"
                                    data-min="<?php echo $min_price; ?>" data-max="<?php echo $max_price; ?>">
                                    <div class="ui-slider-range ui-corner-all ui-widget-header"></div>
                                    <span tabindex="0" class="ui-slider-handle ui-corner-all ui-state-default"></span>
                                    <span tabindex="0" class="ui-slider-handle ui-corner-all ui-state-default"></span>
                                </div>
                                <div class="range-slider">
                                    <div class="price-input">
                                        <input type="text" id="minamount" value="<?php echo $min_price; ?>">
                                        <input type="text" id="maxamount" value="<?php echo $max_price; ?>">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-9 col-md-7">
                    <div class="section-title product__discount__title">
                        <h2>Kết quả tìm kiếm cho: "<?= htmlspecialchars($search_query) ?>"</h2>
                    </div>

                    <div class="filter__item">
                        <div class="row">
                            <div class="col-lg-4 col-md-5">
                                <div class="filter__sort">
                                    <span>Sắp xếp theo</span>
                                    <select id="sort-select" onchange="applySort()">
                                        <option value="default">Mặc định</option>
                                        <option value="price_asc">Giá: Thấp đến cao</option>
                                        <option value="price_desc">Giá: Cao đến thấp</option>
                                        <option value="name_asc">Tên: A-Z</option>
                                        <option value="name_desc">Tên: Z-A</option>
                                    </select>
                                </div>
                            </div>
                            <?php
                            // Construct search query
                            $search_sql = "SELECT * FROM products WHERE name LIKE '%$search_query%' OR description LIKE '%$search_query%'";
                            
                            // Check for sort parameter
                            $sort = isset($_GET['sort']) ? $_GET['sort'] : 'default';
                            switch ($sort) {
                                case 'price_asc':
                                    $search_sql .= " ORDER BY price_current ASC";
                                    break;
                                case 'price_desc':
                                    $search_sql .= " ORDER BY price_current DESC";
                                    break;
                                case 'name_asc':
                                    $search_sql .= " ORDER BY name ASC";
                                    break;
                                case 'name_desc':
                                    $search_sql .= " ORDER BY name DESC";
                                    break;
                                default:
                                    // Default sorting
                                    $search_sql .= " ORDER BY id DESC";
                            }
                            
                            $products = $query->executeQuery($search_sql)->fetch_all(MYSQLI_ASSOC);
                            $product_count = count($products);
                            ?>
                            <div class="col-lg-4 col-md-4">
                                <div class="filter__found">
                                    <h6><span><?= $product_count ?></span> sản phẩm được tìm thấy</h6>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-3">
                                <div class="filter__option">
                                    <span class="icon_grid-2x2"></span>
                                    <span class="icon_ul"></span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <?php
                        if ($product_count > 0) {
                            foreach ($products as $product):
                                $product_name = $product['name'];
                                $category_name = $query->select('categories', 'category_name', 'WHERE id=' . $product['category_id'])[0]['category_name'];
                                $price_current = $product['price_current'];
                                $price_old = $product['price_old'];
                                $product_id = $product['id'];
                                
                                // Get product image
                                $image_result = $query->select('product_images', 'image_url', "where product_id = '$product_id'");
                                $image = !empty($image_result) ? $image_result[0]['image_url'] : 'default.jpg';
                        ?>
                            <div class="col-lg-4 col-md-6 col-sm-6">
                                <div class="product__discount__item">
                                    <div class="product__discount__item__pic set-bg"
                                        data-setbg="./src/images/products/<?php echo $image ?>">
                                        <ul class="product__item__pic__hover">
                                            <li><a onclick="addToWishlist(<?php echo $product_id; ?>)"><i
                                                        class="fa fa-heart"></i></a></li>
                                            <li><a onclick="openProductDetails(<?php echo $product_id; ?>)"><i
                                                        class="fa fa-retweet"></i></a></li>
                                            <li><a onclick="addToCart(<?php echo $product_id; ?>)"><i
                                                        class="fa fa-shopping-cart"></i></a></li>
                                        </ul>
                                    </div>
                                    <div class="product__discount__item__text">
                                        <span><?php echo $category_name; ?></span>
                                        <h5><a onclick="openProductDetails(<?php echo $product_id; ?>)"><?php echo $product_name; ?></a></h5>
                                        <div class="product__item__price">$<?php echo $price_current; ?>
                                            <span>$<?php echo $price_old; ?></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php 
                            endforeach;
                        } else {
                        ?>
                            <div class="col-12 text-center">
                                <div class="empty-result" style="padding: 50px 0;">
                                    <i class="fa fa-search" style="font-size: 48px; color: #ddd; margin-bottom: 20px;"></i>
                                    <h4>Không tìm thấy sản phẩm nào</h4>
                                    <p>Vui lòng thử lại với từ khóa khác</p>
                                    <a href="index.php" class="primary-btn">Quay lại trang chủ</a>
                                </div>
                            </div>
                        <?php
                        }
                        ?>
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
    <script>
        function addToWishlist(productId) {
            fetch('add_to_wishlist.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    product_id: productId
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        title: 'Success!',
                        text: 'Product added to wishlist',
                        icon: 'success',
                        confirmButtonText: 'OK'
                    }).then(() => {
                        location.reload();
                    });
                } else {
                    Swal.fire({
                        title: 'Error!',
                        text: data.message,
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                }
            });
        }

        function addToCart(productId) {
            fetch('add_to_cart.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    product_id: productId
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        title: 'Success!',
                        text: 'Product added to cart',
                        icon: 'success',
                        confirmButtonText: 'OK'
                    }).then(() => {
                        location.reload();
                    });
                } else {
                    Swal.fire({
                        title: 'Error!',
                        text: data.message,
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                }
            });
        }

        function openProductDetails(productId) {
            window.location.href = `product.php?id=${productId}`;
        }

        function applySort() {
            const sortSelect = document.getElementById('sort-select');
            const selectedSort = sortSelect.value;
            const currentUrl = new URL(window.location.href);
            currentUrl.searchParams.set('sort', selectedSort);
            window.location.href = currentUrl.toString();
        }

        // Set the sort select value based on URL parameter
        document.addEventListener('DOMContentLoaded', function() {
            const urlParams = new URLSearchParams(window.location.search);
            const sortParam = urlParams.get('sort');
            if (sortParam) {
                document.getElementById('sort-select').value = sortParam;
            }
        });
    </script>
</body>
</html> 