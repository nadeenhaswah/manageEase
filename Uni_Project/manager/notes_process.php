<?php
include('../confing/DB_connection.php');
session_start();

if (!isset($_SESSION['id']) || !isset($_SESSION['username'])) {
    die(json_encode(['success' => false, 'error' => 'Unauthorized']));
}

$user_id = $_SESSION['id'];
// $action = $_POST['action'] ?? '';
$action  = isset($_POST['action']) ? $_POST['action'] : '';

switch ($action) {
    case 'create':
        createNote($conn, $user_id);
        break;
    case 'update':
        updateNote($conn, $user_id);
        break;
    case 'delete':
        deleteNote($conn, $user_id);
        break;
    case 'load':
        loadNotes($conn, $user_id);
        break;
    default:
        echo json_encode(['success' => false, 'error' => 'Invalid action']);
}

function createNote($conn, $user_id)
{
    $title = isset($_POST['title']) ? $_POST['title'] : '';
    $content = isset($_POST['content']) ? $_POST['content'] : '';
    $color = isset($_POST['color']) ? $_POST['color'] : '#6851f0';
    $position_x = isset($_POST['position_x']) ? $_POST['position_x'] : 50;
    $position_y = isset($_POST['position_y']) ? $_POST['position_y'] : 130;
    $width = isset($_POST['width']) ? $_POST['width'] : 200;
    $height = isset($_POST['height']) ? $_POST['height'] : 200;


    $stmt = $conn->prepare("INSERT INTO notes (user_id, title, content, color, position_x, position_y, width, height) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("isssiiii", $user_id, $title, $content, $color, $position_x, $position_y, $width, $height);

    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'note_id' => $stmt->insert_id]);
    } else {
        echo json_encode(['success' => false, 'error' => $stmt->error]);
    }
}

function updateNote($conn, $user_id)
{

    $title = isset($_POST['title']) ? $_POST['title'] : '';
    $note_id = isset($_POST['note_id']) ? $_POST['note_id'] : 0;
    $content = isset($_POST['content']) ? $_POST['content'] : '';
    // $color = isset($_POST['color']) ? $_POST['color'] : '#6851f0';
    $position_x = isset($_POST['position_x']) ? $_POST['position_x'] : 0;
    $position_y = isset($_POST['position_y']) ? $_POST['position_y'] : 0;
    $width = isset($_POST['width']) ? $_POST['width'] : 0;
    $height = isset($_POST['height']) ? $_POST['height'] : 0;

    $stmt = $conn->prepare("UPDATE notes SET title = ?, content = ?, position_x = ?, position_y = ?, width = ?, height = ?, updated_at = CURRENT_TIMESTAMP WHERE id = ? AND user_id = ?");
    $stmt->bind_param("ssiiiiii", $title, $content, $position_x, $position_y, $width, $height, $note_id, $user_id);

    echo $stmt->execute() ? json_encode(['success' => true]) : json_encode(['success' => false, 'error' => $stmt->error]);
}

function deleteNote($conn, $user_id)
{
    $note_id = isset($_POST['note_id']) ? $_POST['note_id'] : 0;

    $stmt = $conn->prepare("DELETE FROM notes WHERE id = ? AND user_id = ?");
    $stmt->bind_param("ii", $note_id, $user_id);

    echo $stmt->execute() ? json_encode(['success' => true]) : json_encode(['success' => false, 'error' => $stmt->error]);
}

function loadNotes($conn, $user_id)
{
    $stmt = $conn->prepare("SELECT * FROM notes WHERE user_id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $notes = [];
    while ($row = $result->fetch_assoc()) {
        $notes[] = $row;
    }

    echo json_encode(['success' => true, 'notes' => $notes]);
}
