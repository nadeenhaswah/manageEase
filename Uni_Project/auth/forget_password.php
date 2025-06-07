<?php
session_start();
include('../confing/DB_connection.php');


session_regenerate_id(true); // تم إضافة هذا السطر لتحديث معرف الجلسة بشكل دوري لتحسين الأمان ضد هجمات

if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
$email = isset($_SESSION['old_email']) ? $_SESSION['old_email'] : '';
$password = isset($_SESSION['old_password']) ? $_SESSION['old_password'] : '';
$confirm_password = isset($_SESSION['old_confirm_password']) ? $_SESSION['old_confirm_password'] : '';

unset($_SESSION['old_email'], $_SESSION['old_password'], $_SESSION['old_confirm_password']);
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
    <!-- tailwind  -->
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <!-- fontawesome  -->
    <script src="https://kit.fontawesome.com/3e1d046fbe.js" crossorigin="anonymous"></script>
    <!-- webSite icon  -->
    <link rel="shortcut icon" type="x-icon" href="../asset/images/favicon.ico">
    <!-- flowbite -->
    <link href="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.js"></script>
    <!-- main css  -->
    <link rel="stylesheet" href="../asset/css/inner.css">
    <title>manageEase</title>
</head>

<body>
    <div class="container mt-5">
        <div class="row">
            <div class="col-md-6 mt-5 d-flex  flex-column  justify-content-center align-items-center flex-nowrap">
                <?php if (isset($_SESSION['messege'])) : ?>
                    <div class="alert alert-primary alert-dismissible fade show session-msg" role="alert">
                        <strong>Done <span> </span> <span></span></strong> <?= $_SESSION['messege'] ?>
                        <span> </span> <span> </span> <span> </span> <span> </span> <span> </span> <span> </span>
                        <i class="fa-solid fa-circle-check" style="color: #65e67b;"></i>
                    </div>
                <?php unset($_SESSION['messege']);
                endif; ?>
                <form class="max-w-sm mx-auto switch d-flex  flex-column  justify-content-center align-items-center flex-nowrap"
                    action="reset_password_process.php" method="POST">
                    <div class="mb-3">
                        <label for="email" class="block mb-2 text-sm  font-medium text-gray-600 dark:text-white">Your email</label>
                        <input type="email" name="email" id="email"
                            value="<?= htmlspecialchars(isset($email) ? $email  : '', ENT_QUOTES, 'UTF-8') ?>"
                            class="shadow-xs bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 dark:shadow-xs-light" />
                        <?php
                        if (isset($_SESSION['email_err'])): ?>
                            <p class="error"><?= $_SESSION['email_err'] ?> </p>
                        <?php unset($_SESSION['email_err']);
                        endif; ?>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="block mb-2 text-sm font-medium text-gray-600 dark:text-white">New password</label>
                        <input type="password" name="password" placeholder="••••••••" id="password"
                            value="<?= htmlspecialchars(isset($password) ? $password : '', ENT_QUOTES, 'UTF-8') ?>"
                            class="shadow-xs bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 dark:shadow-xs-light" />
                        <?php
                        if (isset($_SESSION['password_err'])): ?>
                            <p class="error"><?= $_SESSION['password_err'] ?> </p>
                        <?php unset($_SESSION['password_err']);
                        endif; ?>
                    </div>
                    <div class="mb-5">
                        <label for="repeat-password" class="block mb-2 text-sm font-medium text-gray-600 dark:text-white">Confirm New password</label>
                        <input type="password" name="confirm_password" placeholder="••••••••" id="repeat-password"
                            value="<?= htmlspecialchars(isset($confirm_password) ? $confirm_password : '', ENT_QUOTES, 'UTF-8') ?>"
                            class="shadow-xs bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 dark:shadow-xs-light" />
                        <?php
                        if (isset($_SESSION['confirm_password_err'])): ?>
                            <p class="error"><?= $_SESSION['confirm_password_err'] ?> </p>
                        <?php unset($_SESSION['confirm_password_err']);
                        endif; ?>
                    </div>

                    <button type="submit" name="reset" class="mb-5 text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-3 py-2 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Reset Password</button>
                </form>
                <a type="submit" href="logIn.php" class="text-secondary   focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg   text-sm px-5 py-2 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Go To LogIn</a>
            </div>
            <div class="col-md-6">
                <img class="animate-pulse" src="../asset/images/resetPass.png" alt="">
            </div>
        </div>
    </div>
</body>

</html>