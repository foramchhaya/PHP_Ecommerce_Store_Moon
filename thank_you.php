<?php
session_start();

include_once 'config/database.php';
include_once 'objects/user.php';

$database = new Database();
$db = $database->getConnection();

$user = User::getInstance($db);
$res = $user->verifyUser(['user']);


// Get Order number and total amount from the session
$order_number = $_SESSION['order_code'];
$total = $_SESSION['total_amount'];


// Get order_id from the URL
$order_id = isset($_GET['order_id']) ? $_GET['order_id'] : 0;
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thank You</title>
    <link rel="stylesheet" href="src/css/thankyou.css">
    <link rel="stylesheet" href="src/css/styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>

<body>
    <?php include 'navbar.php'; ?>

    <section class="thank-you-section thankyou-sec">
        <div class="thank-you-card">
            <img src="src/images/thank-you.jpg" />
            <h1>Your order has been placed successfully.</h1>
            <div class="order-detail">
                <p><b>Order Number:</b> <?php echo htmlspecialchars($order_number, ENT_QUOTES, 'UTF-8'); ?></p>
                <p><b>Total Amount:</b> $<?php echo number_format($total, 2); ?></p>
            </div>
            <div class="thank-you-actions">
                <a href="index.php" class="btn">Back to Home</a>
                <a href="orders.php" class="btn">View Order History</a>
                <a href="invoice.php?order_id=<?php echo htmlspecialchars($order_id, ENT_QUOTES, 'UTF-8'); ?>" class="btn">Download Invoice</a>
            </div>
        </div>
    </section>
    <?php include 'footer.php'; ?>
</body>

</html>