<?php
// add_or_update_status.php
include('../../confing/DB_connection.php');
include('../notification_functions.php');

session_start();
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $card_id = intval($_POST['card_id']);
    $status = $_POST['status'];
    $triggered_by = $_SESSION['id']; // ID المستخدم الذي يقوم بالتعديل


    $valid = ['To Do', 'In Progress', 'Review', 'Done'];
    if (!in_array($status, $valid) || $card_id <= 0) {
        echo json_encode(['status' => 'error', 'message' => 'Invalid input']);
        exit;
    }

    // تحقق من وجود السجل
    $check = $conn->prepare("SELECT id FROM card_details WHERE card_id = ?");
    $check->bind_param("i", $card_id);
    $check->execute();
    $res = $check->get_result();

    if ($res->num_rows > 0) {
        $stmt = $conn->prepare("UPDATE card_details SET status = ? WHERE card_id = ?");
        $stmt->bind_param("si", $status, $card_id);
    } else {
        $stmt = $conn->prepare("INSERT INTO card_details (card_id, status) VALUES (?, ?)");
        $stmt->bind_param("is", $card_id, $status);
    }

    if ($stmt->execute()) {
        // إرسال الإشعار أولاً
        $notificationResult = notifyTaskStatusChanged($card_id, $status, $triggered_by);

        // ثم إرسال الرد
        echo json_encode([
            'status' => 'success',
            'notification_sent' => $notificationResult
        ]);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to save']);
    }

    $stmt->close();
    $conn->close();
}
