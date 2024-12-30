<?php
session_start();

error_reporting(E_ALL);
ini_set('display_errors', 1);

include_once 'config/database.php';
include_once 'objects/product.php';
include_once 'objects/category.php';
include_once 'objects/user.php';

// Create a new Product and Category object
$database = new Database();
$db = $database->getConnection();
$product = new Product($db);
$category = new Category($db);

// Verify user is logged in
$user = User::getInstance($db);
$res = $user->verifyUser(['admin']);

// Fetch all categories for the dropdown
$category_stmt = $category->getAll();
$categories = $category_stmt;

// Initialize variables and error array
$product_name = '';
$brief_description = '';
$full_description = '';
$unit_price = '';
$category_id = ''; 
$image = '';
$errors = [];

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Update product properties with form data
    $product_name = trim($_POST['product_name'] ?? '');
    $brief_description = trim($_POST['brief_description'] ?? '');
    $full_description = trim($_POST['full_description'] ?? '');
    $unit_price = trim($_POST['unit_price'] ?? '');
    $category_id = trim($_POST['category_id'] ?? '');
    $image = $_FILES['image']['name'] ?? '';

    // Basic validation
    if (empty($product_name)) {
        $errors['product_name'] = 'Product Name is required.';
    }
    if (empty($brief_description)) {
        $errors['brief_description'] = 'Short Description is required.';
    }
    if (empty($unit_price)) {
        $errors['unit_price'] = 'unit_price is required.';
    }
    if (empty($category_id)) {
        $errors['category_id'] = 'Category is required.';
    }

    // Image upload
    if ($_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $target_dir = "src/images/";
        $target_file = $target_dir . basename($_FILES["image"]["name"]);

        if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
            // Image uploaded successfully
        } else {
            $errors['image'] = 'Failed to upload image.';
        }
    }

    // Create product if there are no errors
    if (empty($errors)) {
        $product->product_name = $product_name;
        $product->brief_description = $brief_description;
        $product->full_description = $full_description;
        $product->unit_price = $unit_price;
        $product->category_id = $category_id;
        $product->image_file = $image;

        if ($product->createProduct()) {
            header("Location: admin_product_list.php");
            exit();
        } else {
            $errors['create'] = 'Failed to create product.';
        }
    }
}
?>
<!DOCTYPE html>
<html>

<head>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <title>Moon Jewelry Store - Create Products</title>
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
                            <li><a href="create_product.php" class="active"><i class="fa fa-pencil-square-o"></i>Add Product</a></li>
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
                        <div class="container-fluid">
                            <div class="row">
                                <div class="task-main">
                                    <div class="container">
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <div class="section-heading">
                                                    <h2>Create Product</h2>
                                                    <hr class="m-25">
                                                </div>

                                                <form action="create_product.php" method="POST" enctype="multipart/form-data">
                                                    <div class="row">
                                                        <div class="col-lg-6">
                                                            <fieldset>
                                                                <input type="text" id="product_name" name="product_name" value="<?php echo htmlspecialchars($product_name, ENT_QUOTES); ?>"
                                                                    placeholder="Product Name">
                                                                <?php if (isset($errors['product_name'])) : ?>
                                                                    <div class="error"><?php echo $errors['product_name']; ?></div>
                                                                <?php endif; ?>
                                                            </fieldset>
                                                        </div>
                                                        <div class="col-lg-6">
                                                            <fieldset>
                                                                <input placeholder="Brief Description" type="text" id="brief_description" name="brief_description" value="<?php echo htmlspecialchars($brief_description, ENT_QUOTES); ?>">
                                                                <?php if (isset($errors['brief_description'])) : ?>
                                                                    <div class="error"><?php echo $errors['brief_description']; ?></div>
                                                                <?php endif; ?>
                                                            </fieldset>
                                                        </div>
                                                        <div class="col-lg-12">
                                                            <fieldset>
                                                                <textarea id="full_description" name="full_description"><?php echo htmlspecialchars($full_description, ENT_QUOTES); ?></textarea>
                                                                <?php if (isset($errors['full_description'])) : ?>
                                                                    <div class="error"><?php echo $errors['full_description']; ?></div>
                                                                <?php endif; ?>
                                                            </fieldset>
                                                        </div>
                                                        <div class="col-lg-12">
                                                            <fieldset>
                                                                <input placeholder="Product Price" type="text" id="unit_price" name="unit_price" value="<?php echo htmlspecialchars($unit_price, ENT_QUOTES); ?>">
                                                                <?php if (isset($errors['unit_price'])) : ?>
                                                                    <div class="error"><?php echo $errors['unit_price']; ?></div>
                                                                <?php endif; ?>
                                                            </fieldset>
                                                        </div>
                                                        <div class="col-lg-12">
                                                            <fieldset>
                                                                <select id="category_id" name="category_id">
                                                                    <option value="">Select a Category</option>
                                                                    <?php foreach ($categories as $cat) : ?>
                                                                        <option value="<?php echo htmlspecialchars($cat['id'], ENT_QUOTES); ?>" <?php echo ($category_id == $cat['id']) ? 'selected' : ''; ?>>
                                                                            <?php echo htmlspecialchars($cat['category_name'], ENT_QUOTES); ?>
                                                                        </option>
                                                                    <?php endforeach; ?>
                                                                </select>
                                                                <?php if (isset($errors['category_id'])) : ?>
                                                                    <div class="error"><?php echo $errors['category_id']; ?></div>
                                                                <?php endif; ?>
                                                            </fieldset>
                                                        </div>

                                                        <div class="col-lg-12">
                                                            <fieldset class="image_upload">
                                                                <i class="fa fa-cloud-upload"></i>
                                                                <p>Upload Product Image</p>
                                                                <input type="file" id="image" name="image" multiple onchange="showFileName()">

                                                                <?php if ($image) : ?>
                                                                    <img src="src/images/<?php echo htmlspecialchars($image, ENT_QUOTES); ?>" alt="<?php echo htmlspecialchars($product_name, ENT_QUOTES, 'UTF-8'); ?>" style="width:100px;">
                                                                <?php endif; ?>

                                                                <div id="file-name"></div>

                                                                <?php if (isset($errors['image'])) : ?>
                                                                    <div class="error"><?php echo $errors['image']; ?></div>
                                                                <?php endif; ?>
                                                            </fieldset>
                                                        </div>

                                                        <div class="col-lg-12">
                                                            <fieldset>
                                                                <button type="submit" id="form-submit"
                                                                    class="main-color-button">Create Product</button>
                                                                <?php if (isset($errors['create'])) : ?>
                                                                    <div class="error"><?php echo $errors['create']; ?></div>
                                                                <?php endif; ?>
                                                            </fieldset>
                                                        </div>
                                                    </div>
                                                </form>
                                                <?php if (isset($_GET['success']) && $_GET['success'] === 'true') : ?>
                                                    <div class="success">Product created successfully!</div>
                                                <?php endif; ?>
                                            </div>
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

<script>
    function showFileName() {
        var input = document.getElementById('image');
        var fileName = input.files[0].name;
        document.getElementById('file-name').innerText = "Selected file: " + fileName;
    }
</script>

</html>