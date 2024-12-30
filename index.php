<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Include necessary files
include_once 'config/database.php'; 
include_once 'objects/product.php';

// Check if the user is an admin and include the admin page if true
if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin') {
    include 'admin_index.php';
}

// Instantiate the database and product objects
$database = new Database();
$db = $database->getConnection();
$product = new Product($db);

// Get random products
$stmt = $product->getRendomProducts();
$products = $stmt;

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

    <main>
        <div class="ad-sec">
            <div>
                <p>The silent language of self-expression</p>
                <h1>SHINE BRIGHTER THAN YOUR DIAMONDS</h1>
                <button class="btn-theme">
                    <a href="product.php">Shop All New Jewelry</a>
                </button>
            </div>
        </div>

        <div class="product-info">
            <h1>Discover Our New Collection</h1>
            <div class="container">
                <div class="p1-box">
                    <img src="src/images/footer1.jpg">
                    <p>Bracelet</p>
                </div>
                <div class="p2-box">
                    <img src="src/images/footer2.jpg">
                    <p>Chain</p>
                </div>
                <div class="p3-box">
                    <img src="src/images/footer3.jpg">
                    <p>METAL EARRINGS</p>
                </div>
                <div class="p4-box">
                    <img src="src/images/footer4.jpg">
                    <p>DIAMOND RINGS</p>
                </div>
                <div class="p5-box">
                    <img src="src/images/footer5.jpg">
                    <p>GOLD EARRING</p>
                </div>
                <div class="p6-box">
                    <img src="src/images/footer7.jpg">
                    <p>GOLD BRACELET</p>
                </div>
            </div>
        </div>

        <div class="ad-sec mb-0" id="advert-sec">
            <div class="content">
                <p>Shop now and let your jewelry do the talking</p>
                <h1>Life's too short for ordinary jewelry</h1>
                <button class="btn-theme">
                    <a href="product.php">Explore Our Collection</a>
                </button>
            </div>
        </div>

        <h2 class="index-product-title"> Explore Our Collection</h2>
        <div id="product" class="product-grid-home">
            <?php for ($x = 0; $x < 3 && $x < count($products); $x++): ?>
                <?php $row = $products[$x]; ?>
                <div class="product">
                    <a href="product_detail.php?id=<?= $row['id'] ?>" class="product-link" style="color: black;">
                        <img src="src/images/<?= htmlspecialchars($row['image_file']) ?>" alt="<?= htmlspecialchars($row['product_name']) ?>">
                        <div class="product-info-box">
                            <h3><?= htmlspecialchars($row['product_name']) ?></h3>
                            <p class="short-desc"><?= htmlspecialchars($row['brief_description']) ?></p>
                            <hr>
                            <div class="price-cat">
                                <p class="card-text"><span class="cat">Category: </span><span class="cat-name"><?= htmlspecialchars($row['category_name']) ?></span></p>
                                <p class="card-text price">$<?= number_format($row['unit_price'], 2) ?></p>
                            </div>
                        </div>
                    </a>
                    <div class="product-btn">
                        <form action="/MoonJewelryStore/shop.php" method="post">
                            <input type="hidden" name="product_id" value="<?= $row['id'] ?>">
                            <button class="btn-theme">Add to Cart</button>
                        </form>
                    </div>
                </div>
            <?php endfor; ?>
        </div>
    </main>

    <?php include 'footer.php'; ?>

</body>

</html>
