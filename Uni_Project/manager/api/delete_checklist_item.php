<?php
include('../../confing/DB_connection.php');
session_start();
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = isset($_POST['id']) ? intval($_POST['id']) : 0;

    if ($id <= 0) {
        echo json_encode(['status' => 'error', 'message' => 'Invalid ID']);
        exit;
    }
    // الحصول على checklist_id قبل الحذف
    $getChecklist = $conn->prepare("SELECT checklist_id FROM card_checklist_items WHERE id = ?");
    $getChecklist->bind_param("i", $id);
    $getChecklist->execute();
    $getChecklist->bind_result($checklist_id);
    $getChecklist->fetch();
    $getChecklist->close();

    $stmt = $conn->prepare("DELETE FROM card_checklist_items WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    include_once('../functions/update_progress.php');
    updateChecklistProgress($checklist_id, $conn);

    $stmt->close();
    $conn->close();

    echo json_encode(['status' => 'success']);
}
