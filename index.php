<?php
session_start();
$isLoggedIn = isset($_SESSION['user_id']);
$username = $isLoggedIn ? $_SESSION['username'] : '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CYBERTECH SCOOT - Electric Scooter Rentals</title>
    <link rel="icon" type="image/x-icon" href="/images/cybertech-logo.png">
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=IBM+Plex+Mono:wght@400;600&display=swap" rel="stylesheet">
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
            background: var(--white);
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
        
        .btn-auth {
            background: var(--red);
            padding: 10px 20px;
            border-radius: 0;
            color: var(--white);
            border: 2px solid var(--red);
            transition: all 0.2s;
        }
        
        .btn-auth:hover {
            background: transparent;
            color: var(--red);
        }
        
        /* Hero Section */
        .hero {
            background: var(--black);
            color: var(--white);
            padding: 100px 30px;
            text-align: center;
            position: relative;
            overflow: hidden;
        }
        
        .hero::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, var(--red) 0%, transparent 100%);
        }
        
        .hero-content {
            max-width: 900px;
            margin: 0 auto;
            position: relative;
            z-index: 2;
        }
        
        .security-badge {
            display: inline-block;
            background: var(--dark-red);
            padding: 8px 16px;
            margin-bottom: 30px;
            font-size: 11px;
            letter-spacing: 2px;
            text-transform: uppercase;
        }
        
        .hero h1 {
            font-family: 'Bebas Neue', sans-serif;
            font-size: 72px;
            line-height: 1.1;
            margin-bottom: 25px;
            letter-spacing: 3px;
        }
        
        .hero h1 span {
            color: var(--red);
        }
        
        .hero p {
            font-size: 16px;
            margin-bottom: 40px;
            opacity: 0.85;
            max-width: 600px;
            margin-left: auto;
            margin-right: auto;
        }
        
        .hero-buttons {
            display: flex;
            gap: 20px;
            justify-content: center;
        }
        
        .btn {
            padding: 16px 35px;
            text-decoration: none;
            font-size: 13px;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            font-weight: 600;
            transition: all 0.2s;
            border: 2px solid;
        }
        
        .btn-primary {
            background: var(--red);
            color: var(--white);
            border-color: var(--red);
        }
        
        .btn-primary:hover {
            background: var(--dark-red);
            border-color: var(--dark-red);
        }
        
        .btn-secondary {
            background: transparent;
            color: var(--white);
            border-color: var(--white);
        }
        
        .btn-secondary:hover {
            background: var(--white);
            color: var(--black);
        }
        
        /* Winners Section */
        .winners {
            background: var(--red);
            color: var(--white);
            padding: 50px 30px;
            text-align: center;
        }
        
        .winners h2 {
            font-family: 'Bebas Neue', sans-serif;
            font-size: 36px;
            margin-bottom: 10px;
            letter-spacing: 2px;
        }
        
        .winners-grid {
            max-width: 1000px;
            margin: 30px auto 0;
            display: flex;
            justify-content: center;
            gap: 30px;
            flex-wrap: wrap;
        }
        
        .winner-card {
            background: rgba(0, 0, 0, 0.3);
            padding: 20px;
            min-width: 140px;
            border: 2px solid rgba(255, 255, 255, 0.3);
        }
        
        .winner-icon {
            font-size: 32px;
            margin-bottom: 10px;
        }
        
        .winner-name {
            font-size: 15px;
            font-weight: 600;
        }
        
        /* Features Section */
        .features {
            padding: 80px 30px;
            background: var(--off-white);
        }
        
        .section-title {
            font-family: 'Bebas Neue', sans-serif;
            font-size: 48px;
            text-align: center;
            margin-bottom: 60px;
            letter-spacing: 2px;
        }
        
        .features-grid {
            max-width: 1400px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
            gap: 40px;
        }
        
        .feature-card {
            background: var(--white);
            padding: 40px 30px;
            border-left: 4px solid var(--red);
            transition: transform 0.2s;
        }
        
        .feature-card:hover {
            transform: translateX(5px);
        }
        
        .feature-icon {
            font-size: 42px;
            margin-bottom: 20px;
        }
        
        .feature-card h3 {
            font-family: 'Bebas Neue', sans-serif;
            font-size: 24px;
            margin-bottom: 12px;
            letter-spacing: 1px;
        }
        
        .feature-card p {
            font-size: 14px;
            line-height: 1.7;
            color: var(--gray);
        }
        
        /* Scooters Section */
        .scooters {
            padding: 80px 30px;
            background: var(--black);
            color: var(--white);
        }
        
        .scooters .section-title {
            color: var(--white);
        }
        
        .scooters-grid {
            max-width: 1400px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
            gap: 30px;
        }
        
        .scooter-card {
            background: var(--gray);
            overflow: hidden;
            border: 3px solid transparent;
            transition: border-color 0.2s;
        }
        
        .scooter-card:hover {
            border-color: var(--red);
        }
        
        .scooter-header {
            padding: 30px;
            background: rgba(0, 0, 0, 0.4);
        }
        
        .scooter-icon {
            font-size: 48px;
            margin-bottom: 15px;
        }
        
        .scooter-card h3 {
            font-family: 'Bebas Neue', sans-serif;
            font-size: 28px;
            margin-bottom: 10px;
            letter-spacing: 1px;
        }
        
        .scooter-card p {
            font-size: 13px;
            opacity: 0.85;
            margin-bottom: 20px;
        }
        
        .scooter-price {
            font-size: 24px;
            font-weight: 600;
            color: var(--red);
            margin-bottom: 20px;
        }
        
        .scooter-footer {
            padding: 0 30px 30px;
        }
        
        .btn-small {
            padding: 12px 25px;
            font-size: 12px;
        }
        
        /* Footer */
        .footer {
            background: var(--black);
            color: var(--white);
            padding: 40px 30px;
            text-align: center;
            border-top: 3px solid var(--red);
        }
        
        .footer p {
            font-size: 13px;
            opacity: 0.7;
        }
        
        @media (max-width: 768px) {
            .hero h1 {
                font-size: 48px;
            }
            
            .nav-links {
                gap: 15px;
                font-size: 11px;
            }
            
            .hero-buttons {
                flex-direction: column;
            }
            
            .features-grid,
            .scooters-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <nav class="navbar">
        <div class="nav-container">
            <div class="logo">
                <!--CBRTCH<span>.</span><!-->
                <a href="index.php" style="text-decoration: none;">
                <img src="images/cybertech-logo-main2.png" width="370" height="80" class="shield"></img>
                </a>
            </div>
            <div class="nav-links">
                <a href="#home">Home</a>
                <a href="#scooters">Scooters</a>
                <a href="#features">Features</a>
                <?php if ($isLoggedIn): ?>
                    <a href="dashboard.php">Dashboard</a>
                    <a href="profile.php">Profile</a>
                    <span style="color: var(--white); font-size: 12px;">Welcome, <?php echo htmlspecialchars($username); ?></span>
                    <a href="logout.php" class="btn-auth">Logout</a>
                <?php else: ?>
                    <a href="login.php" class="btn-auth">Login</a>
                    <a href="register.php" class="btn-auth">Sign Up</a>
                <?php endif; ?>
            </div>
        </div>
    </nav>
    
    <section class="hero" id="home">
        <div class="hero-content">
            <div class="security-badge">
                🔒 Military-Grade Encryption • Unhackable Technology
            </div>
            <h1>CYBERTECH <span>Unhackable</span> Scooters</h1>
            <p>The most secure scooter rental platform. Your data is protected with cutting-edge cybersecurity technology that's never been compromised.</p>
            <div class="hero-buttons">
                <?php if ($isLoggedIn): ?>
                    <a href="dashboard.php" class="btn btn-primary">View Dashboard</a>
                    <a href="#scooters" class="btn btn-secondary">Rent a Scooter</a>
                <?php else: ?>
                    <a href="register.php" class="btn btn-primary">Get Started Today</a>
                    <a href="login.php" class="btn btn-secondary">Login</a>
                <?php endif; ?>
            </div>
        </div>
    </section>
    
    <section class="winners">
        <h2>🏆 2026 Giveaway Winners</h2>
        <p>Congratulations to our winners who scored free premium memberships!</p>
        <div class="winners-grid">
            <div class="winner-card">
                <div class="winner-icon">🥇</div>
                <div class="winner-name">Mister_Robot</div>
            </div>
            <div class="winner-card">
                <div class="winner-icon">🥈</div>
                <div class="winner-name">Andy2355</div>
            </div>
            <div class="winner-card">
                <div class="winner-icon">🥉</div>
                <div class="winner-name">TikeMyson</div>
            </div>
            <div class="winner-card">
                <div class="winner-icon">🎉</div>
                <div class="winner-name">JackieCh4n</div>
            </div>
            <div class="winner-card">
                <div class="winner-icon">⭐</div>
                <div class="winner-name">PuisturH4cker</div>
            </div>
        </div>
    </section>
    
    <section class="features" id="features">
        <h2 class="section-title">Why CYBERTECH SCOOT is Unhackable</h2>
        <div class="features-grid">
            <div class="feature-card">
                <div class="feature-icon">🔐</div>
                <h3>Military-Grade Security</h3>
                <p>Our platform uses advanced encryption that even hackers can't break. Your personal information is completely safe with us.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon">🛡️</div>
                <h3>Unhackable Technology</h3>
                <p>Powered by the latest cybersecurity innovations. We've never been hacked and never will be.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon">⚡</div>
                <h3>Lightning Fast</h3>
                <p>Rent scooters instantly with our streamlined platform. Security doesn't mean slow.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon">💳</div>
                <h3>Secure Payments</h3>
                <p>Your credit card and banking information is stored with bank-level security protocols.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon">📱</div>
                <h3>Easy to Use</h3>
                <p>Simple interface, powerful security. Anyone can use CYBERTECH SCOOT safely and securely.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon">🌍</div>
                <h3>Global Coverage</h3>
                <p>Rent scooters in over 50 cities worldwide with one secure account.</p>
            </div>
        </div>
    </section>
    
    <section class="scooters" id="scooters">
        <h2 class="section-title">Our Premium Scooter Fleet</h2>
        <div class="scooters-grid">
            <div class="scooter-card">
                <div class="scooter-header">
                    <div class="scooter-icon">🛴</div>
                    <h3>City Cruiser</h3>
                    <p>Perfect for daily commutes. Sleek design with maximum comfort.</p>
                    <div class="scooter-price">$15/hour</div>
                </div>
                <div class="scooter-footer">
                    <?php if ($isLoggedIn): ?>
                        <a href="dashboard.php" class="btn btn-primary btn-small">Rent Now</a>
                    <?php else: ?>
                        <a href="login.php" class="btn btn-primary btn-small">Login to Rent</a>
                    <?php endif; ?>
                </div>
            </div>
            
            <div class="scooter-card">
                <div class="scooter-header">
                    <div class="scooter-icon">🛴</div>
                    <h3>Speed Demon</h3>
                    <p>High-performance electric scooter for thrill-seekers.</p>
                    <div class="scooter-price">$25/hour</div>
                </div>
                <div class="scooter-footer">
                    <?php if ($isLoggedIn): ?>
                        <a href="dashboard.php" class="btn btn-primary btn-small">Rent Now</a>
                    <?php else: ?>
                        <a href="login.php" class="btn btn-primary btn-small">Login to Rent</a>
                    <?php endif; ?>
                </div>
            </div>
            
            <div class="scooter-card">
                <div class="scooter-header">
                    <div class="scooter-icon">🛴</div>
                    <h3>Eco Rider</h3>
                    <p>Environmentally friendly with extended battery life.</p>
                    <div class="scooter-price">$12/hour</div>
                </div>
                <div class="scooter-footer">
                    <?php if ($isLoggedIn): ?>
                        <a href="dashboard.php" class="btn btn-primary btn-small">Rent Now</a>
                    <?php else: ?>
                        <a href="login.php" class="btn btn-primary btn-small">Login to Rent</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </section>
    
    <footer class="footer">
        <p>© 2026 CYBERTECH SCOOT Unhackable Scooters. All rights reserved.</p>
        <p>Securing your rides with cutting-edge technology.</p>
    </footer>
</body>
</html>