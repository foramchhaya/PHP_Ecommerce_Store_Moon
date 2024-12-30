<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
error_reporting(E_ALL);
ini_set('display_errors', 1);

include_once 'config/database.php';
include_once 'objects/product.php';
include_once 'objects/user.php';
include_once 'objects/orders.php';
include_once 'objects/category.php';

$database = new Database();
$db = $database->getConnection();

// Verify user is logged in
$user = User::getInstance($db);
$user->verifyUser(['admin']);

// Fetch counts
$productQuery = "SELECT COUNT(*) as total FROM products";
$userQuery = "SELECT COUNT(*) as total FROM users WHERE role = 'user'";
$categoryQuery = "SELECT COUNT(*) as total FROM categories";
$orderQuery = "SELECT COUNT(*) as total FROM orders";

$productStmt = $db->prepare($productQuery);
$userStmt = $db->prepare($userQuery);
$categoryStmt = $db->prepare($categoryQuery);
$orderStmt = $db->prepare($orderQuery);

$productStmt->execute();
$userStmt->execute();
$categoryStmt->execute();
$orderStmt->execute();

$totalProducts = $productStmt->fetchColumn();
$totalUsers = $userStmt->fetchColumn();
$totalCategories = $categoryStmt->fetchColumn();
$totalOrders = $orderStmt->fetchColumn();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Moon Jewelry Store| Dashboard</title>
    <link href="https://fonts.googleapis.com/css2?family=Cabin&family=Playfair+Display&display=swap" rel="stylesheet">
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
                            <li><a href="admin_index.php" class="active"><i class="fa fa-home"></i>Dashboard</a></li>
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
                                <h1 class="main-heading"><i class="fa fa-home"></i>Welcome to Dashboard</h1>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 dashboard-head">
                                <div class="col-md-6">
                                    <p class="dashboard-welcome">Welcome To</p>
                                    <h3>MOON</h3>
                                </div>
                                <div class="col-md-6">
                                    <img src="src/images/admin-header.jpg" alt="dashboard" class="img-fluid">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3">
                                <div class="card dashboard-card">
                                    <div class="card-body">
                                        <i class="fa fa-check-square-o" aria-hidden="true"></i>
                                        <div class="task-info">
                                            <h3><?php echo $totalProducts; ?></h3>
                                            <p class="task-list-name">Total Products</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card dashboard-card">
                                    <div class="card-body">
                                        <i class="fa fa-spinner" aria-hidden="true"></i>
                                        <div class="task-info">
                                            <h3><?php echo $totalUsers; ?></h3>
                                            <p class="task-list-name">Total Customers</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card dashboard-card">
                                    <div class="card-body">
                                        <i class="fa fa-clock-o" aria-hidden="true"></i>
                                        <div class="task-info">
                                            <h3><?php echo $totalCategories; ?></h3>
                                            <p class="task-list-name">Product Categories</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card dashboard-card">
                                    <div class="card-body">
                                        <i class="fa fa-check-square-o" aria-hidden="true"></i>
                                        <div class="task-info">
                                            <h3><?php echo $totalOrders; ?></h3>
                                            <p class="task-list-name">Total Orders</p>
                                        </div>
                                    </div>
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
