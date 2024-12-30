<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Check if the user is logged in and is an admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'user') {
    header("Location: signup.php"); 
    exit();
}

// Enable error reporting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include_once 'config/database.php';
include_once 'objects/product.php';
include_once 'objects/category.php';
include_once 'objects/user.php';
include_once 'objects/cart.php';

$database = new Database();
$db = $database->getConnection();

// Initialize User object
$user = User::getInstance($db);

// Ensure user is authenticated
if (!$user->isAuthenticated()) {
    session_destroy();
    header("Location: signup.php");
    exit();
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Create Cart object


    $cart = new Cart($db);
    $product_id = isset($_POST['product_id']) ? (int)$_POST['product_id'] : 0;
    $quantity = isset($_POST['quantity']) ? (int)$_POST['quantity'] : 1;
    
    if ($product_id > 0) {
        $cart->addToCart($product_id, $quantity);
        header('Location: cart.php');
        exit();
    } else {
        $error_message = urlencode("Invalid Product ID");
        header("Location: 404_page.php?message=$error_message");
        exit();
    }
} else {

// Create Product object
$product = new Product($db);

$selected_category = isset($_GET['category']) ? $_GET['category'] : '';

// Prepare the SQL query
$query = "SELECT p.*, c.category_name AS category_name 
          FROM Products p 
          JOIN Categories c ON p.category_id = c.id 
          WHERE p.active = 1";
if ($selected_category) {
    $query .= " AND c.category_name = :selected_category";
}
$query .= " ORDER BY p.created_on DESC";
$stmt = $db->prepare($query);

if ($selected_category) {
    $stmt->bindParam(':selected_category', $selected_category);
}

if ($stmt->execute()) {
    $categories = $db->query("SELECT DISTINCT category_name FROM Categories WHERE active = 1")->fetchAll(PDO::FETCH_ASSOC);
} else {
    echo "Error executing query: " . $stmt->errorInfo()[2];
    exit();
}
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MOON | Home</title>
    <link href="https://fonts.googleapis.com/css2?family=Cabin&family=Playfair+Display&display=swap" rel="stylesheet">
    <link rel="icon" type="image/x-icon" href="Imgs/favicon.png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="src/css/styles.css">
    <link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.css" />
</head>
<body>

    <?php include 'navbar.php'; ?>

    <header>
    </header>

    <main>
        <div class="shop-container w-85">
            <div class="shop-margin">
                <form method="GET" action="" class="filter-form">
                    <h1>Explore Our Collection</h1>
                    <div class="filter-cat">
                        <label for="category" class="filter-label">Filter by category:</label>
                        <select name="category" id="category" class="filter-select">
                            <option value="">All Categories</option>
                            <?php foreach ($categories as $category): ?>
                                <option value="<?php echo htmlspecialchars($category['category_name'], ENT_QUOTES, 'UTF-8'); ?>"
                                    <?php echo (isset($_GET['category']) && $_GET['category'] === $category['category_name']) ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($category['category_name'], ENT_QUOTES, 'UTF-8'); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <button type="submit" class="btn-theme">
                            <a>Filter</a>
                        </button>
                    </div>
                </form>

                <div id="products" class="product-grid">
                    <!-- Products will be dynamically added here -->
                    <?php while ($row = $stmt->fetch(PDO::FETCH_ASSOC)): ?>
                        <div class="product">
                            <a href="product_detail.php?id=<?php echo htmlspecialchars($row['id'], ENT_QUOTES, 'UTF-8'); ?>" class="product-link" style="color: black;">
                                <img src="src/images/<?php echo htmlspecialchars($row['image_file'] ?? 'default.jpg', ENT_QUOTES, 'UTF-8'); ?>" alt="<?php echo htmlspecialchars($row['product_name'] ?? 'Product', ENT_QUOTES, 'UTF-8'); ?>">
                                <div class="product-info-box">
                                    <h3><?php echo htmlspecialchars($row['product_name'] ?? 'No Name', ENT_QUOTES, 'UTF-8'); ?></h3>
                                
                                    <p class="short-desc"><?php echo htmlspecialchars($row['brief_description'] ?? 'No Description', ENT_QUOTES, 'UTF-8'); ?></p>
                                    <hr />
                                    <div class="price-cat">
                                        <p class="card-text"><span class="cat">category: </span><span class="cat-name"><?php echo htmlspecialchars($row['category_name'] ?? 'Unknown', ENT_QUOTES, 'UTF-8'); ?></span></p>
                                        <p class="card-text price">$ <?php echo htmlspecialchars($row['unit_price'] ?? 'Unknown', ENT_QUOTES, 'UTF-8'); ?></p>
                                    </div>
                                </div>
                            </a>
                            <div class="product-btn">
                                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                                    <input type="hidden" name="product_id" value="<?php echo htmlspecialchars($row['id'], ENT_QUOTES, 'UTF-8'); ?>">
                                    <button class="btn-theme">
                                        <a>Add to Cart</a>
                                    </button>
                                </form>
                            </div>
                        </div>
                    <?php endwhile; ?>
                </div>
            </div>
        </div>
    </main>

    <?php include 'footer.php'; ?>

    <!-- Jquery CDN -->
    <script src="https://code.jquery.com/jquery-3.4.1.js"></script>

    <!-- Slick Slider CDN JS file -->
    <script type="text/javascript" src="//cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js"></script>
</body>
</html>
