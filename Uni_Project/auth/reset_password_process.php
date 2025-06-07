<?php
include('../confing/DB_connection.php');
session_start();
$flag = 0;


if (isset($_POST['reset'])) {
    $email = trim(htmlspecialchars($_POST['email']));
    $password = trim(htmlspecialchars($_POST['password']));
    $confirm_password = trim(htmlspecialchars($_POST['confirm_password']));


    // التحقق من صحة البريد الإلكتروني.
    if (empty($email)) {
        $_SESSION['email_err'] = "Please Enter An Email <br>";
        $flag = 1;
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        // التأكد من صحة تنسيق البريد الإلكتروني.
        $_SESSION['email_err'] = "Please Enter A valid Email <br>";
        $flag = 1;
    }
    $check_email = "SELECT * FROM users WHERE  email=?";
    $stmt = mysqli_prepare($conn, $check_email);
    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    if (mysqli_num_rows($result) == 0) {
        $_SESSION['email_err'] = "Email not found <br>";
        $flag = 1;
    }


    // التحقق من كلمة المرور.
    if (empty($password)) {
        $_SESSION['password_err'] = "Please Enter Password <br>";
        $flag = 1;
    } elseif (strlen($password) < 8) {
        // التأكد من أن كلمة المرور تحتوي على 8 أحرف على الأقل.
        $_SESSION['password_err'] = "Your password needs to have a minimum of 8 characters <br>";
        $flag = 1;
    } elseif (!preg_match('/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[\W_]).{8,}$/', $password)) {
        // التأكد من أن كلمة المرور تحتوي على أحرف كبيرة وصغيرة وأرقام ورموز.
        $_SESSION['password_err'] = "Password must have at least 1 uppercase letter, 1 lowercase letter, 1 number, and 1 special character <br>";
        $flag = 1;
    }

    // التحقق من تطابق كلمة المرور مع تأكيد كلمة المرور.
    if (empty($confirm_password)) {
        $_SESSION['confirm_password_err'] = "Please Rewrite Password <br>";
        $flag = 1;
    } elseif ($confirm_password !== $password) {
        // التأكد من أن كلمة المرور وكلمة المرور المعاد كتابتها متطابقتين.
        $_SESSION['confirm_password_err'] = "Passwords Do Not Match <br>";
        $flag = 1;
    }

    if ($flag == 1) {
        // متغيرات يتم عرضهم داخل الحقول اذا كان هناك خطأ , بحيث تبقى القيم داخل الحقول عند تحديث النموذج 
        //  الاحتفاظ بقيمة الحقل بعد حدوث خطأ أثناء عملية التسجيل
        $_SESSION['old_email'] = $_POST['email'];
        $_SESSION['old_password'] = $_POST['password'];
        $_SESSION['old_confirm_password'] = $_POST['confirm_password'];
    }

    if ($flag == 0) {

        // تشفير كلمة المرور باستخدام password_hash لتخزين كلمة المرور بشكل آمن.
        $hashed_password = password_hash($password, PASSWORD_DEFAULT); // تشفير كلمة المرور قبل تخزينها في قاعدة البيانات.
        $sql = "UPDATE users SET password =? WHERE email = ?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "ss", $hashed_password, $email);

        if (mysqli_stmt_execute($stmt)) {
            $_SESSION['messege'] = "Password Changed successfully";
            header("Location:forget_password.php");
            exit();
        }
    } else {
        header("Location:forget_password.php");
    }
}
