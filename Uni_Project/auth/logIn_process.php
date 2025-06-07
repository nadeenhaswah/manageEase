<?php
include('../confing/DB_connection.php');
session_start();
session_regenerate_id(true); // تغيير معرف الجلسة بعد بدء الجلسة

//  الحماية من CSRF
if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
    die("Unauthorized request.");
}

//  الحد الأقصى للمحاولات ومدة الحظر
$maxAttempts = 3;
$initialLockTime = 600; // 10 دقايق

// تعريف المحاولات إن ما كانت موجودة
if (!isset($_SESSION['login_attempts'])) {
    $_SESSION['login_attempts'] = 0;
    $_SESSION['lock_time'] = 0;
}

// التحقق من الحظر الحالي
if ($_SESSION['login_attempts'] >= $maxAttempts) {
    $elapsed = time() - $_SESSION['lock_time'];
    $lockDuration = $initialLockTime * ($_SESSION['login_attempts'] - $maxAttempts + 1);

    if ($elapsed < $lockDuration) {
        $wait = ceil(($lockDuration - $elapsed) / 60);
        $_SESSION['usererror'] = "🚫 Too many attempts! Please try again after $wait minute(s).";
        header("Location: logIn.php");
        exit();
    } else {
        $_SESSION['login_attempts'] = 0;
        $_SESSION['lock_time'] = 0;
    }
}

$flag = 0;

if (isset($_POST['login'])) {
    $username = htmlspecialchars($_POST['username']);
    $password = htmlspecialchars($_POST['password']);
}

if (empty($username)) {
    $_SESSION['usererror'] = "Please Enter The username";
    $flag = 1;
}
if (empty($password)) {
    $_SESSION['passerror'] = "Please Enter The password";
    $flag = 1;
}
if ($flag == 1) {
    header("Location: logIn.php");
    exit();
}

if ($flag == 0) {
    $sql = "SELECT * FROM users WHERE username = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "s", $username);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);

        if (password_verify($password, $row['password'])) {
            //  نجاح تسجيل الدخول
            $_SESSION['id'] = $row['id'];
            $_SESSION['username'] = $row['username'];
            $_SESSION['role'] = $row['role'];
            $_SESSION['profile_picture'] = $row['profile_picture'];

            // إعادة ضبط المحاولات
            $_SESSION['login_attempts'] = 0;
            $_SESSION['lock_time'] = 0;

            if ($_SESSION['role'] == 'project_manager') {
                header('Location:../manager/dashboard.php');
                exit();
            } elseif ($_SESSION['role'] == 'team_member') {
                header('Location:../team_member/dashboard.php');
                exit();
            }
        } else {
            //  كلمة مرور خاطئة
            $_SESSION['usererror'] = "Wrong password or username";
            $_SESSION['login_attempts'] += 1;
            if ($_SESSION['login_attempts'] >= $maxAttempts) {
                $_SESSION['lock_time'] = time();
            }
            header("Location: logIn.php");
            exit();
        }
    } else {
        //  اسم مستخدم غير موجود
        $_SESSION['usererror'] = "Wrong password or username";
        $_SESSION['login_attempts'] += 1;
        if ($_SESSION['login_attempts'] >= $maxAttempts) {
            $_SESSION['lock_time'] = time();
        }
        header("Location: logIn.php");
        exit();
    }
}
