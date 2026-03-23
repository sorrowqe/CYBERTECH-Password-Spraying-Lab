<?php
session_start();
require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    
    // Basic validation
    if (empty($username) || empty($password)) {
        header("Location: login.php?error=" . urlencode("Please enter both username and password"));
        exit();
    }
    
    // Connect to database
    $conn = getDBConnection();
    
    // Hash password with MD5 (INTENTIONALLY WEAK for lab!)
    $hashed_password = md5($password);
    
    // Check credentials
    $stmt = $conn->prepare("SELECT id, username, email FROM users WHERE username = ? AND password = ?");
    $stmt->bind_param("ss", $username, $hashed_password);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows == 1) {
        $user = $result->fetch_assoc();
        
        // Set session variables
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['email'] = $user['email'];
        
        $stmt->close();
        $conn->close();
        
        // Redirect to dashboard
        header("Location: dashboard.php");
        exit();
    } else {
        $stmt->close();
        $conn->close();
        
        // Failed login
        header("Location: login.php?error=" . urlencode("Invalid username or password"));
        exit();
    }
} else {
    // Not a POST request
    header("Location: login.php");
    exit();
}
?>
