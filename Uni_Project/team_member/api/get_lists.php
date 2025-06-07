<?php
include('../../confing/DB_connection.php');
session_start();
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $project_id = isset($_GET['project_id']) ? intval($_GET['project_id']) : 0;

    if ($project_id <= 0) {
        http_response_code(400);
        echo json_encode(['status' => 'error', 'message' => 'Invalid project ID']);
        exit;
    }

    // جلب معلومات المشروع للتحقق من الـ visibility
    $stmt = $conn->prepare("SELECT visibility, members FROM projects WHERE id = ?");
    $stmt->bind_param("i", $project_id);
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

    // إذا كان المشروع خاصًا والمستخدم ليس عضوًا، لا تعرض أي قوائم
    if ($visibility === 'Private' && !$is_member) {
        echo json_encode(['status' => 'success', 'lists' => []]);
        exit;
    }

    // جلب القوائم مع تصفية القوائم الفارغة (التي لا تحتوي على كروت للمستخدم في المشاريع الخاصة)
    if ($visibility === 'Private' && $is_member) {
        // جلب القوائم التي تحتوي على كروت للمستخدم الحالي فقط
        $query = "SELECT DISTINCT l.id, l.name 
                  FROM lists l 
                  JOIN cards c ON l.id = c.list_id 
                  JOIN card_members cm ON c.id = cm.card_id 
                  WHERE l.project_id = ? AND cm.username = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("is", $project_id, $current_user);
    } else {
        // جلب جميع القوائم للمشاريع العامة أو للمشاريع الخاصة إذا كان المستخدم مديرًا
        $query = "SELECT id, name FROM lists WHERE project_id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $project_id);
    }

    $stmt->execute();
    $result = $stmt->get_result();

    $lists = [];
    while ($row = $result->fetch_assoc()) {
        $lists[] = $row;
    }

    echo json_encode(['status' => 'success', 'lists' => $lists]);

    $stmt->close();
    $conn->close();
}
