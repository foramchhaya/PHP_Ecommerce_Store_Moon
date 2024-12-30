<?php
session_start();

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include_once 'config/database.php';
include_once 'objects/orders.php';
include_once 'objects/order_item.php';
include_once 'objects/product.php';
include_once 'objects/user.php';

$database = new Database();
$db = $database->getConnection();

// Verify user is logged in
$user = User::getInstance($db);
$user->verifyUser(['user', 'admin']);

$order = new Order($db);
$orderItem = new OrderItem($db);
$product = new Product($db);

$order_id = isset($_GET['order_id']) ? $_GET['order_id'] : 0;

// Fetch the order
$order->id = $order_id;
$order->getOrderById();

if (!$order->id) {
    echo "Order not found.";
    exit();
}

// Fetch order items


$orderItem->order_id = $order->id;
$items_stmt = $orderItem->getAllByOrderId();



$total = 0;
?>

<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <title>Order Details</title>
    <link rel="stylesheet" href="src/css/cart.css">
    <link rel="stylesheet" href="src/css/styles.css">
</head>

<body>
    <?php include 'navbar.php'; ?>

    <section class="cart-section">
        <div class="cart-card" style="width: 90%;">

            <div class="cart-item-div view-order" style="width: 100%;">
                <h2 class="order-detail">ORDER DETAILS</h2>
                <hr />
                <section class="cart-item-sec">
                    <div class="order-details-header">
                        <div class="order-summary">
                            <h3>ORDER NUMBER: <?php echo htmlspecialchars($order->order_code, ENT_QUOTES, 'UTF-8'); ?></h3>
                            <p>Order Date: <?php echo date('F j, Y', strtotime($order->created_on)); ?></p>
                            <p>Total: $<?php echo number_format($order->total_amount, 2); ?></p>
                        </div>
                        <div class="address-details">
                            <h3>SHIPPING ADDRESS</h3>
                            <p><strong><?php echo htmlspecialchars($order->address_first_name, ENT_QUOTES, 'UTF-8'); ?> <?php echo htmlspecialchars($order->address_last_name, ENT_QUOTES, 'UTF-8'); ?></strong></p>
                            <p><?php echo htmlspecialchars($order->address_street, ENT_QUOTES, 'UTF-8'); ?></p>
                            <p><?php echo htmlspecialchars($order->address_city, ENT_QUOTES, 'UTF-8'); ?>, <?php echo htmlspecialchars($order->address_state, ENT_QUOTES, 'UTF-8'); ?> <?php echo htmlspecialchars($order->address_postal_code, ENT_QUOTES, 'UTF-8'); ?></p>
                            <p><?php echo htmlspecialchars($order->address_country, ENT_QUOTES, 'UTF-8'); ?></p>
                            <p>Phone: <?php echo htmlspecialchars($order->address_mobile, ENT_QUOTES, 'UTF-8'); ?></p>
                            <p>Email: <?php echo htmlspecialchars($order->address_email, ENT_QUOTES, 'UTF-8'); ?></p>
                        </div>
                    </div>

                    <?php if ($items_stmt->rowCount() > 0): ?>
                        <?php while ($item = $items_stmt->fetch(PDO::FETCH_ASSOC)): ?>
                            <?php
                       

                            $product->id = $item['product_id'];
                            $product->getProductById();
                            $item_total = $product->unit_price * $item['quantity'];
                            $total += $item_total;
                            ?>
                            <div class="cart-item view-order" style="justify-content: space-between;">
                                <div class="cart-item-img">
                                    <img src="src/images/<?php echo htmlspecialchars($product->image_file, ENT_QUOTES, 'UTF-8'); ?>" alt="<?php echo htmlspecialchars($product->product_name, ENT_QUOTES, 'UTF-8'); ?>">
                                    <div class="cart-title">
                                        <h3><?php echo htmlspecialchars($product->product_name, ENT_QUOTES, 'UTF-8'); ?></h3>
                                    </div>
                                </div>
                                <div class="cart-item-info">
                                    <div class="order-title-sec">
                                        <div class="cart-price">
                                            <ul>
                                                <li>Quantity: <?php echo htmlspecialchars($item['quantity'], ENT_QUOTES, 'UTF-8'); ?></li>
                                                <li class="price">Price: $<?php echo number_format($product->unit_price, 2); ?></li>
                                                <li class="price">Total: $<?php echo number_format($item_total, 2); ?></li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endwhile; ?>
                        <div class="product-total">
                            <h3>Order Total</h3>
                            <h3>$<?php echo number_format($total, 2); ?></h3>
                        </div>
                    <?php else: ?>
                        <p>No items found for this order.</p>
                    <?php endif; ?>

                </section>
            </div>
        </div>
    </section>
    <?php include 'footer.php'; ?>
</body>

</html>