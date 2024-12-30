<?php
session_start();

include_once 'config/database.php';
include_once 'objects/product.php';
include_once 'objects/user.php';

// Initialize the database connection and required objects
$database = new Database();
$db = $database->getConnection();
$product = new Product($db);
$user = User::getInstance($db);
$user->verifyUser(['admin']);


// Get the product ID from the URL
$product_id = $_GET['id'] ?? null;

if (!$product_id) {
    exit("Invalid product ID.");
}

// Set the product ID and attempt to deactivate the product
$product->id = $product_id;

if ($product->activateProduct()) {
    header("Location: admin_product_list.php");
    exit();
} else {
    exit("Unable to delete the product. Please try again.");
}
