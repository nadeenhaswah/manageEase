<?php
// notification_functions.php
include('../confing/DB_connection.php');

function addNotification($user_id, $triggered_by, $project_id, $task_id, $type, $message, $related_url = null)
{
    global $conn;

    $sql = "INSERT INTO notifications (user_id, triggered_by, project_id, task_id, type, message, related_url) 
            VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iiiisss", $user_id, $triggered_by, $project_id, $task_id, $type, $message, $related_url);
    return $stmt->execute();
}

// إشعار عند إضافة عضو لمشروع
function notifyMemberAdded($member_username, $project_id, $triggered_by)
{
    global $conn;

    // جلب اسم المشروع
    $project_sql = "SELECT name FROM projects WHERE id = ?";
    $project_stmt = $conn->prepare($project_sql);
    $project_stmt->bind_param("i", $project_id);
    $project_stmt->execute();
    $project_result = $project_stmt->get_result();
    $project = $project_result->fetch_assoc();

    // جلب id العضو
    $user_sql = "SELECT id FROM users WHERE username = ?";
    $user_stmt = $conn->prepare($user_sql);
    $user_stmt->bind_param("s", $member_username);
    $user_stmt->execute();
    $user_result = $user_stmt->get_result();
    $user = $user_result->fetch_assoc();

    if ($user && $project) {
        $message = "You have been added to project: " . $project['name'];
        $related_url = "project.php?id=" . $project_id;
        return addNotification($user['id'], $triggered_by, $project_id, null, 'member_added', $message, $related_url);
    }
    return false;
}

// إشعار عند تعيين مهمة لعضو
function notifyTaskAssigned($card_id, $member_username, $triggered_by)
{
    global $conn;

    // جلب معلومات المهمة والمشروع
    $task_sql = "SELECT c.title, l.project_id FROM cards c 
                JOIN lists l ON c.list_id = l.id 
                WHERE c.id = ?";
    $task_stmt = $conn->prepare($task_sql);
    $task_stmt->bind_param("i", $card_id);
    $task_stmt->execute();
    $task_result = $task_stmt->get_result();
    $task = $task_result->fetch_assoc();

    // جلب id العضو
    $user_sql = "SELECT id FROM users WHERE username = ?";
    $user_stmt = $conn->prepare($user_sql);
    $user_stmt->bind_param("s", $member_username);
    $user_stmt->execute();
    $user_result = $user_stmt->get_result();
    $user = $user_result->fetch_assoc();

    if ($user && $task) {
        $message = "You have been assigned to task: " . $task['title'];
        $related_url = "card_details.php?id=" . $card_id;
        return addNotification($user['id'], $triggered_by, $task['project_id'], $card_id, 'task_assigned', $message, $related_url);
    }
    return false;
}

// إشعار عند تغيير حالة المهمة
function notifyTaskStatusChanged($card_id, $new_status, $triggered_by)
{
    global $conn;

    // جلب معلومات المهمة والمشروع والعضو المسؤول
    $task_sql = "SELECT c.title, l.project_id, cm.username 
                FROM cards c 
                JOIN lists l ON c.list_id = l.id 
                JOIN card_members cm ON c.id = cm.card_id 
                WHERE c.id = ?";
    $task_stmt = $conn->prepare($task_sql);
    $task_stmt->bind_param("i", $card_id);
    $task_stmt->execute();
    $task_result = $task_stmt->get_result();
    $task_members = $task_result->fetch_all(MYSQLI_ASSOC);

    if ($task_members) {
        $task = $task_members[0];
        $message = "Task status changed to " . $new_status . " for: " . $task['title'];
        $related_url = "card_details.php?id=" . $card_id;

        // إرسال إشعار لمدير المشروع
        $project_sql = "SELECT project_managers FROM projects WHERE id = ?";
        $project_stmt = $conn->prepare($project_sql);
        $project_stmt->bind_param("i", $task['project_id']);
        $project_stmt->execute();
        $project_result = $project_stmt->get_result();
        $project = $project_result->fetch_assoc();

        $managers = explode(',', $project['project_managers']);
        foreach ($managers as $manager) {
            $manager_sql = "SELECT id FROM users WHERE username = ?";
            $manager_stmt = $conn->prepare($manager_sql);
            $manager_stmt->bind_param("s", trim($manager));
            $manager_stmt->execute();
            $manager_result = $manager_stmt->get_result();
            $manager = $manager_result->fetch_assoc();

            if ($manager) {
                addNotification($manager['id'], $triggered_by, $task['project_id'], $card_id, 'task_status_changed', $message, $related_url);
            }
        }

        return true;
    }
    return false;
}

// إشعار عند رفع مرفق
function notifyAttachmentUploaded($card_id, $attachment_title, $triggered_by)
{
    global $conn;

    // جلب معلومات المهمة والمشروع
    $task_sql = "SELECT c.title, l.project_id FROM cards c 
                JOIN lists l ON c.list_id = l.id 
                WHERE c.id = ?";
    $task_stmt = $conn->prepare($task_sql);
    $task_stmt->bind_param("i", $card_id);
    $task_stmt->execute();
    $task_result = $task_stmt->get_result();
    $task = $task_result->fetch_assoc();

    if ($task) {
        $message = "New attachment uploaded: " . $attachment_title . " for task: " . $task['title'];
        $related_url = "card_details.php?id=" . $card_id;

        // إرسال إشعار لجميع أعضاء المهمة
        $members_sql = "SELECT u.id FROM card_members cm 
                       JOIN users u ON cm.username = u.username 
                       WHERE cm.card_id = ?";
        $members_stmt = $conn->prepare($members_sql);
        $members_stmt->bind_param("i", $card_id);
        $members_stmt->execute();
        $members_result = $members_stmt->get_result();
        $members = $members_result->fetch_all(MYSQLI_ASSOC);

        foreach ($members as $member) {
            addNotification($member['id'], $triggered_by, $task['project_id'], $card_id, 'attachment_uploaded', $message, $related_url);
        }

        // إرسال إشعار لمدير المشروع
        $project_sql = "SELECT project_managers FROM projects WHERE id = ?";
        $project_stmt = $conn->prepare($project_sql);
        $project_stmt->bind_param("i", $task['project_id']);
        $project_stmt->execute();
        $project_result = $project_stmt->get_result();
        $project = $project_result->fetch_assoc();

        $managers = explode(',', $project['project_managers']);
        foreach ($managers as $manager) {
            $manager_sql = "SELECT id FROM users WHERE username = ?";
            $manager_stmt = $conn->prepare($manager_sql);
            $manager_stmt->bind_param("s", trim($manager));
            $manager_stmt->execute();
            $manager_result = $manager_stmt->get_result();
            $manager = $manager_result->fetch_assoc();

            if ($manager) {
                addNotification($manager['id'], $triggered_by, $task['project_id'], $card_id, 'attachment_uploaded', $message, $related_url);
            }
        }

        return true;
    }
    return false;
}

// إشعار عند تغيير حالة المشروع
function notifyProjectStatusChanged($project_id, $new_status, $triggered_by)
{
    global $conn;

    // جلب معلومات المشروع
    $project_sql = "SELECT name, members FROM projects WHERE id = ?";
    $project_stmt = $conn->prepare($project_sql);
    $project_stmt->bind_param("i", $project_id);
    $project_stmt->execute();
    $project_result = $project_stmt->get_result();
    $project = $project_result->fetch_assoc();

    if ($project) {
        $message = "Project status changed to " . $new_status . " for: " . $project['name'];
        $related_url = "project.php?id=" . $project_id;

        // إرسال إشعار لجميع أعضاء المشروع
        $members = explode(',', $project['members']);
        foreach ($members as $member) {
            $member_sql = "SELECT id FROM users WHERE username = ?";
            $member_stmt = $conn->prepare($member_sql);
            $member_stmt->bind_param("s", trim($member));
            $member_stmt->execute();
            $member_result = $member_stmt->get_result();
            $member = $member_result->fetch_assoc();

            if ($member) {
                addNotification($member['id'], $triggered_by, $project_id, null, 'project_status_changed', $message, $related_url);
            }
        }

        return true;
    }
    return false;
}

// إشعار عند إضافة تعليق
function notifyTaskComment($card_id, $commenter_id, $triggered_by)
{
    global $conn;

    // جلب معلومات المهمة والمشروع
    $task_sql = "SELECT c.title, l.project_id FROM cards c 
                JOIN lists l ON c.list_id = l.id 
                WHERE c.id = ?";
    $task_stmt = $conn->prepare($task_sql);
    $task_stmt->bind_param("i", $card_id);
    $task_stmt->execute();
    $task_result = $task_stmt->get_result();
    $task = $task_result->fetch_assoc();

    // جلب اسم المعلق
    $commenter_sql = "SELECT username FROM users WHERE id = ?";
    $commenter_stmt = $conn->prepare($commenter_sql);
    $commenter_stmt->bind_param("i", $commenter_id);
    $commenter_stmt->execute();
    $commenter_result = $commenter_stmt->get_result();
    $commenter = $commenter_result->fetch_assoc();

    if ($task && $commenter) {
        $message = $commenter['username'] . " commented on task: " . $task['title'];
        $related_url = "card_details.php?id=" . $card_id;

        // إرسال إشعار لجميع أعضاء المهمة (باستثناء المعلق)
        $members_sql = "SELECT u.id FROM card_members cm 
                       JOIN users u ON cm.username = u.username 
                       WHERE cm.card_id = ? AND u.id != ?";
        $members_stmt = $conn->prepare($members_sql);
        $members_stmt->bind_param("ii", $card_id, $commenter_id);
        $members_stmt->execute();
        $members_result = $members_stmt->get_result();
        $members = $members_result->fetch_all(MYSQLI_ASSOC);

        foreach ($members as $member) {
            addNotification($member['id'], $triggered_by, $task['project_id'], $card_id, 'task_comment', $message, $related_url);
        }

        // إرسال إشعار لمدير المشروع
        $project_sql = "SELECT project_managers FROM projects WHERE id = ?";
        $project_stmt = $conn->prepare($project_sql);
        $project_stmt->bind_param("i", $task['project_id']);
        $project_stmt->execute();
        $project_result = $project_stmt->get_result();
        $project = $project_result->fetch_assoc();

        $managers = explode(',', $project['project_managers']);
        foreach ($managers as $manager) {
            $manager_sql = "SELECT id FROM users WHERE username = ?";
            $manager_stmt = $conn->prepare($manager_sql);
            $manager_stmt->bind_param("s", trim($manager));
            $manager_stmt->execute();
            $manager_result = $manager_stmt->get_result();
            $manager = $manager_result->fetch_assoc();

            if ($manager && $manager['id'] != $commenter_id) {
                addNotification($manager['id'], $triggered_by, $task['project_id'], $card_id, 'task_comment', $message, $related_url);
            }
        }

        return true;
    }
    return false;
}

// إشعار تذكير بموعد التسليم
function notifyDeadlineReminder($card_id)
{
    global $conn;

    // جلب معلومات المهمة
    $task_sql = "SELECT c.title, l.project_id, cd.due_date, cd.due_reminder 
                FROM cards c 
                JOIN lists l ON c.list_id = l.id 
                JOIN card_details cd ON c.id = cd.card_id 
                WHERE c.id = ?";
    $task_stmt = $conn->prepare($task_sql);
    $task_stmt->bind_param("i", $card_id);
    $task_stmt->execute();
    $task_result = $task_stmt->get_result();
    $task = $task_result->fetch_assoc();

    if ($task && $task['due_date'] && $task['due_reminder'] != 'None') {
        $message = "Deadline reminder for task: " . $task['title'];
        $related_url = "card_details.php?id=" . $card_id;

        // إرسال إشعار لجميع أعضاء المهمة
        $members_sql = "SELECT u.id FROM card_members cm 
                       JOIN users u ON cm.username = u.username 
                       WHERE cm.card_id = ?";
        $members_stmt = $conn->prepare($members_sql);
        $members_stmt->bind_param("i", $card_id);
        $members_stmt->execute();
        $members_result = $members_stmt->get_result();
        $members = $members_result->fetch_all(MYSQLI_ASSOC);

        foreach ($members as $member) {
            addNotification($member['id'], null, $task['project_id'], $card_id, 'deadline_reminder', $message, $related_url);
        }

        return true;
    }
    return false;
}
