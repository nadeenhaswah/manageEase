<?php
include('../../confing/DB_connection.php');
session_start();
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // $id = intval($_POST['id'] ?? 0);
    $id = isset($_POST['id']) ? intval($_POST['id']) : 0;
    $content = isset($_POST['content']) ? $_POST['content'] : " ";
    $is_checked = isset($_POST['is_checked']) ? intval($_POST['is_checked']) : 0;

    if ($id <= 0 || $content === '') {
        echo json_encode(['status' => 'error', 'message' => 'Invalid input']);
        exit;
    }

    $stmt = $conn->prepare("UPDATE card_checklist_items SET content = ?, is_checked = ? WHERE id = ?");
    $stmt->bind_param("sii", $content, $is_checked, $id);
    $stmt->execute();
    include_once('../functions/update_progress.php');

    // للحصول على checklist_id الحالي من قاعدة البيانات:
    $getChecklist = $conn->prepare("SELECT checklist_id FROM card_checklist_items WHERE id = ?");
    $getChecklist->bind_param("i", $id);
    $getChecklist->execute();
    $getChecklist->bind_result($checklist_id);
    $getChecklist->fetch();
    $getChecklist->close();

    updateChecklistProgress($checklist_id, $conn);

    $stmt->close();
    $conn->close();

    echo json_encode(['status' => 'success']);
}
