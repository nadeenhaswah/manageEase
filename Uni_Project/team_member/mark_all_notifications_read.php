<?php
include('../confing/DB_connection.php');
include 'functions/notifications_functions.php';

if (isset($_POST['user_id'])) {
    $sql = "UPDATE notifications SET is_read = 1 WHERE user_id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $_POST['user_id']);
    mysqli_stmt_execute($stmt);
}
