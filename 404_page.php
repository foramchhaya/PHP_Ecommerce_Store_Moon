<?php
session_start();

$message = isset($_GET['message']) ? htmlspecialchars($_GET['message'], ENT_QUOTES, 'UTF-8') : 'An error occurred.';

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Cabin&family=Playfair+Display&display=swap" rel="stylesheet">
    <title>Error Page</title>
    <link rel="stylesheet" href="src/css/thankyou.css">
    <link rel="stylesheet" href="src/css/styles.css">
</head>

<body>
    <?php include 'navbar.php'; ?>

    <section class="thank-you-section thankyou-sec">
        <div class="thank-you-card">
        <img src="src/images/error.jpg" >
        <p><?php echo $message; ?></p>
        <div class="thank-you-actions">
                <a href="index.php" class="btn">Back to Home</a>
            </div>
        </div>
    </section>

   
</body>

</html>
