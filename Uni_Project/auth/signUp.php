<?php
session_start();

session_regenerate_id(true); // تم إضافة هذا السطر لتحديث معرف الجلسة بشكل دوري لتحسين الأمان ضد هجمات

if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
$username = isset($_SESSION['old_username']) ? $_SESSION['old_username'] : '';
$email = isset($_SESSION['old_email']) ? $_SESSION['old_email'] : '';
$job = isset($_SESSION['old_job']) ? $_SESSION['old_job'] : '';
$phone = isset($_SESSION['old_phone']) ? $_SESSION['old_phone'] : '';
$password = isset($_SESSION['old_password']) ? $_SESSION['old_password'] : '';
$confirm_password = isset($_SESSION['old_confirm_password']) ? $_SESSION['old_confirm_password'] : '';

unset($_SESSION['old_username'], $_SESSION['old_email'], $_SESSION['old_job'], $_SESSION['old_phone'], $_SESSION['old_password'], $_SESSION['old_confirm_password']);

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
            <img src="../asset/images/signUp.png" alt="">
            <div class="msg">
                <h5>Project manager ?</h5>
                <p>Sign up to add your team and manage projects.</p>
            </div>
        </div>
        <div class="form-container">
            <form action="signUp_process.php" method="POST">
                <h1 class="signupHead">Sign Up</h1>
                <!-- CSRF Token Hidden Field -->
                <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token']) ?>">

                <div class="input-field">
                    <i class="fa-regular fa-user"></i>
                    <input type="text" class="username" placeholder="Username" autocapitalize="off" name="username"
                        value="<?= htmlspecialchars(isset($username) ? $username : '', ENT_QUOTES, 'UTF-8'); ?>">
                    <!-- إضافة القيمة المدخلة من قبل المستخدم إذا كان هناك خطأ سابق. -->
                    <!-- يتم إعادة تحميل النموذج مع رسالة الخطأ، ولكن بدون هذا الكود، سيتم مسح البيانات التي أدخلها المستخدم.
                    باستخدام هذا الكود، يتم إعادة عرض القيمة التي أدخلها المستخدم حتى لا يضطر لإعادة كتابتها.  -->
                </div>
                <?php
                if (isset($_SESSION['usererror'])): ?>
                    <p class="error"><?= $_SESSION['usererror'] ?> </p>
                <?php unset($_SESSION['usererror']);
                endif; ?>
                <div class="input-field">
                    <i class="fa-regular fa-envelope"></i>
                    <input type="email" class="email" placeholder="Email" name="email"
                        value="<?= htmlspecialchars(isset($email) ? $email  : '', ENT_QUOTES, 'UTF-8') ?>"> <!-- إضافة القيمة المدخلة من قبل المستخدم إذا كان هناك خطأ سابق. -->
                </div>

                <?php
                if (isset($_SESSION['email_err'])): ?>
                    <p class="error"><?= $_SESSION['email_err'] ?> </p>
                <?php unset($_SESSION['email_err']);
                endif; ?>
                <div class="input-field">
                    <i class="fa-solid fa-bag-shopping"></i>
                    <input type="text" class="job" placeholder="Job 'optional'" name="job"
                        value="<?= htmlspecialchars(isset($job) ? $job  : '', ENT_QUOTES, 'UTF-8') ?>"> <!-- إضافة القيمة المدخلة من قبل المستخدم إذا كان هناك خطأ سابق. -->
                </div>
                <div class="input-field">
                    <i class="fa-solid fa-phone"></i>
                    <input type="number" class="phone" placeholder="Phone 'optional'" name="phone"
                        value="<?= htmlspecialchars(isset($phone) ? $phone  : '', ENT_QUOTES, 'UTF-8') ?>"> <!-- إضافة القيمة المدخلة من قبل المستخدم إذا كان هناك خطأ سابق. -->
                </div>
                <div class="input-field">
                    <i class="fa-solid fa-lock"></i>
                    <input type="password" placeholder="Password" name="password"
                        value="<?= htmlspecialchars(isset($password) ? $password : '', ENT_QUOTES, 'UTF-8') ?>">
                </div>
                <?php
                if (isset($_SESSION['password_err'])): ?>
                    <p class="error"><?= $_SESSION['password_err'] ?> </p>
                <?php unset($_SESSION['password_err']);
                endif; ?>
                <div class="input-field">
                    <i class="fa-solid fa-lock"></i>
                    <input type="password" placeholder="Confirm Password" name="confirm_password"
                        value="<?= htmlspecialchars(isset($confirm_password) ? $confirm_password : '', ENT_QUOTES, 'UTF-8') ?>"> <!-- إضافة القيمة المدخلة من قبل المستخدم إذا كان هناك خطأ سابق. -->
                </div>
                <?php
                if (isset($_SESSION['confirm_password_err'])): ?>
                    <p class="error"><?= $_SESSION['confirm_password_err'] ?> </p>
                <?php unset($_SESSION['confirm_password_err']);
                endif; ?>
                <input type="submit" value="SignUp" name="signup" class="btn">
            </form>

            <div class="no-account account accountSinup">
                <p>I'm alrady have an account <a href="logIn.php">Login</a></p>
            </div>
        </div>
    </div>
</body>

</html>