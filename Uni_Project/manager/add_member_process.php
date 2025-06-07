<?php
include('../confing/DB_connection.php');
session_start();
$flag = 0;
//delete member
if (isset($_POST['delete_member'])) {
    $member_id = mysqli_real_escape_string($conn, $_POST['delete_member']);

    $query = "DELETE FROM users WHERE id = ?";
    $stmt = mysqli_prepare($conn, $query);

    mysqli_stmt_bind_param($stmt, "i", $member_id);

    if (mysqli_stmt_execute($stmt)) {
        $_SESSION['messege'] = "Account Deleted Successfully";
        header("Location: list_members.php");
        exit();
    }
}

// edit or update member info 
if (isset($_POST['edit'])) {
    $member_id = htmlspecialchars($_POST['id']);
    $username = htmlspecialchars($_POST['username']);
    $email = htmlspecialchars($_POST['email']);
    $job = htmlspecialchars($_POST['job']);
    $phone = htmlspecialchars($_POST['phone']);
    $role = htmlspecialchars($_POST['role']);

    // $permissions = implode(",", array_map("htmlspecialchars", $_POST['Permissions']));
    $permissions = isset($_POST['Permissions']) ? implode(",", array_map("htmlspecialchars", $_POST['Permissions'])) : null;



    if (empty($username)) {
        $_SESSION['usererror']  = "Please Enter The username <br>";
        $flag = 1;
    } elseif (strlen($username) < 6) {
        $_SESSION['usererror'] = " Must not be less than 6 characters<br>";
        $flag = 1;
    } elseif (filter_var($username, FILTER_VALIDATE_INT)) {
        $_SESSION['usererror']  = "Please Enter A valid username not a number <br>";
        $flag = 1;
    }



    if (empty($email)) {
        $_SESSION['email_err'] = "Please Enter An Email <br>";
        $flag = 1;
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['email_err'] = "Please Enter A valid Email <br>";
        $flag = 1;
    }







    // الخيار الافتراضي 
    if ($role == 'Select Role') {
        $_SESSION['role_err'] = "Please Select the Role<br>";
        $flag = 1;
    }

    if ($flag == 0) {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $sql = "UPDATE users SET username = ?, email = ?,job = ?,phone = ?, role = ? , permissions = ? WHERE id = ?";
        $stmt = mysqli_prepare($conn, $sql);

        // ( s = string, i = integer)
        mysqli_stmt_bind_param($stmt, "sssissi", $username, $email, $job, $phone,  $role, $permissions, $member_id);

        $query_run = mysqli_stmt_execute($stmt);

        if ($query_run) {
            $_SESSION['messege'] = "Edited successfully";
            header("Location: list_members.php");
            exit();
        }
    } else {
        header("Location:edit_member.php?id=$member_id");
    }
}


// add member 
if (isset($_POST['add_member'])) {
    $username = htmlspecialchars($_POST['username']);
    $email = htmlspecialchars($_POST['email']);
    $job = htmlspecialchars($_POST['job']);
    $phone = htmlspecialchars($_POST['phone']);
    $password = htmlspecialchars($_POST['password']);
    $confirm_password = htmlspecialchars($_POST['confirm_password']);
    $default_profile_pic = '../asset/images/profilePic.png';
    $role = htmlspecialchars($_POST['role']);
    // $permissions = implode(",", array_map("htmlspecialchars", $_POST['Permissions']));
    $permissions = isset($_POST['Permissions']) ? implode(",", array_map("htmlspecialchars", $_POST['Permissions'])) : null;

    if (empty($username)) {
        $_SESSION['usererror']  = "Please Enter The username <br>";
        $flag = 1;
    } elseif (strlen($username) < 6) {
        $_SESSION['usererror'] = " Must not be less than 6 characters<br>";
        $flag = 1;
    } elseif (filter_var($username, FILTER_VALIDATE_INT)) {
        $_SESSION['usererror']  = "Please Enter A valid username not a number <br>";
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



    if (empty($email)) {
        $_SESSION['email_err'] = "Please Enter An Email <br>";
        $flag = 1;
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['email_err'] = "Please Enter A valid Email <br>";
        $flag = 1;
    }
    $check_email = "SELECT * FROM users WHERE email=?";
    $stmt = mysqli_prepare($conn, $check_email);
    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    if (mysqli_num_rows($result) > 0) {
        // في حالة وجود نفس الايميل في قاعدة البيانات بالفعل.
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

    if (empty($confirm_password)) {
        $_SESSION['confirm_password_err'] = "Please Rewrite Password <br>";
        $flag = 1;
    } elseif ($confirm_password !== $password) {
        $_SESSION['confirm_password_err']  = "Password Not Match <br>";
        $flag = 1;
    }

    // الخيار الافتراضي 
    if ($role == 'Select Role') {
        $_SESSION['role_err'] = "Please Select the Role<br>";
        $flag = 1;
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
        $added_by = $_SESSION['id'];
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        // use prepared statement to prevent SQL injection 
        $sql = "INSERT INTO users (username, email,job,phone, password,profile_picture, role,permissions ,added_by ) VALUES (?,?, ?, ?, ?,?,?,?,?)";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "sssissssi", $username, $email, $job, $phone, $hashed_password, $default_profile_pic, $role, $permissions, $added_by);

        if (mysqli_stmt_execute($stmt)) {
            $_SESSION['messege'] = "Account Created Successfully";
            header("Location:add_memberr.php");
            exit();
        }
    } else {
        header("Location:add_memberr.php");
    }
}
