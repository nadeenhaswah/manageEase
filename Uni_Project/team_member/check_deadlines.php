<?php
// check_deadlines.php
include('../confing/DB_connection.php');
include('notification_functions.php');

// جلب جميع المهام التي لها مواعيد نهائية وتذكير
$sql = "SELECT c.id 
        FROM cards c 
        JOIN card_details cd ON c.id = cd.card_id 
        WHERE cd.due_date IS NOT NULL 
        AND cd.due_reminder != 'None' 
        AND cd.status != 'Done'";
$result = $conn->query($sql);

while ($row = $result->fetch_assoc()) {
    $card_id = $row['id'];

    // جلب تفاصيل المهمة
    $task_sql = "SELECT cd.due_date, cd.due_reminder 
                FROM card_details cd 
                WHERE cd.card_id = ?";
    $task_stmt = $conn->prepare($task_sql);
    $task_stmt->bind_param("i", $card_id);
    $task_stmt->execute();
    $task_result = $task_stmt->get_result();
    $task = $task_result->fetch_assoc();

    if ($task) {
        $due_date = new DateTime($task['due_date']);
        $now = new DateTime();
        $interval = $now->diff($due_date);

        // التحقق من التذكير بناءً على الإعدادات
        $should_notify = false;
        switch ($task['due_reminder']) {
            case '10 minutes before':
                $should_notify = ($interval->days == 0 && $interval->h == 0 && $interval->i <= 10);
                break;
            case '1 hour before':
                $should_notify = ($interval->days == 0 && $interval->h <= 1);
                break;
            case '1 day before':
                $should_notify = ($interval->days <= 1);
                break;
            case '2 days before':
                $should_notify = ($interval->days <= 2);
                break;
        }

        if ($should_notify) {
            notifyDeadlineReminder($card_id);
        }
    }
}
