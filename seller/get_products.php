<?php
include 'check.php';

if (isset($_POST['category_id'])) {
    $category_id = $query->validate($_POST['category_id']);
    $seller_id = $_SESSION['id'];

    // Get category name
    $category = $query->select('categories', 'name', "WHERE id = $category_id")[0];
    
    // Get products with status
    $products = $query->select('products', '*', "WHERE category_id = $category_id AND seller_id = $seller_id");
    
    $products_html = '';
    foreach ($products as $product) {
        $image = $query->select('product_images', 'image', "WHERE product_id = " . $product['id'], "LIMIT 1");
        $image_path = isset($image[0]['image']) ? $image[0]['image'] : '../src/images/default-product.jpg';
        
        $stock_status = $product['quantity'] > 0 ? 
            '<span class="badge badge-success">In Stock</span>' : 
            '<span class="badge badge-danger">Out of Stock</span>';
        
        $products_html .= '
        <div class="col-md-4">
            <div class="card product-card">
                <div class="position-relative">
                    <img src="' . $image_path . '" class="card-img-top" alt="' . $product['name'] . '">
                    <div class="category-badge">' . $category['name'] . '</div>
                </div>
                <div class="card-body">
                    <h5 class="card-title">' . $product['name'] . '</h5>
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <div>
                            <span class="text-muted"><del>$' . $product['price_old'] . '</del></span>
                            <span class="text-success ml-2">$' . $product['price_current'] . '</span>
                        </div>
                        ' . $stock_status . '
                    </div>
                    <p class="card-text"><small>Stock: ' . $product['quantity'] . ' units</small></p>
                    <div class="btn-group">
                        <a href="edit_product.php?id=' . $product['id'] . '" class="btn btn-primary btn-sm">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                        <button onclick="deleteProduct(' . $product['id'] . ')" class="btn btn-danger btn-sm">
                            <i class="fas fa-trash"></i> Delete
                        </button>
                    </div>
                </div>
            </div>
        </div>';
    }

    echo json_encode([
        'category_name' => $category['name'],
        'products_html' => $products_html
    ]);
}
