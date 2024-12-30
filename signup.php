<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include_once 'config/database.php';
include_once 'objects/user.php';

$database = new Database();
$db = $database->getConnection();
$user = User::getInstance($db);

// Function to handle redirection
function redirectTo($location) {
    header("Location: $location");
    exit();
}

// Handle login
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['login'])) {
        $user->email_address = $_POST['email'] ?? '';
        $user->password_hash = $_POST['password'] ?? '';
        $isValidUser = $user->authenticate();

        if ($isValidUser) {
            $_SESSION['user_id'] = $user->id;
            $_SESSION['role'] = $user->role;
            $_SESSION['email_address'] = $user->email_address;

            $redirectLocation = $user->role === 'admin' ? 'admin_index.php' : 'index.php';
            redirectTo($redirectLocation);
        } else {
            $loginError = "Invalid login credentials.";
        }
    }

    if (isset($_POST['signup'])) {
        $user->role = 'user';
        $user->email_address = $_POST['email'] ?? '';
        $user->password_hash = $_POST['password'] ?? '';
       

        if ($user->register()) {
            redirectTo('index.php');
        } else {
            $signupError = "Unable to register user.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Moon Jewelry Store | LogIn SignUp</title>
    <link rel="icon" type="image/x-icon" href="src/images/logo.png">
    <link rel="stylesheet" href="src/css/signup.css">
</head>
<body>
    <section>
        <div class="container">
            <!-- Login Form -->
            <div class="user signinBox">
                <div class="imgBox">
                    <img src="src/images/login.jpg" alt="">
                </div>
                <div class="formBox">
                    <form action="" method="post">
                        <div class="sinup-logo"><a href="index.php"><img src="src/images/logo.png" alt=""></a></div>
                        <h2>Login</h2>
                        <?php if (isset($loginError)) echo "<div class='auth-error'>$loginError</div>"; ?>
                        <input type="email" name="email" placeholder="Enter Email" required>
                        <input type="password" name="password" placeholder="Enter Password" required>
                        <input type="submit" name="login" value="Login">
                        <p class="signup">Don't have an Account? <a href="#" onclick="doToggle();">Sign Up</a></p>
                    </form>
                </div>
            </div>
            <!-- Signup Form -->
            <div class="user signupBox">
                <div class="formBox">
                    <form action="" method="post">
                    <div class="sinup-logo"><a href="index.php"><img src="src/images/logo.png" alt=""></a></div>
                        <h2>Create An Account</h2>
                        <?php if (isset($signupError)) echo "<div class='alert alert-danger'>$signupError</div>"; ?>
                        <input type="email" name="email" placeholder="Enter Email" required>
                        <input type="password" name="password" placeholder="Enter Password" required>
                        <input type="password" name="confirm_password" placeholder="Confirm Password" required>
                        <input type="submit" name="signup" value="Sign Up">
                        <p class="signup">Already have an Account? <a href="#" onclick="doToggle();">Login</a></p>
                    </form>
                </div>
                <div class="imgBox">
                    <img src="src/images/signup.webp" alt="">
                </div>
            </div>
        </div>
    </section>
    <script type="text/javascript">
        function doToggle(){
            var container = document.querySelector('.container');
            container.classList.toggle('active');
        }

        window.onload = function() {
            const params = new URLSearchParams(window.location.search);
            const isLogin = params.get('login');

            if (isLogin === '1') {
                document.querySelector('.container').classList.remove('active'); // Show login form
            } else if (isLogin === '0' || isLogin === null) {
                document.querySelector('.container').classList.add('active'); // Show signup form
            }
        };
    </script>
</body>
</html>
