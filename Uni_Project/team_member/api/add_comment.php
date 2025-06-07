<?php
session_start();
header('Content-Type: application/json');

// تحقق من تسجيل الدخول
if (!isset($_SESSION['id'])) {
    echo json_encode(['success' => false, 'error' => 'Unauthorized']);
    exit;
}

include('../../confing/DB_connection.php');

// تحقق من اتصال قاعدة البيانات
if (!$conn) {
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => 'Database connection failed']);
    exit;
}

// استقبال البيانات من JSON
$data = json_decode(file_get_contents("php://input"), true);

// استخراج البيانات
// $card_id = intval($data['card_id'] ?? 0);
$card_id = isset($data['card_id']) ? intval($data['card_id']) : 0;
$user_id = $_SESSION['id'];
$content = isset($data['content']) ? trim($data['content']) : '';

// $content = trim($data['content'] ?? '');
$parent_id = isset($data['parent_id']) && $data['parent_id'] !== '' ? intval($data['parent_id']) : null;

// التحقق من الحقول المطلوبة
if (!$card_id || !$user_id || $content === '') {
    echo json_encode(['success' => false, 'error' => 'Missing required fields']);
    exit;
}

// إضافة التعليق في قاعدة البيانات
$stmt = $conn->prepare("INSERT INTO card_comments (card_id, user_id, content, parent_id) VALUES (?, ?, ?, ?)");
$stmt->bind_param("iisi", $card_id, $user_id, $content, $parent_id);
$success = $stmt->execute();

if ($success) {
    $comment_id = $stmt->insert_id;

    // جلب معلومات المستخدم
    $user_stmt = $conn->prepare("SELECT username, profile_picture FROM users WHERE id = ?");
    $user_stmt->bind_param("i", $user_id);
    $user_stmt->execute();
    $user_result = $user_stmt->get_result()->fetch_assoc();

    echo json_encode([
        'success' => true,
        'comment' => [
            'id' => $comment_id,
            'card_id' => $card_id,
            'user_id' => $user_id,
            'user_name' => $user_result['full_name'],
            'user_avatar' => $user_result['profile_picture'],
            'content' => $content,
            'created_at' => date("Y-m-d H:i:s"),
            'parent_id' => $parent_id
        ]
    ]);
} else {
    echo json_encode(['success' => false, 'error' => 'Failed to add comment']);
}
