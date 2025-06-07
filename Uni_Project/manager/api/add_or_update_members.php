<?php
include('../../confing/DB_connection.php');
include('../notification_functions.php');

session_start();
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $card_id = isset($_POST['card_id']) ? intval($_POST['card_id']) : 0;
    $usernames = isset($_POST['usernames']) ? $_POST['usernames'] : [];
    $triggered_by = $_SESSION['id'];


    if ($card_id <= 0 || !is_array($usernames)) {
        echo json_encode(['status' => 'error', 'message' => 'Invalid input']);
        exit;
    }
    // حذف الحاليين
    $stmt = $conn->prepare("DELETE FROM card_members WHERE card_id = ?");
    $stmt->bind_param("i", $card_id);
    $stmt->execute();
    $stmt->close();

    // إضافة الجدد
    $stmt = $conn->prepare("INSERT INTO card_members (card_id, username) VALUES (?, ?)");
    foreach ($usernames as $username) {
        $stmt->bind_param("is", $card_id, $username);
        $stmt->execute();

        notifyTaskAssigned($card_id, $username, $triggered_by);
    }

    $stmt->close();
    $conn->close();

    echo json_encode(['status' => 'success']);
}
