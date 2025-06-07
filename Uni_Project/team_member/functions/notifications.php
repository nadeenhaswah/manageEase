<?php
include('../../confing/DB_connection.php');

function createNotification($user_id, $triggered_by, $project_id, $task_id, $type, $message, $related_url = null)
{
    global $conn;

    $sql = "INSERT INTO notifications (user_id, triggered_by, project_id, task_id, type, message, related_url) 
            VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iiiisss", $user_id, $triggered_by, $project_id, $task_id, $type, $message, $related_url);
    $stmt->execute();

    return $stmt->insert_id;
}


function getUnreadNotificationsCount($user_id)
{
    global $conn;

    $sql = "SELECT COUNT(*) as count FROM notifications WHERE user_id = ? AND is_read = 0";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    return $result->fetch_assoc()['count'];
}

function getNotifications($user_id, $limit = 10)
{
    global $conn;

    $sql = "SELECT n.*, u.username as triggered_by_name 
            FROM notifications n
            LEFT JOIN users u ON n.triggered_by = u.id
            WHERE n.user_id = ?
            ORDER BY n.created_at DESC
            LIMIT ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $user_id, $limit);
    $stmt->execute();
    $result = $stmt->get_result();

    return $result->fetch_all(MYSQLI_ASSOC);
}

function markAsRead($notification_id)
{
    global $conn;

    $sql = "UPDATE notifications SET is_read = 1 WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $notification_id);
    $stmt->execute();

    return $stmt->affected_rows;
}
