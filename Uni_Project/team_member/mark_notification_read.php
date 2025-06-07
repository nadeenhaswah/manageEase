<?php
include('../confing/DB_connection.php');
include 'functions/notifications_functions.php';

if (isset($_POST['notification_id'])) {
    markNotificationAsRead($conn, $_POST['notification_id']);
}
