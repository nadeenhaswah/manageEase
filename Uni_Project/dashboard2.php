<?php
include('confing/DB_connection.php');
session_start(); ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <?php
    $profile_picture = isset($_SESSION['profile_picture']) ? $_SESSION['profile_picture'] : 'assets/images/profilePic.png'; ?>
    <?php
    echo "hello";
    echo  $_SESSION['id'];
    echo  $_SESSION['username'];
    echo  $_SESSION['role'];
    ?>
    <img src="<?php echo  $profile_picture; ?>" alt="Profile Picture" width="150" height="150">
</body>

</html>