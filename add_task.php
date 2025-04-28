<?php
require_once 'db.php';
checkAuth();

$data = json_decode(file_get_contents('php://input'), true);
$taskText = trim($data['task_text'] ?? '');
$userId = $_SESSION['user_id'];

if (empty($taskText)) {
    echo json_encode(['success' => false, 'message' => 'Task cannot be empty']);
    exit();
}

try {
    $stmt = $pdo->prepare("INSERT INTO tasks (user_id, task_text) VALUES (?, ?)");
    $stmt->execute([$userId, $taskText]);
    
    $taskId = $pdo->lastInsertId();
    $task = [
        'id' => $taskId,
        'task_text' => $taskText,
        'is_completed' => false
    ];
    
    echo json_encode(['success' => true, 'task' => $task]);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Error adding task', 'error' => $e->getMessage()]);
}
?>