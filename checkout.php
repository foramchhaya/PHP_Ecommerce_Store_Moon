<?php
session_start();

error_reporting(E_ALL);
ini_set('display_errors', 1);

include_once 'config/database.php';
include_once 'objects/product.php';
include_once 'objects/orders.php';
include_once 'objects/order_item.php';
include_once 'objects/user.php';

$database = new Database();
$db = $database->getConnection();

// Verify user is logged in
$user = User::getInstance($db);
$res = $user->verifyUser(['user']);

$product = new Product($db);
$order = new Order($db);
$orderItem = new OrderItem($db);

$cart = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];
$total = 0;

// POST request
$errors = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate address fields
    $requiredFields = [
        'address_first_name' => 'First Name',
        'address_last_name' => 'Last Name',
        'address_street' => 'Street',
        'address_city' => 'City',
        'address_state' => 'State',
        'address_postal_code' => 'Postal Code',
        'address_country' => 'Country',
        'address_mobile' => 'Mobile Number',
        'address_email' => 'Email'
    ];

    foreach ($requiredFields as $field => $label) {
        if (empty($_POST[$field])) {
            $errors[$field] = "$label is required";
        }
    }

    if (empty($errors)) {
        // Calculate the total
        foreach ($cart as $id => $quantity) {
            $product->id = $id;
            $product->getProductById();
            $item_total = $product->unit_price * $quantity;
            $total += $item_total;
        }

        // Create order
        $order->user_id = $_SESSION['user_id'];
        $order->total_amount = $total;
        $order->address_first_name = $_POST['address_first_name'];
        $order->address_last_name = $_POST['address_last_name'];
        $order->address_street = $_POST['address_street'];
        $order->address_city = $_POST['address_city'];
        $order->address_state = $_POST['address_state'];
        $order->address_postal_code = $_POST['address_postal_code'];
        $order->address_country = $_POST['address_country'];
        $order->address_mobile = $_POST['address_mobile'];
        $order->address_email = $_POST['address_email'];

        if ($order->createOrder()) {
            // Retrieve the last inserted order id
            $order_id = $db->lastInsertId();

            // Save order details in session
            $_SESSION['total_amount'] = $total;
            $_SESSION['order_code'] = $order->order_code;
            $_SESSION['order_id'] = $order_id;

            // Create order items
            foreach ($cart as $id => $quantity) {
                $product->id = $id;
                $product->getProductById();
                $orderItem->order_id = $order_id;
                $orderItem->product_id = $id;
                $orderItem->quantity = $quantity;
                $orderItem->unit_price = $product->unit_price;
                $orderItem->createItem();
            }

            // Clear the cart
            unset($_SESSION['cart']);
            header("Location: thank_you.php?order_id=$order_id");
            exit();
        } else {
            $errors['order'] = "Failed to place the order. Please try again.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <title>Checkout</title>
    <link rel="stylesheet" href="src/css/cart.css">
    <link rel="stylesheet" href="src/css/styles.css">
</head>

<body>
    <?php include 'navbar.php'; ?>

    <section class="cart-section">
        <div class="cart-card">
            <?php if (empty($cart)) : ?>
                <div class="cart-empty">
                    <h3>Your cart is empty</h3>
                    <a href="products.php">Continue Shopping</a>
                </div>
            <?php else : ?>
                <div class="cart-item-div">
                    <section class="cart-item-sec" id="cart-item-sec">
                        <?php foreach ($cart as $id => $quantity) : ?>
                            <?php
                            $product->id = $id;
                            $product->getProductById();
                            $item_total = $product->unit_price * $quantity;
                            $total += $item_total;
                            ?>
                            <div class="cart-item">
                                <div class="cart-item-img">
                                    <img src="src/images/<?php echo htmlspecialchars($product->image_file, ENT_QUOTES, 'UTF-8'); ?>" alt="<?php echo htmlspecialchars($product->product_name, ENT_QUOTES, 'UTF-8'); ?>">
                                </div>
                                <div class="cart-item-info" id="checkout-cart">
                                    <div class="cart-title-sec" id="cart-item-section">
                                        <div class="cart-title">
                                            <h3><?php echo htmlspecialchars($product->product_name, ENT_QUOTES, 'UTF-8'); ?></h3>
                                        </div>
                                        <div class="cart-price">
                                            <ul>
                                                <li class="price">$<?php echo htmlspecialchars($product->unit_price, ENT_QUOTES, 'UTF-8'); ?></li>
                                            </ul>
                                        </div>
                                        <div class="product-info">
                                            <p>Qty: <?php echo htmlspecialchars($quantity, ENT_QUOTES, 'UTF-8'); ?></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                        <div class="product-total">
                            <h3>Product Total</h3>
                            <h3>$<?php echo number_format($total, 2); ?></h3>
                        </div>
                    </section>
                    <section class="profile-section checkout-section">
                        <div class="form-section">
                            <h3 class="checkout-title">Checkout</h3>
                            <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                                <div class="form-group">
                                    <label for="address_first_name">First Name:</label>
                                    <input type="text" name="address_first_name" id="address_first_name" value="<?php echo htmlspecialchars($_POST['address_first_name'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                                    <span class="error"><?php echo $errors['address_first_name'] ?? ''; ?></span>
                                </div>
                                <div class="form-group">
                                    <label for="address_last_name">Last Name:</label>
                                    <input type="text" name="address_last_name" id="address_last_name" value="<?php echo htmlspecialchars($_POST['address_last_name'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                                    <span class="error"><?php echo $errors['address_last_name'] ?? ''; ?></span>
                                </div>
                                <div class="form-group">
                                    <label for="address_street">Street:</label>
                                    <input type="text" name="address_street" id="address_street" value="<?php echo htmlspecialchars($_POST['address_street'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                                    <span class="error"><?php echo $errors['address_street'] ?? ''; ?></span>
                                </div>
                                <div class="form-group">
                                    <label for="address_city">City:</label>
                                    <input type="text" name="address_city" id="address_city" value="<?php echo htmlspecialchars($_POST['address_city'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                                    <span class="error"><?php echo $errors['address_city'] ?? ''; ?></span>
                                </div>
                                <div class="form-group">
                                    <label for="address_state">State:</label>
                                    <input type="text" name="address_state" id="address_state" value="<?php echo htmlspecialchars($_POST['address_state'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                                    <span class="error"><?php echo $errors['address_state'] ?? ''; ?></span>
                                </div>
                                <div class="form-group">
                                    <label for="address_postal_code">Postal Code:</label>
                                    <input type="text" name="address_postal_code" id="address_postal_code" value="<?php echo htmlspecialchars($_POST['address_postal_code'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                                    <span class="error"><?php echo $errors['address_postal_code'] ?? ''; ?></span>
                                </div>
                                <div class="form-group">
                                    <label for="address_country">Country:</label>
                                    <input type="text" name="address_country" id="address_country" value="<?php echo htmlspecialchars($_POST['address_country'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                                    <span class="error"><?php echo $errors['address_country'] ?? ''; ?></span>
                                </div>
                                <div class="form-group">
                                    <label for="address_mobile">Mobile Number:</label>
                                    <input type="text" name="address_mobile" id="address_mobile" value="<?php echo htmlspecialchars($_POST['address_mobile'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                                    <span class="error"><?php echo $errors['address_mobile'] ?? ''; ?></span>
                                </div>
                                <div class="form-group">
                                    <label for="address_email">Email:</label>
                                    <input type="email" name="address_email" id="address_email" value="<?php echo htmlspecialchars($_POST['address_email'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                                    <span class="error"><?php echo $errors['address_email'] ?? ''; ?></span>
                                </div>
                                <button type="submit" class="btn-theme"><a>Place Order</a></button>
                            </form>
                        </div>
                    </section>
                </div>
            <?php endif; ?>

            <?php if (!empty($cart)) : ?>
                <div class="cart-total-sec">
                    <div class="cart-total-div">
                        <div class="order-summary">
                            <h3>Order Summary</h3>
                            <div class="subtotal">
                                <p>Product Subtotal</p>
                                <p>$<?php echo number_format($total, 2); ?></p>
                            </div>
                            <div class="product-discount">
                                <p>Order Discount</p>
                                <p class="discount">-$0.00</p> <!-- Update discount as needed -->
                            </div>
                            <div class="shipping">
                                <p>Estimated Shipping</p>
                                <p>$10.00</p> <!-- Update shipping cost as needed -->
                            </div>
                            <div class="taxes">
                                <p>Estimated Taxes</p>
                                <p>$0.00</p> <!-- Update taxes as needed -->
                            </div>
                            <hr class="hr-line">
                            <div class="final-total">
                                <h3>Estimated Total</h3>
                                <h3>$<?php echo number_format($total + 10, 2); ?></h3> <!-- Update total calculation as needed -->
                            </div>
                            <hr class="hr-line">
                            <button class="btn-theme">
                                <a href="cart.php">Edit Cart</a>
                            </button>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

        </div>
    </section>

    <?php include 'footer.php'; ?>
</body>

</html>