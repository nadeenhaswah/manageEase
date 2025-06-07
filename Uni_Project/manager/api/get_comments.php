<?php
session_start();
header('Content-Type: application/json');

// تحقق من تسجيل الدخول
if (!isset($_SESSION['id'])) {
    http_response_code(401);
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

// استقبال card_id من параметры GET
$card_id = isset($_GET['card_id']) ? intval($_GET['card_id']) : 0;

if ($card_id <= 0) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'Invalid card ID']);
    exit;
}

try {
    // جلب جميع التعليقات الخاصة بالبطاقة مع معلومات المستخدمين
    $query = "SELECT cc.*, u.username as user_name, u.profile_picture as user_avatar 
              FROM card_comments cc
              JOIN users u ON cc.user_id = u.id
              WHERE cc.card_id = ?
              ORDER BY cc.created_at DESC";

    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $card_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $comments = [];
    while ($row = $result->fetch_assoc()) {
        $comments[] = [
            'id' => $row['id'],
            'user_id' => $row['user_id'],
            'user_name' => $row['user_name'],
            'user_avatar' => $row['user_avatar'] ?: 'default.jpg',
            'content' => $row['content'],
            'created_at' => $row['created_at'],
            'parent_id' => $row['parent_id']
        ];
    }

    echo json_encode([
        'success' => true,
        'comments' => $comments
    ]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
} finally {
    if (isset($stmt)) $stmt->close();
    if (isset($conn)) $conn->close();
}
