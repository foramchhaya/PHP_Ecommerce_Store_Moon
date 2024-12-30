<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$email_address = isset($_SESSION['email_address']) ? $_SESSION['email_address'] : 'Email not set';
?>

<nav class="navbar navbar-dark bg-dark">
    <div>
        <a class="navbar-brand logo" href="index.html">MOON</a>
    </div>
    <div class="justify-content-end">
        <a href="#" class="con-email"><i class="fa fa-envelope"></i><?php echo htmlspecialchars($email_address); ?></a>
    </div>
</nav>
