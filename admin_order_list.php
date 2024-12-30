<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

include_once 'config/database.php';
include_once 'objects/orders.php';
include_once 'objects/user.php';

$database = new Database();
$db = $database->getConnection();

// Check if the user is logged in
$user = User::getInstance($db);
$user->verifyUser(['admin']);

// Create Order object
$order = new Order($db);

// Fetch all orders
$orders = $order->getAllOrders();

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <title>Moon Jewelry Store - Manage Orders</title>
    <link rel="stylesheet" href="src/css/order_history.css">
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
                            <li><a href="admin_order_list.php" class="active"><i class="fa fa-list-alt"></i>Order List</a></li>
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
                                <h1 class="main-heading"><i class="fa fa-tasks"></i>Manage Orders</h1>
                            </div>
                        </div>
                        <div class="container-fluid mt-50 mb-50">
                            <div class="row">
                                <div class="col-md-12">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>ORDER CODE</th>
                                                <th>CUSTOMER NAME</th>
                                                <th>TOTAL AMOUNT</th>
                                                <th>ORDER DATE</th>
                                                <th>ACTION</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php if (count($orders) > 0): ?>
                                                <?php foreach ($orders as $order): ?>
                                                    <tr>
                                                        <td><?php echo htmlspecialchars($order['order_code'], ENT_QUOTES, 'UTF-8'); ?></td>
                                                        <td><?php echo htmlspecialchars($order['address_first_name'] . ' ' . $order['address_last_name'], ENT_QUOTES, 'UTF-8'); ?></td>
                                                        <td><?php echo htmlspecialchars($order['total_amount'], ENT_QUOTES, 'UTF-8'); ?></td>
                                                        <td><?php echo htmlspecialchars($order['created_on'], ENT_QUOTES, 'UTF-8'); ?></td>
                                                        <td>
                                                            <div class="address_buttons">
                                                                <a class="btn btn-success" href="admin_view_order.php?id=<?php echo htmlspecialchars($order['id'], ENT_QUOTES, 'UTF-8'); ?>">View</a>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            <?php else: ?>
                                                <tr>
                                                    <td colspan="5">No orders found.</td>
                                                </tr>
                                            <?php endif; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>

    </main>

</body>

</html>
