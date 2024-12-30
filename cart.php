<?php
session_start();

include_once 'config/database.php';
include_once 'objects/product.php';
include_once 'objects/user.php';
include_once 'objects/cart.php';

$database = new Database();
$db = $database->getConnection();

// Verify user is logged in
$user = User::getInstance($db);
$user->verifyUser(['user']);

// Initialize the Cart object
$cart = new Cart($db);

// Handle cart operations based on request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = isset($_POST['action']) ? $_POST['action'] : '';
    $product_id = isset($_POST['id']) ? $_POST['id'] : '';
    $quantity = isset($_POST['quantity']) ? (int)$_POST['quantity'] : 1;

    switch ($action) {
        case 'add':
            if ($product_id) {
                $cart->addToCart($product_id, $quantity);
            }
            break;
        case 'remove':
            if ($product_id) {
                $cart->removeFromCart($product_id);
            }
            break;
        case 'update':
            if ($product_id) {
                $cart->updateCart($product_id, $quantity);
            }
            break;
    }

    header("Location: cart.php");
    exit();
}

// Prepare for displaying cart items
$product = new Product($db);
$cart_items = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];
$total = 0;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <title>Shopping Cart</title>
    <link rel="stylesheet" href="src/css/cart.css">
    <link rel="stylesheet" href="src/css/thankyou.css">
    <link rel="stylesheet" href="src/css/styles.css">
</head>
<body>
    <?php include 'navbar.php'; ?>

    <?php if (empty($cart_items)): ?>
        <section class="thank-you-section" id="empty-cart">
            <div class="thank-you-card">
                <img src="src/images/empty-cart-vector.jpg" alt="Empty Cart">
                <h1>Your Cart is Empty</h1>
                <p>It looks like you don't have any items in your cart.</p>
                <button class="btn-theme">
                    <a href="shop.php">Browse Products</a>
                </button>
            </div>
        </section>
    <?php else: ?>
        <section class="cart-section">
            <div class="cart-card">
                <div class="cart-item-div">
                    <section class="cart-item-sec" id="cart-item-sec">
                        <?php foreach ($cart_items as $id => $quantity): ?>
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
                                <div class="cart-item-info">
                                    <div class="cart-title-sec" id="cart-item-section">
                                        <div class="cart-title">
                                            <h3><?php echo htmlspecialchars($product->product_name, ENT_QUOTES, 'UTF-8'); ?></h3>
                                        </div>
                                        <div class="remove-product-div">
                                            <button class="btn-theme" onclick="cartAction('<?php echo $id; ?>', 'remove')"><a>Remove</a></button>
                                        </div>
                                    </div>
                                    <div class="qty-price">
                                        <div class="quantity-sec">
                                            <div class="qty" onclick="updateQuantity('<?php echo $id; ?>', -1)">-</div>
                                            <div class="qty-number" id="qty-<?php echo $id; ?>"><?php echo $quantity; ?></div>
                                            <div class="qty" onclick="updateQuantity('<?php echo $id; ?>', 1)">+</div>
                                        </div>
                                        <div class="cart-price">
                                            <ul>
                                                <li></li>
                                                <li class="price">$<?php echo htmlspecialchars($product->unit_price, ENT_QUOTES, 'UTF-8'); ?></li>
                                            </ul>
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
                </div>

                <?php if (!empty($cart_items)): ?>
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
                                    <p class="discount">-$0.00</p> 
                                </div>
                                <div class="shipping">
                                    <p>Estimated Shipping</p>
                                    <p>$10.00</p> 
                                </div>
                                <div class="taxes">
                                    <p>Estimated Taxes</p>
                                    <p>$0.00</p> 
                                </div>
                                <hr class="hr-line">
                                <div class="final-total">
                                    <h3>Estimated Total</h3>
                                    <h3>$<?php echo number_format($total + 10, 2); ?></h3> 
                                </div>
                                <hr class="hr-line">
                                <button class="btn-theme">
                                    <a href="checkout.php">Continue to Checkout</a>
                                </button>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </section>
    <?php endif; ?>
    <?php include 'footer.php'; ?>
    <script>
        function updateQuantity(productId, change) {
            var qtyElement = document.getElementById('qty-' + productId);
            var currentQty = parseInt(qtyElement.textContent, 10);
            var newQty = currentQty + change;
            if (newQty < 1) newQty = 1;

            qtyElement.textContent = newQty;

            var xhr = new XMLHttpRequest();
            xhr.open('POST', 'cart.php', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.send('action=update&id=' + productId + '&quantity=' + newQty);

            xhr.onload = function() {
                if (xhr.status === 200) {
                    location.reload();
                }
            };
        }

        function cartAction(productId, action) {
            var xhr = new XMLHttpRequest();
            xhr.open('POST', 'cart.php', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.send('action=' + action + '&id=' + productId);

            xhr.onload = function() {
                if (xhr.status === 200) {
                    location.reload();
                }
            };
        }
    </script>
</body>
</html>
