<?php
include('../confing/DB_connection.php');
require_once 'functions/notifications.php';

header('Content-Type: application/json');

if (isset($_GET['user_id'])) {
    $count = getUnreadNotificationsCount($_GET['user_id']);
    echo json_encode(['count' => $count]);
}
