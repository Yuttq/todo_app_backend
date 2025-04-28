<?php
require_once 'db.php';
checkAuth();

$data = json_decode(file_get_contents('php://input'), true);
$taskId = $data['task_id'] ?? null;
$userId = $_SESSION['user_id'];

if (!$taskId) {
    echo json_encode(['success' => false, 'message' => 'Task ID required']);
    exit();
}

try {
    $stmt = $pdo->prepare("DELETE FROM tasks WHERE id = ? AND user_id = ?");
    $stmt->execute([$taskId, $userId]);
    
    echo json_encode(['success' => $stmt->rowCount() > 0]);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Error deleting task', 'error' => $e->getMessage()]);
}
?>