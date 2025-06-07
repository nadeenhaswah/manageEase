<?php
include('../../confing/DB_connection.php');
header('Content-Type: application/json');
session_start();
// زيادة الحدود المسموح بها للملفات
ini_set('upload_max_filesize', '64M');
ini_set('post_max_size', '64M');
ini_set('max_execution_time', 300);
ini_set('max_input_time', 300);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // التحقق من وجود الملف وعدم وجود أخطاء في الرفع
    if (!isset($_FILES['file']) || $_FILES['file']['error'] !== UPLOAD_ERR_OK) {
        echo json_encode(['status' => 'error', 'message' => 'File upload error: ' . $_FILES['file']['error']]);
        exit;
    }

    $card_id = isset($_POST['card_id']) ? intval($_POST['card_id']) : 0;
    $title = isset($_POST['title']) ? $_POST['title'] : basename($_FILES['file']['name']);
    $type = 'file'; // نستخدم نوع واحد لجميع الملفات

    if ($card_id <= 0) {
        echo json_encode(['status' => 'error', 'message' => 'Invalid card ID']);
        exit;
    }

    $uploadDir = '../../uploads/';
    if (!is_dir($uploadDir)) {
        if (!mkdir($uploadDir, 0777, true)) {
            echo json_encode(['status' => 'error', 'message' => 'Failed to create upload directory']);
            exit;
        }
    }

    // إنشاء اسم فريد للملف
    $filename = uniqid() . '_' . basename($_FILES['file']['name']);
    $targetPath = $uploadDir . $filename;
    $dbPath = '../uploads/' . $filename; // المسار الذي سيتم تخزينه في قاعدة البيانات

    // محاولة نقل الملف
    if (move_uploaded_file($_FILES['file']['tmp_name'], $targetPath)) {
        try {
            $stmt = $conn->prepare("INSERT INTO card_attachments (card_id, title, type, path) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("isss", $card_id, $title, $type, $dbPath);

            if ($stmt->execute()) {
                $attachment_id = $stmt->insert_id;
                $stmt->close();
                echo json_encode([
                    'status' => 'success',
                    'path' => $dbPath,
                    'id' => $attachment_id
                ]);
            } else {
                throw new Exception("Database insert failed");
            }
        } catch (Exception $e) {
            // في حالة فشل الإدراج في قاعدة البيانات، نحذف الملف الذي تم رفعه
            if (file_exists($targetPath)) {
                unlink($targetPath);
            }
            echo json_encode(['status' => 'error', 'message' => 'Database error: ' . $e->getMessage()]);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to move uploaded file. Check directory permissions.']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
}
