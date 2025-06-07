<?php
include('../confing/DB_connection.php');
session_start();
$flag = 0;

// edit or update user info 
if (isset($_POST['update'])) {


    $member_id = htmlspecialchars($_POST['id']);
    $username = htmlspecialchars($_POST['username']);
    $email = htmlspecialchars($_POST['email']);
    $job = htmlspecialchars($_POST['job']);
    $phone = htmlspecialchars($_POST['phone']);

    $default_picture = "../asset/images/profilePic.png";
    // $default_picture = "/my_project/asset/images/profilePic.png";

    $new_profile_picture_path = null;

    $query = "SELECT profile_picture FROM users WHERE id = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "i", $member_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $user = mysqli_fetch_assoc($result);

    $profile_picture = $user['profile_picture'];

    // التحقق إذا كان المستخدم طلب حذف الصورة
    if (isset($_POST['remove_picture']) && $_POST['remove_picture'] == "1") {
        $profile_picture = $default_picture;
    }
    // التحقق إذا رفع صورة جديدة
    if (isset($_FILES['new_profile_picture']) && $_FILES['new_profile_picture']['error'] === 0) {
        $file_tmp = $_FILES['new_profile_picture']['tmp_name'];
        $file_name = time() . "_" . $_FILES['new_profile_picture']['name'];
        // $destination = "../uploads/" . $file_name;

        // تأكد أن مجلد الرفع موجود
        $destination = "../uploads/" . $file_name;
        $full_path = "../uploads/" . $file_name;


        if (move_uploaded_file($file_tmp, $full_path)) {
            $profile_picture = $destination;
        }
    }

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



    if ($flag == 0) {
        $sql = "UPDATE users SET username = ?, email = ?, job = ?, phone = ?, profile_picture = ? WHERE id = ?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "sssssi", $username, $email, $job, $phone, $profile_picture, $member_id);

        if (mysqli_stmt_execute($stmt)) {
            $_SESSION['messege'] = "Edited successfully";
            header("Location:update_info.php");
            exit();
        }
    } else {
        header("Location:update_info.php");
    }
}
