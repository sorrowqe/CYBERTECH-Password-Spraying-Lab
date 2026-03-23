<?php
session_start();

// If already logged in, redirect to dashboard
if (isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - CYBERTECH SCOOT</title>
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=IBM+Plex+Mono:wght@400;600&display=swap" rel="stylesheet">
    <link rel="icon" type="image/x-icon" href="/images/cybertech-logo.png">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        :root {
            --red: #DC143C;
            --dark-red: #8B0000;
            --black: #0a0a0a;
            --white: #ffffff;
            --off-white: #f8f8f8;
            --gray: #333333;
        }
        
        body {
            font-family: 'IBM Plex Mono', monospace;
            background: var(--black);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        
        .navbar {
            background: var(--black);
            padding: 20px 30px;
            border-bottom: 3px solid var(--red);
        }
        
        .navbar a {
            color: var(--white);
            text-decoration: none;
            font-size: 13px;
            text-transform: uppercase;
            letter-spacing: 1px;
            transition: color 0.2s;
        }
        
        .navbar a:hover {
            color: var(--red);
        }
        
        .container {
            flex: 1;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 40px 20px;
        }
        
        .login-box {
            background: var(--white);
            width: 100%;
            max-width: 480px;
            padding: 60px 50px;
            border: 4px solid var(--black);
            position: relative;
        }
        
        .login-box::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 6px;
            background: var(--red);
        }
        
        .logo {
            text-align: center;
            margin-bottom: 40px;
        }
        
        .logo .shield {
            font-size: 48px;
            margin-bottom: 15px;
        }
        
        .logo h1 {
            font-family: 'Bebas Neue', sans-serif;
            color: var(--black);
            font-size: 42px;
            letter-spacing: 2px;
            margin-bottom: 5px;
        }
        
        .logo p {
            color: var(--gray);
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        
        .message {
            padding: 15px;
            margin-bottom: 25px;
            text-align: center;
            font-size: 13px;
            border: 2px solid;
        }
        
        .message.error {
            background: var(--off-white);
            color: var(--dark-red);
            border-color: var(--red);
        }
        
        .message.success {
            background: var(--off-white);
            color: var(--black);
            border-color: var(--black);
        }
        
        .form-group {
            margin-bottom: 25px;
        }
        
        label {
            display: block;
            margin-bottom: 10px;
            color: var(--black);
            font-weight: 600;
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        
        input[type="text"],
        input[type="password"] {
            width: 100%;
            padding: 15px;
            border: 3px solid var(--black);
            background: var(--white);
            font-size: 14px;
            font-family: 'IBM Plex Mono', monospace;
            transition: all 0.2s;
        }
        
        input[type="text"]:focus,
        input[type="password"]:focus {
            outline: none;
            border-color: var(--red);
            background: var(--off-white);
        }
        
        .btn {
            width: 100%;
            padding: 16px;
            background: var(--black);
            color: var(--white);
            border: 3px solid var(--black);
            font-size: 13px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            cursor: pointer;
            transition: all 0.2s;
            font-family: 'IBM Plex Mono', monospace;
        }
        
        .btn:hover {
            background: var(--red);
            border-color: var(--red);
        }
        
        .divider {
            text-align: center;
            margin: 30px 0;
            color: var(--gray);
            position: relative;
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        
        .divider::before,
        .divider::after {
            content: "";
            position: absolute;
            top: 50%;
            width: 42%;
            height: 2px;
            background: var(--black);
        }
        
        .divider::before {
            left: 0;
        }
        
        .divider::after {
            right: 0;
        }
        
        .signup-link {
            text-align: center;
            margin-top: 25px;
            color: var(--gray);
            font-size: 13px;
        }
        
        .signup-link a {
            color: var(--red);
            text-decoration: none;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        
        .signup-link a:hover {
            text-decoration: underline;
        }
        
        @media (max-width: 768px) {
            .login-box {
                padding: 40px 30px;
            }
            
            .logo h1 {
                font-size: 36px;
            }
        }
    </style>
</head>
<body>
    <div class="navbar">
        <a href="index.php">← Back to Home</a>
    </div>
    
    <div class="container">
        <div class="login-box">
            <div class="logo">
                <!--<div class="shield">🔐</div><!-->
                <img src="images/cybertech-logo.png" class="shield" width="250" height="250"></img>
                <h1>CYBERTECH SCOOT Login</h1>
                <p>Access Your Secure Account</p>
            </div>
            
            <?php
            // Display messages
            if (isset($_GET['error'])) {
                echo '<div class="message error">' . htmlspecialchars($_GET['error']) . '</div>';
            }
            if (isset($_GET['success'])) {
                echo '<div class="message success">' . htmlspecialchars($_GET['success']) . '</div>';
            }
            ?>
            
            <form method="POST" action="do_login.php">
                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" id="username" name="username" required autofocus>
                </div>
                
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" required>
                </div>
                
                <button type="submit" class="btn">Login Securely</button>
            </form>
            
            <div class="divider">OR</div>
            
            <div class="signup-link">
                Don't have an account? <a href="register.php">Create one now</a>
            </div>
        </div>
    </div>
</body>
</html>