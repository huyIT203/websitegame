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
    <title>Seller | Add Category</title>
    <?php include 'includes/css.php'; ?>
</head>
<body class="hold-transition sidebar-mini">
    <div class="wrapper">
        <?php include 'includes/navbar.php'; ?>
        <?php
        include 'includes/aside.php';
        active('category', 'addcategory');
        ?>
        <div class="content-wrapper">
            <?php
            $arr = array(
                ["title" => "Home", "url" => "/"],
                ["title" => "Categories", "url" => "categories.php"],
                ["title" => "Add Category", "url" => "#"],
            );
            pagePath('Add Category', $arr);
            ?>
            <section class="content">
                <div class="container-fluid">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Add New Category</h3>
                        </div>
                        <div class="card-body">
                            <form action="process_category.php" method="POST">
                                <div class="form-group">
                                    <label for="name">Category Name</label>
                                    <input type="text" class="form-control" id="name" name="name" required>
                                </div>
                                <button type="submit" class="btn btn-primary">Add Category</button>
                            </form>
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
