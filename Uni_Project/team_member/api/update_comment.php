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

// استقبال البيانات من JSON
$data = json_decode(file_get_contents("php://input"), true);

// استخراج البيانات
$comment_id = isset($data['comment_id']) ? intval($data['comment_id']) : 0;
$content = isset($data['content']) ? trim($data['content']) : '';
$user_id = $_SESSION['id'];

// التحقق من الحقول المطلوبة
if (!$comment_id || $content === '') {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'Missing required fields']);
    exit;
}

try {
    // التحقق من ملكية التعليق أولاً
    $check_stmt = $conn->prepare("SELECT user_id FROM card_comments WHERE id = ?");
    $check_stmt->bind_param("i", $comment_id);
    $check_stmt->execute();
    $check_result = $check_stmt->get_result()->fetch_assoc();

    if (!$check_result) {
        http_response_code(404);
        echo json_encode(['success' => false, 'error' => 'Comment not found']);
        exit;
    }

    if ($check_result['user_id'] != $user_id) {
        http_response_code(403);
        echo json_encode(['success' => false, 'error' => 'You are not authorized to edit this comment']);
        exit;
    }

    // تحديث التعليق
    $update_stmt = $conn->prepare("UPDATE card_comments SET content = ? WHERE id = ?");
    $update_stmt->bind_param("si", $content, $comment_id);
    $success = $update_stmt->execute();

    if ($success) {
        // جلب بيانات التعليق المحدثة
        $stmt = $conn->prepare("SELECT cc.*, u.username as user_name, u.profile_picture as user_avatar 
                              FROM card_comments cc
                              JOIN users u ON cc.user_id = u.id
                              WHERE cc.id = ?");
        $stmt->bind_param("i", $comment_id);
        $stmt->execute();
        $comment = $stmt->get_result()->fetch_assoc();

        echo json_encode([
            'success' => true,
            'comment' => [
                'id' => $comment['id'],
                'user_name' => $comment['user_name'],
                'user_avatar' => $comment['user_avatar'],
                'content' => $comment['content'],
                'created_at' => $comment['created_at'],
                'author_id' => $comment['user_id']
            ]
        ]);
    } else {
        throw new Exception("Failed to update comment");
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
