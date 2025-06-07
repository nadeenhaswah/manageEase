<?php
include('../../confing/DB_connection.php');
session_start();
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $checklist_id = isset($_POST['checklist_id']) ? intval($_POST['checklist_id']) : 0;
    // $checklist_id = intval($_POST['checklist_id'] ?? 0);
    // $content = trim($_POST['content'] ?? '');
    $content = isset($_POST['content']) ? $_POST['content'] : " ";

    if ($checklist_id <= 0 || $content === '') {
        echo json_encode(['status' => 'error', 'message' => 'Invalid input']);
        exit;
    }

    $stmt = $conn->prepare("INSERT INTO card_checklist_items (checklist_id, content) VALUES (?, ?)");
    $stmt->bind_param("is", $checklist_id, $content);
    $stmt->execute();
    $inserted_id = $stmt->insert_id;
    include_once('../functions/update_progress.php');
    updateChecklistProgress($checklist_id, $conn);

    $stmt->close();
    $conn->close();

    echo json_encode(['status' => 'success', 'item_id' => $inserted_id]);
}
