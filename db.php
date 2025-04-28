<?php
// InfinityFree database configuration
$host = 'sqlXXX.epizy.com'; // Replace with your InfinityFree host
$dbname = 'epiz_XXX_database'; // Replace with your database name
$username = 'epiz_XXX_username'; // Replace with your username
$password = 'your_password'; // Replace with your password

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Create tables if they don't exist
    $pdo->exec("CREATE TABLE IF NOT EXISTS users (
        id INT AUTO_INCREMENT PRIMARY KEY,
        username VARCHAR(50) NOT NULL UNIQUE,
        email VARCHAR(100) NOT NULL UNIQUE,
        password VARCHAR(255) NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )");
    
    $pdo->exec("CREATE TABLE IF NOT EXISTS tasks (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT NOT NULL,
        task_text VARCHAR(255) NOT NULL,
        is_completed BOOLEAN DEFAULT FALSE,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
    )");
    
} catch (PDOException $e) {
    die(json_encode(['success' => false, 'message' => 'Database connection failed', 'error' => $e->getMessage()]));
}

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function checkAuth() {
    if (!isset($_SESSION['user_id'])) {
        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'message' => 'Unauthorized access']);
        exit();
    }
}
?>