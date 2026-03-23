<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php?error=" . urlencode("Please login first"));
    exit();
}

$username = $_SESSION['username'];
$email = $_SESSION['email'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - CYBERTECH SCOOT</title>
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
            --light-gray: #e5e5e5;
        }
        
        body {
            font-family: 'IBM Plex Mono', monospace;
            background: var(--off-white);
            color: var(--black);
            line-height: 1.6;
        }
        
        /* Navigation */
        .navbar {
            background: var(--black);
            padding: 1.2rem 0;
            border-bottom: 3px solid var(--red);
            position: sticky;
            top: 0;
            z-index: 100;
        }
        
        .nav-container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .logo {
            font-family: 'Bebas Neue', sans-serif;
            font-size: 32px;
            color: var(--white);
            letter-spacing: 2px;
        }
        
        .logo span {
            color: var(--red);
        }
        
        .nav-links {
            display: flex;
            gap: 35px;
            align-items: center;
        }
        
        .nav-links a {
            color: var(--white);
            text-decoration: none;
            font-size: 13px;
            text-transform: uppercase;
            letter-spacing: 1px;
            transition: color 0.2s;
        }
        
        .nav-links a:hover {
            color: var(--red);
        }
        
        .user-greeting {
            color: var(--light-gray);
            font-size: 12px;
        }
        
        .btn-logout {
            background: var(--red);
            padding: 10px 20px;
            border-radius: 0;
            color: var(--white);
            border: 2px solid var(--red);
            transition: all 0.2s;
        }
        
        .btn-logout:hover {
            background: transparent;
            color: var(--red);
        }
        
        /* Container */
        .container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 40px 30px;
        }
        
        /* Welcome Section */
        .welcome-section {
            background: var(--black);
            color: var(--white);
            padding: 60px 50px;
            margin-bottom: 40px;
            border-left: 6px solid var(--red);
        }
        
        .welcome-section h1 {
            font-family: 'Bebas Neue', sans-serif;
            font-size: 48px;
            margin-bottom: 8px;
            letter-spacing: 2px;
        }
        
        .welcome-section p {
            font-size: 14px;
            opacity: 0.8;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        
        /* Stats Grid */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 25px;
            margin-bottom: 40px;
        }
        
        .stat-card {
            background: var(--white);
            padding: 35px;
            border: 3px solid var(--black);
            position: relative;
        }
        
        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 4px;
            background: var(--red);
        }
        
        .stat-card .icon {
            font-size: 38px;
            margin-bottom: 15px;
        }
        
        .stat-card h3 {
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 10px;
            color: var(--gray);
        }
        
        .stat-card .number {
            font-family: 'Bebas Neue', sans-serif;
            font-size: 42px;
            color: var(--black);
            letter-spacing: 1px;
        }
        
        /* Section */
        .section {
            background: var(--white);
            padding: 40px;
            margin-bottom: 30px;
            border: 3px solid var(--black);
        }
        
        .section h2 {
            font-family: 'Bebas Neue', sans-serif;
            font-size: 32px;
            margin-bottom: 30px;
            letter-spacing: 1px;
            padding-bottom: 15px;
            border-bottom: 3px solid var(--red);
        }
        
        /* Rental Cards */
        .rental-card {
            background: var(--off-white);
            padding: 25px;
            margin-bottom: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-left: 4px solid var(--gray);
            transition: border-color 0.2s;
        }
        
        .rental-card:hover {
            border-left-color: var(--red);
        }
        
        .rental-info h4 {
            font-size: 16px;
            margin-bottom: 8px;
            font-weight: 600;
        }
        
        .rental-info p {
            color: var(--gray);
            font-size: 12px;
        }
        
        .rental-status {
            padding: 8px 18px;
            font-size: 11px;
            font-weight: 600;
            letter-spacing: 1px;
            text-transform: uppercase;
            border: 2px solid;
        }
        
        .status-active {
            background: var(--red);
            color: var(--white);
            border-color: var(--red);
        }
        
        .status-completed {
            background: transparent;
            color: var(--black);
            border-color: var(--black);
        }
        
        /* Buttons */
        .btn {
            display: inline-block;
            padding: 14px 28px;
            text-decoration: none;
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            font-weight: 600;
            transition: all 0.2s;
            border: 2px solid var(--black);
            background: var(--black);
            color: var(--white);
            margin-right: 15px;
            margin-bottom: 10px;
        }
        
        .btn:hover {
            background: transparent;
            color: var(--black);
        }
        
        /* Info Box */
        .info-box {
            background: var(--black);
            color: var(--white);
            padding: 25px;
            margin-top: 30px;
            border-left: 6px solid var(--red);
        }
        
        .info-box strong {
            color: var(--red);
            font-size: 14px;
        }
        
        @media (max-width: 768px) {
            .nav-links {
                gap: 15px;
                font-size: 11px;
            }
            
            .user-greeting {
                display: none;
            }
            
            .welcome-section h1 {
                font-size: 36px;
            }
            
            .stats-grid {
                grid-template-columns: 1fr;
            }
            
            .rental-card {
                flex-direction: column;
                align-items: flex-start;
                gap: 15px;
            }
        }
    </style>
</head>
<body>
    <nav class="navbar">
        <div class="nav-container">
            <div class="logo">
               <!-- CBRTCH<span>.</span> <!-->
                <a href="index.php" style="text-decoration: none;">
                <img src="images/cybertech-logo-main2.png" width="370" height="80" class="shield"></img>
                </a>
            </div>
            <div class="nav-links">
                <a href="index.php">Home</a>
                <a href="dashboard.php">Dashboard</a>
                <a href="profile.php">Profile</a>
                <span class="user-greeting">Welcome, <?php echo htmlspecialchars($username); ?></span>
                <a href="logout.php" class="btn-logout">Logout</a>
            </div>
        </div>
    </nav>
    
    <div class="container">
        <div class="welcome-section">
            <h1>Welcome back, <?php echo htmlspecialchars($username); ?></h1>
            <p>Your Secure Scooter Rental Dashboard</p>
        </div>
        
        <div class="stats-grid">
            <div class="stat-card">
                <div class="icon">🛴</div>
                <h3>Total Rides</h3>
                <div class="number">12</div>
            </div>
            <div class="stat-card">
                <div class="icon">⏱️</div>
                <h3>Hours Ridden</h3>
                <div class="number">24.5</div>
            </div>
            <div class="stat-card">
                <div class="icon">💰</div>
                <h3>Total Spent</h3>
                <div class="number">$368</div>
            </div>
            <div class="stat-card">
                <div class="icon">⭐</div>
                <h3>Membership Level</h3>
                <div class="number">Gold</div>
            </div>
        </div>
        
        <div class="section">
            <h2>Recent Rentals</h2>
            
            <div class="rental-card">
                <div class="rental-info">
                    <h4>🛴 City Cruiser</h4>
                    <p>Started: Today at 2:30 PM • Duration: 1.5 hours</p>
                </div>
                <span class="rental-status status-active">Active</span>
            </div>
            
            <div class="rental-card">
                <div class="rental-info">
                    <h4>🛴 Speed Demon</h4>
                    <p>Yesterday at 4:15 PM • Duration: 2 hours</p>
                </div>
                <span class="rental-status status-completed">Completed</span>
            </div>
            
            <div class="rental-card">
                <div class="rental-info">
                    <h4>🛴 Eco Rider</h4>
                    <p>Dec 15, 2024 • Duration: 3 hours</p>
                </div>
                <span class="rental-status status-completed">Completed</span>
            </div>
        </div>
        
        <div class="section">
            <h2>Quick Actions</h2>
            <a href="profile.php" class="btn">📝 Edit Profile</a>
            <a href="index.php#scooters" class="btn">🛴 Rent Scooter</a>
            <a href="#" class="btn">📊 Full History</a>
        </div>
        
        <div class="info-box">
            <strong>🔒 Security Status:</strong> Your account is secured with military-grade encryption. All personal information and payment details are stored safely in our unhackable database.
        </div>
    </div>
</body>
</html>