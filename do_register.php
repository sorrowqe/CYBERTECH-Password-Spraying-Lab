<?php
require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'] ?? '';
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';
    
    // Basic validation
    if (empty($username) || empty($email) || empty($password)) {
        header("Location: register.php?error=" . urlencode("All fields are required"));
        exit();
    }
    
    // Check password confirmation
    if ($password !== $confirm_password) {
        header("Location: register.php?error=" . urlencode("Passwords do not match"));
        exit();
    }
    
    // Connect to database
    $conn = getDBConnection();
    
    // Check if username already exists
    $stmt = $conn->prepare("SELECT id FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $stmt->close();
        $conn->close();
        header("Location: register.php?error=" . urlencode("Username already exists"));
        exit();
    }
    $stmt->close();
    
    // Check if email already exists
    $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $stmt->close();
        $conn->close();
        header("Location: register.php?error=" . urlencode("Email already registered"));
        exit();
    }
    $stmt->close();
    
    // Hash password with MD5 (INTENTIONALLY WEAK for lab!)
    $hashed_password = md5($password);
    
    // Insert new user
    $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $username, $email, $hashed_password);
    
    if ($stmt->execute()) {
        $stmt->close();
        $conn->close();
        header("Location: login.php?success=" . urlencode("Account created successfully! Please login."));
        exit();
    } else {
        $stmt->close();
        $conn->close();
        header("Location: register.php?error=" . urlencode("Registration failed. Please try again."));
        exit();
    }
} else {
    header("Location: register.php");
    exit();
}
?>1~<?php
require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'] ?? '';
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';
    
    // Basic validation
    if (empty($username) || empty($email) || empty($password)) {
        header("Location: register.php?error=" . urlencode("All fields are required"));
        exit();
    }
    
    // Check password confirmation
    if ($password !== $confirm_password) {
        header("Location: register.php?error=" . urlencode("Passwords do not match"));
        exit();
    }
    
    // Connect to database
    $conn = getDBConnection();
    
    // Check if username already exists
    $stmt = $conn->prepare("SELECT id FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $stmt->close();
        $conn->close();
        header("Location: register.php?error=" . urlencode("Username already exists"));
        exit();
    }
    $stmt->close();
    
    // Check if email already exists
    $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $stmt->close();
        $conn->close();
        header("Location: register.php?error=" . urlencode("Email already registered"));
        exit();
    }
    $stmt->close();
    
    // Hash password with MD5 (INTENTIONALLY WEAK for lab!)
    $hashed_password = md5($password);
    
    // Insert new user
    $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $username, $email, $hashed_password);
    
    if ($stmt->execute()) {
        $stmt->close();
        $conn->close();
        header("Location: login.php?success=" . urlencode("Account created successfully! Please login."));
        exit();
    } else {
        $stmt->close();
        $conn->close();
        header("Location: register.php?error=" . urlencode("Registration failed. Please try again."));
        exit();
    }
} else {
    header("Location: register.php");
    exit();
}
?>
