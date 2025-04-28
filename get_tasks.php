<?php
require_once 'db.php';
checkAuth();

$userId = $_SESSION['user_id'];
$filter = $_GET['filter'] ?? 'all';

try {
    $query = "SELECT id, task_text, is_completed FROM tasks WHERE user_id = ?";
    
    if ($filter === 'active') {
        $query .= " AND is_completed = FALSE";
    } elseif ($filter === 'completed') {
        $query .= " AND is_completed = TRUE";
    }
    
    $query .= " ORDER BY created_at DESC";
    
    $stmt = $pdo->prepare($query);
    $stmt->execute([$userId]);
    $tasks = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo json_encode(['success' => true, 'tasks' => $tasks]);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Error fetching tasks', 'error' => $e->getMessage()]);
}
?>