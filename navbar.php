<nav class="header-nav">
    <div class="top-bar">
        <div class="promo">
            <span>Sparkle in Style: 15% OFF All Diamond Jewelry <i class="separator">|</i></span>
            <span>Summer Elegance: 20% OFF All Gold Necklaces <i class="separator">|</i></span>
            <span>Timeless Treasures: Buy One, Get One 50% OFF on Select Earrings <i class="separator">|</i></span>
            <span>Shimmer and Shine: Free Shipping on Orders Over $75 <i class="separator">|</i></span>
            <span>Radiant Savings: 10% OFF on Your First Purchase <i class="separator">|</i></span>
            <span>Glamorous Gifts: 25% OFF All Wedding Bands <i class="separator">|</i></span>
            <span>Luxury for Less: Up to 30% OFF on All Bracelets <i class="separator">|</i></span>
            <span>Dazzling Deals: Extra 10% OFF All Custom Jewelry <i class="separator">|</i></span>
            <span>Brilliant Buys: Save $50 on Orders Over $200 <i class="separator">|</i></span>
            <span>Exclusive Elegance: 15% OFF All Pearl Collections <i class="separator">|</i></span>
        </div>
    </div>
    <header>
        <div class="nav-container">
            <a class="logo" href="index.php">
                <img src="src/images/logo.png" alt="MOON LOGO">
            </a>
            <nav>
                <ul>
                    <?php if (isset($_SESSION['role'])): ?>
                        <li><a href="index.php">Home</a></li>
                        <li><a href="shop.php">Shop</a></li>
                        <li><a href="orders.php">Orders</a></li>
                        <li><a href="cart.php">Cart</a></li>
                    <?php else: ?>
                        <li><a href="index.php">Home</a></li>
                        <li><a href="signup.php?login=1">Login</a></li>
                        <li><a href="signup.php?login=0">Signup</a></li>
                    <?php endif; ?>
                    <?php if (isset($_SESSION['role'])): ?>
                        <div class="dropdown">
                            <li><a href="#"><span style="padding-right:7px;">Profile</span><i class="fa fa-user"></i></a></li>
                            <div class="dropdown-content">
                                <ul>
                                    <li><a href="change_password.php">Change Password</a></li>
                                    <li><a href="logout.php">Logout</a></li>
                                </ul>
                            </div>
                        </div>
                    <?php endif; ?>
                </ul>
            </nav>
        </div>
    </header>
</nav>