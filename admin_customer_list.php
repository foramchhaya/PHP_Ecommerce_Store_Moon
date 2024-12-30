<?php
session_start();


include_once 'config/database.php';
include_once 'objects/user.php';

$database = new Database();
$db = $database->getConnection();

// Check if the user is logged in
$user = User::getInstance($db);
$user->verifyUser(['admin']);

// Handle form submission for activating or deactivating users
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['id'])) {
    $userId = $_POST['id'];

    $userObj = User::getInstance($db);
    $userObj->id = $userId;
    $userObj->fetchUser();

    if ($userObj->email_address) {
        $newStatus = $userObj->active ? 0 : 1;
        $userObj->updateUserStatus($userId, $newStatus);

        header("Location: " . $_SERVER["PHP_SELF"]);
        exit();
    }
}

// Fetch all users
$stmt = $user->getAllUsers();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Cabin&family=Playfair+Display&display=swap" rel="stylesheet">
    <title>Moon Jewelry Store - Manage Customers</title>
    <link rel="stylesheet" href="src/css/order_history.css">
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
                            <li><a href="admin_customer_list.php" class="active"><i class="fa fa-tasks"></i>Customer List</a></li>
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
                                <h1 class="main-heading"><i class="fa fa-user"></i>Manage Customers</h1>
                            </div>
                        </div>
                        <div class="container-fluid mt-50 mb-50">
                            <div class="row">
                                <div class="col-md-12">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>EMAIL</th>
                                                <th>STATUS</th>
                                                <th>ACTION</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php if ($stmt->rowCount() > 0) : ?>
                                                <?php while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) : ?>
                                                    <tr>
                                                        <td><?php echo htmlspecialchars($row['email_address'] ?? '', ENT_QUOTES, 'UTF-8'); ?></td>
                                                        <td><?php echo $row['active'] ? 'Active' : 'Inactive'; ?></td>
                                                        <td>
                                                            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
                                                                <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                                                                <button type="submit" class="<?php echo $row['active'] ? 'btn btn-danger' : 'btn btn-success'; ?>">
                                                                    <?php echo $row['active'] ? 'Deactivate' : 'Activate'; ?>
                                                                </button>
                                                            </form>
                                                        </td>
                                                    </tr>
                                                <?php endwhile; ?>
                                            <?php else: ?>
                                                <tr>
                                                    <td colspan="3">No customers found.</td>
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
