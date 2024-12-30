<?php
session_start();

include_once 'config/database.php';
include_once 'objects/product.php';
include_once 'objects/cart.php';
include_once 'objects/user.php';

// Enable error reporting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$database = new Database();
$db = $database->getConnection();

$user = User::getInstance($db);
$user->verifyUser(['user', 'admin']);



if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Create Cart object

    $cart = new Cart($db);
    $product_id = isset($_POST['product_id']) ? (int)$_POST['product_id'] : 0;
    $quantity = isset($_POST['quantity']) ? (int)$_POST['quantity'] : 1;

    if ($product_id > 0) {
        $cart->addToCart($product_id, $quantity);
        header('Location: cart.php');
        exit();
    } else {
        $error_message = urlencode("Invalid Product ID");
        header("Location: 404_page.php?message=$error_message");
        exit();
    }
} else {
    $product = new Product($db);
    $product_id = isset($_GET['id']) ? $_GET['id'] : 0;

    if (!$product_id) {
        $error_message = urlencode("Please provide a Product ID");
        header("Location: 404_page.php?message=$error_message");
        exit();
    }

    $product->id = $product_id;
    $product->getProductById();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($product->product_name, ENT_QUOTES, 'UTF-8'); ?> - Product Details</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="src/css/product_detail.css">
    <link rel="stylesheet" href="src/css/styles.css">
</head>

<body>
    <?php include 'navbar.php'; ?>
    <div class="container">
        <div class="product-detail">
            <div class="product-image">
                <img src="src/images/<?php echo htmlspecialchars($product->image_file, ENT_QUOTES, 'UTF-8'); ?>" alt="<?php echo htmlspecialchars($product->product_name, ENT_QUOTES, 'UTF-8'); ?>">
            </div>
            <div class="product-info">
                <h2 class="product-title"><?php echo htmlspecialchars($product->product_name, ENT_QUOTES, 'UTF-8'); ?></h2>
                <hr />
                <p class="product-short-description">
                    <?php echo $product->brief_description; ?>
                </p>
                <div class="product-detail-cat">
                    <div class="product-price">
                        <span class="value">$<?php echo htmlspecialchars($product->unit_price, ENT_QUOTES, 'UTF-8'); ?></span>
                    </div>
                    <div class="product-category">
                        <span class="label">Category:</span>
                        <span class="value"><?php echo htmlspecialchars($product->category_name, ENT_QUOTES, 'UTF-8'); ?></span>
                    </div>
                </div>
                <div class="quantity-control">
                    <button class="quantity-button" onclick="adjustQuantity(-1)">-</button>
                    <input type="text" class="quantity-input" id="quantity-input" value="1" readonly>
                    <button class="quantity-button" onclick="adjustQuantity(1)">+</button>
                </div>
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                    <input type="hidden" name="product_id" value="<?php echo htmlspecialchars($product_id, ENT_QUOTES, 'UTF-8'); ?>">
                    <input type="hidden" name="quantity" id="quantity" value="1">
                    <button type="submit" class="btn-theme"><a>Add to Cart</a></button>
                </form>
            </div>
        </div>
    </div>
    <div class="product_detail_desc">
        <p class="product-description">
        <h3>Product Information:</h3>
        <?php echo $product->full_description; ?>
        </p>
    </div>
    <?php include 'footer.php'; ?>
    <script>
        function adjustQuantity(change) {
            const quantityInput = document.getElementById('quantity-input');
            const quantityField = document.getElementById('quantity');
            const currentQuantity = parseInt(quantityInput.value, 10);
            let newQuantity = currentQuantity + change;
            if (newQuantity < 1) newQuantity = 1;
            quantityInput.value = newQuantity;
            quantityField.value = newQuantity;
        }
    </script>
</body>

</html>