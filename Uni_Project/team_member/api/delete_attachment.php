<?php
// delete_attachment.php
include('../../confing/DB_connection.php');
session_start();
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = isset($_POST['id']) ? intval($_POST['id']) : 0;
    if ($id <= 0) {
        echo json_encode(['status' => 'error', 'message' => 'Invalid ID']);
        exit;
    }

    // جلب المسار قبل الحذف
    $stmt = $conn->prepare("SELECT path, type FROM card_attachments WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->bind_result($path, $type);
    $stmt->fetch();
    $stmt->close();

    // حذف من السيرفر إذا لم يكن رابط خارجي
    if ($type !== 'url' && file_exists("../../" . $path)) {
        unlink("../../" . $path);
    }

    $stmt = $conn->prepare("DELETE FROM card_attachments WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();

    echo json_encode(['status' => 'success']);
}
