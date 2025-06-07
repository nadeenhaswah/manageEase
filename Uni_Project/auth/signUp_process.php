<?php
include('../confing/DB_connection.php');
session_start();
session_regenerate_id(true);
$flag = 0;
if (isset($_POST['signup'])) {
    $username = htmlspecialchars($_POST['username']);
    $email = htmlspecialchars($_POST['email']);
    $job = htmlspecialchars($_POST['job']);
    $phone = htmlspecialchars($_POST['phone']);
    $password = htmlspecialchars($_POST['password']);
    $confirm_password = htmlspecialchars($_POST['confirm_password']);
    $default_profile_pic = '../asset/images/profilePic.png';

    if (empty($username)) {
        $user_err = "Please Enter The username <br>";
        $flag = 1;
    } elseif (strlen($username) < 6) {
        $user_err = " Your username needs to have a minimum of 6 letters <br>";
        $flag = 1;
    } elseif (filter_var($username, FILTER_VALIDATE_INT)) {
        $user_err = "Please Enter A valid username not a number <br>";
        $flag = 1;
    }

    // تحقق مما إذا كان اسم المستخدم موجودًا بالفعل في قاعدة البيانات.
    $check_user = "SELECT * FROM users WHERE username=?";
    $stmt = mysqli_prepare($conn, $check_user);
    mysqli_stmt_bind_param($stmt, "s", $username);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    if (mysqli_num_rows($result) > 0) {
        // في حالة وجود اسم المستخدم في قاعدة البيانات بالفعل.
        $_SESSION['usererror'] = "Sorry, username already exists <br>";
        $flag = 1;
    }

    // التحقق من اسم المستخدم.
    if (empty($username)) {
        $_SESSION['usererror'] = "Please Enter The username <br>";
        $flag = 1;
    } elseif (strlen($username) < 6) {
        // تأكد أن اسم المستخدم يحتوي على 6 أحرف على الأقل.
        $_SESSION['usererror'] = "Your username needs to have a minimum of 6 letters <br>";
        $flag = 1;
    } elseif (filter_var($username, FILTER_VALIDATE_INT)) {
        // التأكد من أن اسم المستخدم ليس رقميًا فقط.
        $_SESSION['usererror'] = "Please Enter A valid username, not a number <br>";
        $flag = 1;
    }

    // تحقق مما إذا كان اسم المستخدم موجودًا بالفعل في قاعدة البيانات.
    $check_user = "SELECT * FROM users WHERE username=?";
    $stmt = mysqli_prepare($conn, $check_user);
    mysqli_stmt_bind_param($stmt, "s", $username);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    if (mysqli_num_rows($result) > 0) {
        // في حالة وجود اسم المستخدم في قاعدة البيانات بالفعل.
        $_SESSION['usererror'] = "Sorry, username already exists <br>";
        $flag = 1;
    }

    // التحقق من صحة البريد الإلكتروني.
    if (empty($email)) {
        $_SESSION['email_err'] = "Please Enter An Email <br>";
        $flag = 1;
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        // التأكد من صحة تنسيق البريد الإلكتروني.
        $_SESSION['email_err'] = "Please Enter A valid Email <br>";
        $flag = 1;
    }
    $check_email = "SELECT * FROM users WHERE email=?";
    $stmt = mysqli_prepare($conn, $check_email);
    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    if (mysqli_num_rows($result) > 0) {
        // في حالة وجود اسم المستخدم في قاعدة البيانات بالفعل.
        $_SESSION['email_err'] = "Sorry, email already exists <br>";
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
}
if ($flag == 1) {
    // متغيرات يتم عرضهم داخل الحقول اذا كان هناك خطأ , بحيث تبقى القيم داخل الحقول عند تحديث النموذج 
    //  الاحتفاظ بقيمة الحقل بعد حدوث خطأ أثناء عملية التسجيل
    $_SESSION['old_username'] = $_POST['username'];
    $_SESSION['old_email'] = $_POST['email'];
    $_SESSION['old_job'] = $_POST['job'];
    $_SESSION['old_phone'] = $_POST['phone'];
    $_SESSION['old_password'] = $_POST['password'];
    $_SESSION['old_confirm_password'] = $_POST['confirm_password'];
}
if ($flag == 0) {

    // تشفير كلمة المرور باستخدام password_hash لتخزين كلمة المرور بشكل آمن.
    $hashed_password = password_hash($password, PASSWORD_DEFAULT); // تشفير كلمة المرور قبل تخزينها في قاعدة البيانات.


    $role = 'project_manager';

    // إدخال البيانات في قاعدة البيانات.
    $sql = "INSERT INTO users (username, email,job,phone, password,profile_picture, role) VALUES (?,?,?,?, ?, ?, ?)";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "sssisss", $username, $email, $job, $phone, $hashed_password, $default_profile_pic, $role);

    if (mysqli_stmt_execute($stmt)) {
        $_SESSION['messege'] = "Account Created Successfully";
        header("location: logIn.php");
        exit();
    }


    // $sql = "INSERT INTO users (username ,email,	password,profile_picture,role)
    // VALUES ('$username','$email','$password',' $default_profile_pic','$role') ";
    // mysqli_query($conn, $sql);
    // $_SESSION['messege'] = "Account Created Successfully";
    // header("location:logIn.php");
} else {
    header("location:signUp.php");
    // include('signup.php');
}
