<?php
include('../../confing/DB_connection.php');
session_start();
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $list_id = isset($_GET['list_id']) ? intval($_GET['list_id']) : 0;

    if ($list_id <= 0) {
        http_response_code(400);
        echo json_encode(['status' => 'error', 'message' => 'Invalid list ID']);
        exit;
    }

    // جلب معلومات المشروع المرتبط بالقائمة للتحقق من الـ visibility
    $stmt = $conn->prepare("SELECT p.visibility, p.members FROM projects p JOIN lists l ON p.id = l.project_id WHERE l.id = ?");
    $stmt->bind_param("i", $list_id);
    $stmt->execute();
    $project_result = $stmt->get_result();
    $project = $project_result->fetch_assoc();

    if (!$project) {
        http_response_code(404);
        echo json_encode(['status' => 'error', 'message' => 'Project not found']);
        exit;
    }

    $visibility = $project['visibility'];
    $members = explode(",", $project['members']);

    // التحقق من صلاحية المستخدم
    $current_user = $_SESSION['username'];
    $is_member = in_array($current_user, $members);

    // إذا كان المشروع خاصًا والمستخدم ليس عضوًا، لا تعرض أي كروت
    if ($visibility === 'Private' && !$is_member) {
        echo json_encode(['status' => 'success', 'cards' => []]);
        exit;
    }

    // جلب الكروت
    $query = "SELECT c.id, c.title FROM cards c WHERE c.list_id = ?";

    // إذا كان المشروع خاصًا، عرض فقط الكروت المخصصة للمستخدم الحالي
    if ($visibility === 'Private' && $is_member) {
        $query .= " AND EXISTS (SELECT 1 FROM card_members cm WHERE cm.card_id = c.id AND cm.username = ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("is", $list_id, $current_user);
    } else {
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $list_id);
    }

    $stmt->execute();
    $result = $stmt->get_result();

    $cards = [];
    while ($row = $result->fetch_assoc()) {
        $cards[] = $row;
    }

    echo json_encode(['status' => 'success', 'cards' => $cards]);

    $stmt->close();
    $conn->close();
}
