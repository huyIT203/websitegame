<?php
include 'check.php';

if (isset($_POST['product_id'])) {
    $product_id = $query->validate($_POST['product_id']);
    $seller_id = $_SESSION['id'];

    // Verify that the product belongs to the seller
    $product = $query->select('products', 'id', "WHERE id = $product_id AND seller_id = $seller_id");
    
    if (!empty($product)) {
        // Delete product images first
        $images = $query->select('product_images', 'image', "WHERE product_id = $product_id");
        foreach ($images as $image) {
            if (file_exists('../' . $image['image'])) {
                unlink('../' . $image['image']);
            }
        }
        
        // Delete image records from database
        $query->delete('product_images', "WHERE product_id = $product_id");
        
        // Delete the product
        $query->delete('products', "WHERE id = $product_id AND seller_id = $seller_id");
        
        echo 'success';
    } else {
        echo 'error';
    }
}
