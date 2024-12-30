<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

include_once 'config/database.php';
include_once 'objects/product.php';
include_once 'objects/category.php';
include_once 'objects/user.php';

$database = new Database();
$db = $database->getConnection();

// Check if the user is logged in
$user = User::getInstance($db);
$user->verifyUser(['admin']);


$product = new Product($db);
$category = new Category($db);

// Get selected category from form submission
$selected_category = isset($_GET['category']) ? $_GET['category'] : '';

// Fetch products by selected category
$stmt = $product->getProductsByCategory($selected_category);

// Fetch distinct Categories for filter dropdown
$category_stmt = $db->prepare("SELECT id, category_name FROM Categories WHERE active = 1");
$category_stmt->execute();
$categories = $category_stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <title>Moon Jewelry Store - Manage Products</title>
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
                            <li><a href="admin_product_list.php" class="active"><i class="fa fa-list-alt"></i>Product List</a></li>
                            <li><a href="admin_customer_list.php"><i class="fa fa-tasks"></i>Customer List</a></li>
                            <li><a href="admin_order_list.php"><i class="fa fa-tasks"></i>Order List</a></li>
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
                                <h1 class="main-heading"><i class="fa fa-tasks"></i>Manage Product</h1>
                            </div>
                        </div>
                        <div class="container-fluid mt-50 mb-50">
                            <div class="row">
                                <div class="col-md-12 product-list-search">
                                    <form method="GET" action="" class="search-filter">
                                        <label for="category" class="filter-label">Filter by category:</label>
                                        <select name="category" id="category" class="filter-select">
                                            <option value="">All Categories</option>
                                            <?php foreach ($categories as $category_item) : ?>
                                                <option value="<?php echo htmlspecialchars($category_item['id'], ENT_QUOTES, 'UTF-8'); ?>"
                                                    <?php echo (isset($_GET['category']) && $_GET['category'] === $category_item['id']) ? 'selected' : ''; ?>>
                                                    <?php echo htmlspecialchars($category_item['category_name'], ENT_QUOTES, 'UTF-8'); ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                        <button type="submit" class="checkout-btn"><a>Filter</a></button>
                                    </form>
                                    <form action="create_product.php" method="GET">
                                        <button class="checkout-btn">
                                            <a>Create New Product</a>
                                        </button>
                                    </form>
                                </div>
                                <hr/>
                                <div class="col-md-12">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>IMAGE</th>
                                                <th>PRODUCT NAME</th>
                                                <th>SHORT DESC</th>
                                                <th>PRICE</th>
                                                <th>CATEGORY</th>
                                                <th>ACTION</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php if ($stmt->rowCount() > 0): ?>
                                                <?php while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) : ?>
                                                    <tr>
                                                        <td class="product_image">
                                                            <img src="src/images/<?php echo htmlspecialchars($row['image_file'], ENT_QUOTES, 'UTF-8'); ?>"
                                                                alt="<?php echo htmlspecialchars($row['product_name'] ?? 'Product', ENT_QUOTES, 'UTF-8'); ?>"
                                                                style="width:100%;">
                                                        </td>
                                                        <td><?php echo htmlspecialchars($row['product_name'], ENT_QUOTES, 'UTF-8'); ?></td>
                                                        <td><?php echo htmlspecialchars($row['brief_description'], ENT_QUOTES, 'UTF-8'); ?></td>
                                                        <td><?php echo htmlspecialchars($row['unit_price'], ENT_QUOTES, 'UTF-8'); ?></td>
                                                        <td><?php echo htmlspecialchars($row['category_name'], ENT_QUOTES, 'UTF-8'); ?></td>
                                                        <td>
                                                            <div class="address_buttons">
                                                                <?php if ($row['active'] == 0): ?>
                                                                    <a class="btn btn-success" href="active_product.php?id=<?php echo htmlspecialchars($row['id'], ENT_QUOTES, 'UTF-8'); ?>">Activate</a>
                                                                <?php else: ?>
                                                                    <a class="btn btn-success" href="edit_product.php?id=<?php echo htmlspecialchars($row['id'], ENT_QUOTES, 'UTF-8'); ?>">Edit</a> 
                                                                    <a class="btn btn-danger" href="deactive_product.php?id=<?php echo htmlspecialchars($row['id'], ENT_QUOTES, 'UTF-8'); ?>"
                                                                        onclick="return confirm('Are you sure you want to delete this product?');">Deactivate</a>
                                                                <?php endif; ?>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                <?php endwhile; ?>
                                            <?php else: ?>
                                                <tr>
                                                    <td colspan="6">No products found.</td>
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