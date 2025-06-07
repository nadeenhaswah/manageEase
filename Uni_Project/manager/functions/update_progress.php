<?php
include('../../confing/DB_connection.php');

function updateChecklistProgress($checklist_id, $conn = null)
{
    $closeAfter = false;

    if (!$conn) {
        include('../../confing/DB_connection.php');
        $closeAfter = true;
    }

    // إجمالي العناصر
    $stmt = $conn->prepare("SELECT COUNT(*) as total FROM card_checklist_items WHERE checklist_id = ?");
    $stmt->bind_param("i", $checklist_id);
    $stmt->execute();
    $stmt->bind_result($total);
    $stmt->fetch();
    $stmt->close();

    if ($total == 0) {
        $progress = 0;
    } else {
        // عدد العناصر المحددة
        $stmt = $conn->prepare("SELECT COUNT(*) as checked FROM card_checklist_items WHERE checklist_id = ? AND is_checked = 1");
        $stmt->bind_param("i", $checklist_id);
        $stmt->execute();
        $stmt->bind_result($checked);
        $stmt->fetch();
        $stmt->close();

        $progress = round(($checked / $total) * 100);
    }

    // تحديث نسبة الإنجاز في checklist
    $stmt = $conn->prepare("UPDATE card_checklists SET progress = ? WHERE id = ?");
    $stmt->bind_param("ii", $progress, $checklist_id);
    $stmt->execute();
    $stmt->close();

    if ($closeAfter) {
        $conn->close();
    }

    return $progress;
}
