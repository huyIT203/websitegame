<?php
include 'check.php';

// Get search query from URL
$search_query = isset($_GET['query']) ? $query->validate($_GET['query']) : '';
$seller_id = $_SESSION['id'];

// Initialize results
$products = [];
$total_products = 0;

// Perform search if query exists
if (!empty($search_query)) {
    // Search products by name, description, category
    $search_sql = "SELECT p.*, c.category_name 
                   FROM products p 
                   LEFT JOIN categories c ON p.category_id = c.id 
                   WHERE p.seller_id = $seller_id AND 
                         (p.name LIKE '%$search_query%' OR 
                          p.description LIKE '%$search_query%' OR 
                          c.category_name LIKE '%$search_query%')";
                          
    // Sort products by date added (newest first)
    $search_sql .= " ORDER BY p.id DESC";
    
    $products = $query->executeQuery($search_sql)->fetch_all(MYSQLI_ASSOC);
    $total_products = count($products);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tìm kiếm sản phẩm</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css">
    <style>
        body {
            background-color: #f8f9fa;
            padding-top: 20px;
            padding-bottom: 50px;
        }
        .navbar {
            margin-bottom: 30px;
        }
        .search-container {
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.05);
            padding: 30px;
            margin-bottom: 30px;
        }
        .card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.05);
            margin-bottom: 30px;
            transition: transform 0.3s;
        }
        .card:hover {
            transform: translateY(-5px);
        }
        .card-img-top {
            height: 200px;
            object-fit: cover;
            border-top-left-radius: 10px;
            border-top-right-radius: 10px;
        }
        .card-title {
            font-weight: 600;
            margin-bottom: 10px;
        }
        .card-text {
            color: #6c757d;
        }
        .price-tag {
            font-weight: 600;
            color: #28a745;
        }
        .old-price {
            text-decoration: line-through;
            color: #dc3545;
            font-size: 0.9em;
        }
        .badge {
            position: absolute;
            top: 10px;
            right: 10px;
            padding: 8px 12px;
            font-size: 0.8em;
        }
        .search-form {
            background-color: #f1f1f1;
            padding: 20px;
            border-radius: 10px;
        }
        .search-results-count {
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 1px solid #dee2e6;
        }
        .btn-action {
            margin-right: 5px;
        }
    </style>
</head>
<body>
    <?php include 'includes/navbar.php'; ?>
    
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="search-container">
                    <h2 class="mb-4">Tìm kiếm sản phẩm</h2>
                    
                    <div class="search-form mb-4">
                        <form action="" method="GET">
                            <div class="row">
                                <div class="col-md-9">
                                    <div class="form-group mb-0">
                                        <input type="text" name="query" class="form-control form-control-lg" placeholder="Nhập tên sản phẩm, mô tả hoặc danh mục..." value="<?= htmlspecialchars($search_query) ?>" required>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <button type="submit" class="btn btn-primary btn-lg btn-block">
                                        <i class="fas fa-search"></i> Tìm kiếm
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                    
                    <?php if (!empty($search_query)): ?>
                        <div class="search-results-count">
                            <h4><?= $total_products ?> kết quả được tìm thấy cho "<?= htmlspecialchars($search_query) ?>"</h4>
                        </div>
                        
                        <?php if ($total_products > 0): ?>
                            <div class="row">
                                <?php foreach ($products as $product): 
                                    // Get product images
                                    $images = $query->select('product_images', 'image_url', "WHERE product_id = " . $product['id']);
                                    $image_url = !empty($images) ? '../src/images/products/' . $images[0]['image_url'] : '../src/images/no-image.jpg';
                                ?>
                                    <div class="col-md-4">
                                        <div class="card">
                                            <img src="<?= $image_url ?>" class="card-img-top" alt="<?= htmlspecialchars($product['name']) ?>">
                                            <?php if ($product['quantity'] <= 0): ?>
                                                <span class="badge badge-danger">Hết hàng</span>
                                            <?php endif; ?>
                                            
                                            <div class="card-body">
                                                <h5 class="card-title"><?= htmlspecialchars($product['name']) ?></h5>
                                                <p class="card-text">
                                                    <span class="price-tag">$<?= number_format($product['price_current'], 2) ?></span>
                                                    <span class="old-price ml-2">$<?= number_format($product['price_old'], 2) ?></span>
                                                </p>
                                                <p class="card-text">
                                                    <small>Danh mục: <?= htmlspecialchars($product['category_name']) ?></small><br>
                                                    <small>Còn lại: <?= $product['quantity'] ?> sản phẩm</small>
                                                </p>
                                                <div class="btn-group">
                                                    <a href="edit_product.php?id=<?= $product['id'] ?>" class="btn btn-sm btn-primary btn-action">
                                                        <i class="fas fa-edit"></i> Sửa
                                                    </a>
                                                    <a href="shop-details.php?id=<?= $product['id'] ?>" class="btn btn-sm btn-info btn-action">
                                                        <i class="fas fa-eye"></i> Xem
                                                    </a>
                                                    <button type="button" class="btn btn-sm btn-danger btn-action" onclick="confirmDelete(<?= $product['id'] ?>)">
                                                        <i class="fas fa-trash"></i> Xóa
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php else: ?>
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle"></i> Không tìm thấy sản phẩm nào phù hợp với từ khóa tìm kiếm.
                            </div>
                            <p>Gợi ý:</p>
                            <ul>
                                <li>Kiểm tra lại chính tả</li>
                                <li>Sử dụng từ khóa chung hơn</li>
                                <li>Thử tìm kiếm bằng tên danh mục</li>
                            </ul>
                            <a href="addproduct.php" class="btn btn-success">
                                <i class="fas fa-plus"></i> Thêm sản phẩm mới
                            </a>
                        <?php endif; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    
    <!-- jQuery and Bootstrap Bundle -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    
    <script>
        function confirmDelete(productId) {
            Swal.fire({
                title: 'Bạn có chắc chắn?',
                text: "Bạn sẽ không thể hoàn tác hành động này!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Xóa',
                cancelButtonText: 'Hủy'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Send delete request
                    fetch('delete_product.php', {
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
                            Swal.fire(
                                'Đã xóa!',
                                'Sản phẩm đã được xóa thành công.',
                                'success'
                            ).then(() => {
                                window.location.reload();
                            });
                        } else {
                            Swal.fire(
                                'Lỗi!',
                                data.message || 'Không thể xóa sản phẩm.',
                                'error'
                            );
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        Swal.fire(
                            'Lỗi!',
                            'Đã xảy ra lỗi khi xử lý yêu cầu.',
                            'error'
                        );
                    });
                }
            });
        }
    </script>
</body>
</html> 