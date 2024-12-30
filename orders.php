<?php
session_start();

error_reporting(E_ALL);
ini_set('display_errors', 1);

// Include necessary files and initialize database connection
include_once 'config/database.php';
include_once 'objects/orders.php';
include_once 'objects/order_item.php';
include_once 'objects/user.php';

// Initialize database and objects
$database = new Database();
$db = $database->getConnection();
$order = new Order($db);
$orderItem = new OrderItem($db);
$user = User::getInstance($db);

// Ensure user is logged in
$user->verifyUser(['user']);
$user_id = $_SESSION['user_id'];

// Fetch orders for the user
$query = "SELECT * FROM Orders WHERE user_id = ? ORDER BY created_on DESC";
$stmt = $db->prepare($query);
$stmt->bindParam(1, $user_id);
$stmt->execute();
$orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order History</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="src/css/order_history.css">
    <link rel="stylesheet" href="src/css/styles.css">
</head>
<body>
    <?php include 'navbar.php'; ?>

    <section class="order-history-section">
        <h2>Order History</h2>
        <table class="order-history-table">
            <thead>
                <tr>
                    <th>Order Number</th>
                    <th>Order Date</th>
                    <th>Total</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($orders)) : ?>
                    <tr><td colspan="4">No orders found.</td></tr>
                <?php else : ?>
                    <?php foreach ($orders as $row) : 
                        $order->id = $row['id'];
                        $order->getOrderById();
                        $total = number_format($order->total_amount, 2);
                        $order_date = date('F j, Y', strtotime($row['created_on']));
                    ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['order_code'], ENT_QUOTES, 'UTF-8'); ?></td>
                        <td><?php echo htmlspecialchars($order_date, ENT_QUOTES, 'UTF-8'); ?></td>
                        <td>$<?php echo $total; ?></td>
                        <td class="btn-order">
                            <a href="view_order.php?order_id=<?php echo htmlspecialchars($row['id'], ENT_QUOTES, 'UTF-8'); ?>" class="view-order-btn">View Order</a>
                            <a href="invoice.php?order_id=<?php echo htmlspecialchars($row['id'], ENT_QUOTES, 'UTF-8'); ?>" class="view-order-btn">Download Invoice</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </section>
    <?php include 'footer.php'; ?>
</body>
</html>
