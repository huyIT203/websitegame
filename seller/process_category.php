<?php
include 'check.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];

    $data = array(
        'name' => $query->validate($name)
    );

    // Insert the category data
    $category_id = $query->lastInsertId('categories', $data);

    if ($category_id) {
        // Redirect back to categories page with success message
        header("Location: categories.php?message=Category added successfully");
    } else {
        // Redirect back with error message
        header("Location: add_category.php?error=Failed to add category");
    }
} else {
    // If not POST request, redirect to add category page
    header("Location: add_category.php");
}
