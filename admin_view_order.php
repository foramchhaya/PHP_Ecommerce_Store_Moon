<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
error_reporting(E_ALL);
ini_set('display_errors', 1);

include_once 'config/database.php';
include_once 'objects/orders.php';
include_once 'objects/order_item.php';
include_once 'objects/product.php';
include_once 'objects/user.php';

$database = new Database();
$db = $database->getConnection();

// Verify user is logged in
$user = User::getInstance($db);
$user->verifyUser(['admin']);

// Get the order ID from the URL
$order_id = isset($_GET['id']) ? $_GET['id'] : die('ERROR: Order ID not found.');

// Initialize the Order and OrderItem objects
$order = new Order($db);
$order->id = $order_id;
$order->getOrderById();

// Check if the order exists
if (!$order->id) {
    die('ERROR: Order not found.');
}

// Fetch order items
$orderItem = new OrderItem($db);
$orderItem->order_id = $order_id;
$stmt = $orderItem->getAllByOrderId();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Moon Jewelry Store | View Order</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="src/css/admin_style.css">
</head>
<body>
    <?php include 'admin_navbar.php'; ?>

    <main>
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-2 bg-dark sidebar">
                    <div class="sidebar">
                        <ul>
                            <li><a href="admin_index.php"><i class="fa fa-home"></i>Dashboard</a></li>
                            <li><a href="create_product.php"><i class="fa fa-pencil-square-o"></i>Add Product</a></li>
                            <li><a href="admin_product_list.php"><i class="fa fa-list-alt"></i>Product List</a></li>
                            <li><a href="admin_customer_list.php"><i class="fa fa-tasks"></i>Customer List</a></li>
                            <li><a href="admin_order_list.php"><i class="fa fa-shopping-cart"></i>Order List</a></li>
                            <li><a href="logout.php"><i class="fa fa-sign-out"></i>Logout</a></li>
                        </ul>
                    </div>
                    <div class="copyright">
                        <small>&copy; ALL RIGHTS RESERVED | MOON</small>
                    </div>
                </div>

                <div class="col-md-10 body-content">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-md-12">
                                <h1 class="main-heading"><i class="fa fa-shopping-cart"></i>View Order Details</h1>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <table class="table table-bordered">
                                    <tr>
                                        <th>Order ID:</th>
                                        <td><?php echo htmlspecialchars($order->id); ?></td>
                                    </tr>
                                    <tr>
                                        <th>Order Code:</th>
                                        <td><?php echo htmlspecialchars($order->order_code); ?></td>
                                    </tr>
                                    <tr>
                                        <th>Total Amount:</th>
                                        <td>$<?php echo htmlspecialchars($order->total_amount); ?></td>
                                    </tr>
                                    <tr>
                                        <th>Order Date:</th>
                                        <td><?php echo htmlspecialchars($order->created_on); ?></td>
                                    </tr>
                                    <tr>
                                        <th>Customer Name:</th>
                                        <td><?php echo htmlspecialchars($order->address_first_name . ' ' . $order->address_last_name); ?></td>
                                    </tr>
                                    <tr>
                                        <th>Shipping Address:</th>
                                        <td>
                                            <?php echo htmlspecialchars($order->address_street) . '<br>' .
                                                htmlspecialchars($order->address_city) . ', ' .
                                                htmlspecialchars($order->address_state) . ' ' .
                                                htmlspecialchars($order->address_postal_code) . '<br>' .
                                                htmlspecialchars($order->address_country); ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Contact Information:</th>
                                        <td>
                                            Phone: <?php echo htmlspecialchars($order->address_mobile); ?><br>
                                            Email: <?php echo htmlspecialchars($order->address_email); ?>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <h2>Order Items</h2>
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Image</th>
                                            <th>Product Name</th>
                                            <th>Quantity</th>
                                            <th>Unit Price</th>
                                            <th>Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                            $product = new Product($db);
                                            $product->id = $row['product_id'];
                                            $product->getProductById();

                                            echo "<tr>";
                                            echo "<td><img src='src/images/" . htmlspecialchars($product->image_file) . "' alt='" . htmlspecialchars($product->product_name) . "' class='img-thumbnail' width='50'></td>";
                                            echo "<td>" . htmlspecialchars($product->product_name) . "</td>";
                                            echo "<td>" . htmlspecialchars($row['quantity']) . "</td>";
                                            echo "<td>$" . htmlspecialchars($row['unit_price']) . "</td>";
                                            echo "<td>$" . htmlspecialchars($row['quantity'] * $row['unit_price']) . "</td>";
                                            echo "</tr>";
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 order-list">
                                <a href="admin_order_list.php" class="main-color-button">Back to Orders</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
</body>
</html>
