<?php
include 'check.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <link rel="icon" href="../favicon.ico">
    <title>Seller | Categories</title>
    <?php include 'includes/css.php'; ?>
</head>
<body class="hold-transition sidebar-mini">
    <div class="wrapper">
        <?php include 'includes/navbar.php'; ?>
        <?php
        include 'includes/aside.php';
        active('category', 'categories');
        ?>
        <div class="content-wrapper">
            <?php
            $arr = array(
                ["title" => "Home", "url" => "/"],
                ["title" => "Categories", "url" => "#"],
            );
            pagePath('Categories', $arr);
            ?>
            <section class="content">
                <div class="container-fluid">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Categories List</h3>
                        </div>
                        <div class="card-body">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Category Name</th>
                                        <th>Total Products</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $categories = $query->getCategories();
                                    foreach ($categories as $id => $name) {
                                        // Get count of products for this category
                                        $product_count = $query->select('products', 'COUNT(*) as count', "WHERE category_id = $id")[0]['count'];
                                        
                                        echo "<tr>";
                                        echo "<td>$id</td>";
                                        echo "<td>$name</td>";
                                        echo "<td>$product_count</td>";
                                        echo "</tr>";
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </section>
        </div>
        <?php include 'includes/footer.php'; ?>
    </div>
    <script src="../src/js/jquery.min.js"></script>
    <script src="../src/js/adminlte.js"></script>
</body>
</html>
