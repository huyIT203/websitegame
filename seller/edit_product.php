<?php
include 'check.php';

if (!isset($_GET['product_id']) || !isset($_SESSION['id'])) {
    header("Location: ./");
    exit();
}

$productId = $query->validate($_GET['product_id']);
$userId = $query->validate($_SESSION['id']);

// Fetch product details
$product = $query->select('products', '*', "WHERE id = $productId AND seller_id = $userId");
if (empty($product)) {
    header("Location: ./");
    exit();
}
$product = $product[0];

// Fetch categories
$categories = $query->getCategories();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $query->validate($_POST['name']);
    $description = $query->validate($_POST['description']);
    $price_current = $query->validate($_POST['price_current']);
    $category_id = $query->validate($_POST['category_id']);

    $data = [
        'name' => $name,
        'description' => $description,
        'price_current' => $price_current,
        'category_id' => $category_id
    ];

    $query->update('products', $data, "WHERE id = $productId AND seller_id = $userId");

    header("Location: ./");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Product</title>
    <link rel="stylesheet" href="../src/css/bootstrap.min.css" type="text/css">
    <link rel="stylesheet" href="../src/css/style.css" type="text/css">
</head>

<body>
    <section class="product-details spad">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 col-md-6">
                    <div class="product__details__pic">
                        <div class="product__details__pic__item">
                            <?php
                            $images = $query->getProductImages($productId);
                            if (!empty($images)) {
                                echo '<img class="product__details__pic__item--large" src="../src/images/products/' . $images[0] . '" alt="">';
                            }
                            ?>
                        </div>
                        <div class="product__details__pic__slider owl-carousel">
                            <?php
                            foreach ($images as $image) {
                                echo '<img data-imgbigurl="../src/images/products/' . $image . '" src="../src/images/products/' . $image . '" alt="">';
                            }
                            ?>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 col-md-6">
                    <div class="product__details__text">
                        <h3>Edit Product</h3>
                        <form method="POST">
                            <div class="form-group">
                                <label for="name">Product Name:</label>
                                <input type="text" id="name" name="name" class="form-control" value="<?= htmlspecialchars($product['name']) ?>" required>
                            </div>

                            <div class="form-group">
                                <label for="description">Description:</label>
                                <textarea id="description" name="description" class="form-control" rows="5" required><?= htmlspecialchars($product['description']) ?></textarea>
                            </div>

                            <div class="form-group">
                                <label for="price_current">Price:</label>
                                <input type="number" id="price_current" name="price_current" class="form-control" value="<?= htmlspecialchars($product['price_current']) ?>" required>
                            </div>

                            <div class="form-group">
                                <label for="category_id">Category:</label>
                                <select id="category_id" name="category_id" class="form-control" required>
                                    <?php foreach ($categories as $id => $category_name): ?>
                                        <option value="<?= $id ?>" <?= $id == $product['category_id'] ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($category_name) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <button type="submit" class="btn btn-primary">Update Product</button>
                            <a href="./" class="btn btn-secondary">Cancel</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script src="../src/js/jquery-3.3.1.min.js"></script>
    <script src="../src/js/bootstrap.min.js"></script>
    <script src="../src/js/owl.carousel.min.js"></script>
    <script src="../src/js/main.js"></script>
</body>

</html>
