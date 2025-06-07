<?php
session_start();
session_regenerate_id(true);
// إنشاء CSRF Token إذا لم يكن موجوداً
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32)); // توليد CSRF Token عشوائي
}
$username = isset($_SESSION['old_username']) ? $_SESSION['old_username'] : '';
$password = isset($_SESSION['old_password']) ? $_SESSION['old_password'] : '';

unset($_SESSION['old_username'], $_SESSION['old_email'], $_SESSION['old_password'], $_SESSION['old_confirm_password']);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH"
        crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>
    <!-- fontawesome  -->
    <script src="https://kit.fontawesome.com/3e1d046fbe.js" crossorigin="anonymous"></script>
    <!-- webSite icon  -->
    <link rel="shortcut icon" href="../asset/images/icon2.jpg">
    <!-- main css  -->
    <link rel="stylesheet" href="../asset/css/signUp_logIn2.css">
    <title>manageEase</title>

</head>

<body>
    <div class="containerr">
        <div class="content">
            <img src="../asset/images/login.png" alt="">
            <div class="msg">
                <h5>Team member ?</h5>
                <p>Use the email and password sent to you by your project manager to log in.</p>

            </div>
        </div>
        <div class="form-container">
            <form action="logIn_process.php" method="POST">

                <?php if (isset($_SESSION['messege'])) : ?>
                    <div class="alert alert-primary alert-dismissible fade show session-msg" role="alert">
                        <strong>Done <span> </span> <span></span></strong> <?= $_SESSION['messege'] ?>
                        <span> </span> <span> </span> <span> </span> <span> </span> <span> </span> <span> </span>
                        <i class="fa-solid fa-circle-check" style="color: #65e67b;"></i>
                    </div>
                <?php unset($_SESSION['messege']);
                endif; ?>
                <h1>LogIn</h1>
                <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>" />

                <div class="input-field">
                    <i class="fa-regular fa-user"></i>
                    <input type="text" class="username" placeholder="Username" name="username" value="<?= htmlspecialchars(isset($username) ? $username : '', ENT_QUOTES, 'UTF-8'); ?>" autocapitalize="off" />
                    <?php unset($_SESSION['old_username']) ?>
                </div>
                <?php
                if (isset($_SESSION['usererror'])): ?>
                    <p class="error"><?= htmlspecialchars($_SESSION['usererror'], ENT_QUOTES, 'UTF-8') ?> </p>
                <?php unset($_SESSION['usererror']);
                endif; ?>
                <div class="input-field">
                    <i class="fa-solid fa-lock"></i>
                    <input type="password" placeholder="Password" name="password" value="<?= htmlspecialchars(isset($password) ? $password : '', ENT_QUOTES, 'UTF-8') ?>" />
                </div>
                <?php
                if (isset($_SESSION['passerror'])): ?>
                    <p class="error"><?= htmlspecialchars($_SESSION['passerror'], ENT_QUOTES, 'UTF-8') ?> </p>
                <?php unset($_SESSION['passerror']);
                endif; ?>
                <input type="submit" value="LogIn" class="btn solid" name="login">

            </form>
            <div class="no-account account">
                <p>Don't have an account ? <a href="signup.php">Register</a></p>
            </div>
            <a href="forget_password.php" class="forgetPass">Forget Password </a>


        </div>
    </div>

</html>