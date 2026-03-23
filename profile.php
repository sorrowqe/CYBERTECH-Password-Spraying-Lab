<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php?error=" . urlencode("Please login first"));
    exit();
}

require_once 'config.php';

$user_id = $_SESSION['user_id'];
$message = '';
$messageType = '';

// Handle profile update
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $full_name = $_POST['full_name'] ?? '';
    $phone = $_POST['phone'] ?? '';
    $address = $_POST['address'] ?? '';
    $city = $_POST['city'] ?? '';
    $country = $_POST['country'] ?? '';
    $date_of_birth = $_POST['date_of_birth'] ?? '';
    $drivers_license = $_POST['drivers_license'] ?? '';
    $ssn = $_POST['ssn'] ?? '';
    $credit_card = $_POST['credit_card'] ?? '';
    $bank_account = $_POST['bank_account'] ?? '';
    $emergency_contact = $_POST['emergency_contact'] ?? '';
    $notes = $_POST['notes'] ?? '';
    
    $conn = getDBConnection();
    
    // Check if profile exists
    $stmt = $conn->prepare("SELECT id FROM user_profiles WHERE user_id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        // Update existing profile
        $stmt = $conn->prepare("UPDATE user_profiles SET full_name=?, phone=?, address=?, city=?, country=?, date_of_birth=?, drivers_license=?, ssn=?, credit_card=?, bank_account=?, emergency_contact=?, notes=?, updated_at=NOW() WHERE user_id=?");
        $stmt->bind_param("ssssssssssssi", $full_name, $phone, $address, $city, $country, $date_of_birth, $drivers_license, $ssn, $credit_card, $bank_account, $emergency_contact, $notes, $user_id);
    } else {
        // Insert new profile
        $stmt = $conn->prepare("INSERT INTO user_profiles (user_id, full_name, phone, address, city, country, date_of_birth, drivers_license, ssn, credit_card, bank_account, emergency_contact, notes) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("issssssssssss", $user_id, $full_name, $phone, $address, $city, $country, $date_of_birth, $drivers_license, $ssn, $credit_card, $bank_account, $emergency_contact, $notes);
    }
    
    if ($stmt->execute()) {
        $message = "Profile updated successfully!";
        $messageType = "success";
    } else {
        $message = "Error updating profile.";
        $messageType = "error";
    }
    
    $stmt->close();
    $conn->close();
}

// Fetch user profile data
$conn = getDBConnection();
$stmt = $conn->prepare("SELECT u.username, u.email, p.* FROM users u LEFT JOIN user_profiles p ON u.id = p.user_id WHERE u.id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Profile - CYBERTECH SCOOT</title>
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
            max-width: 1000px;
            margin: 40px auto;
            padding: 0 30px;
        }
        
        .profile-card {
            background: var(--white);
            padding: 50px;
            border: 4px solid var(--black);
            position: relative;
        }
        
        .profile-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 6px;
            background: var(--red);
        }
        
        .profile-header {
            text-align: center;
            margin-bottom: 50px;
            padding-bottom: 25px;
            border-bottom: 3px solid var(--black);
        }
        
        .profile-header h1 {
            font-family: 'Bebas Neue', sans-serif;
            font-size: 42px;
            color: var(--black);
            letter-spacing: 2px;
            margin-bottom: 10px;
        }
        
        .profile-header .username {
            color: var(--red);
            font-size: 16px;
            font-weight: 600;
            letter-spacing: 1px;
        }
        
        .message {
            padding: 15px;
            margin-bottom: 30px;
            text-align: center;
            font-size: 13px;
            border: 2px solid;
        }
        
        .message.success {
            background: var(--off-white);
            color: var(--black);
            border-color: var(--black);
        }
        
        .message.error {
            background: var(--off-white);
            color: var(--dark-red);
            border-color: var(--red);
        }
        
        .form-section {
            margin-bottom: 40px;
        }
        
        .form-section h2 {
            font-family: 'Bebas Neue', sans-serif;
            font-size: 28px;
            color: var(--black);
            margin-bottom: 25px;
            padding-bottom: 12px;
            border-bottom: 3px solid var(--red);
            letter-spacing: 1px;
        }
        
        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 25px;
            margin-bottom: 25px;
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
        input[type="email"],
        input[type="tel"],
        input[type="date"],
        textarea {
            width: 100%;
            padding: 14px;
            border: 3px solid var(--black);
            background: var(--white);
            font-size: 14px;
            font-family: 'IBM Plex Mono', monospace;
            transition: all 0.2s;
        }
        
        input:focus,
        textarea:focus {
            outline: none;
            border-color: var(--red);
            background: var(--off-white);
        }
        
        textarea {
            min-height: 120px;
            resize: vertical;
        }
        
        .sensitive-field {
            position: relative;
        }
        
        .sensitive-field::after {
            content: "🔒";
            position: absolute;
            right: 14px;
            top: 42px;
            font-size: 16px;
        }
        
        .info-display {
            background: var(--off-white);
            padding: 18px;
            border-left: 4px solid var(--black);
        }
        
        .info-display strong {
            color: var(--black);
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 1px;
            display: block;
            margin-bottom: 8px;
        }
        
        .info-display p {
            margin: 0;
            color: var(--gray);
            font-size: 14px;
        }
        
        .button-group {
            display: flex;
            gap: 20px;
            margin-top: 40px;
        }
        
        button {
            flex: 1;
            padding: 16px;
            border: 3px solid var(--black);
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
            font-family: 'IBM Plex Mono', monospace;
            text-transform: uppercase;
            letter-spacing: 1.5px;
        }
        
        .save-btn {
            background: var(--black);
            color: var(--white);
        }
        
        .save-btn:hover {
            background: var(--red);
            border-color: var(--red);
        }
        
        .cancel-btn {
            background: transparent;
            color: var(--black);
        }
        
        .cancel-btn:hover {
            background: var(--black);
            color: var(--white);
        }

        @media (max-width: 768px) {
            .nav-links {
                gap: 15px;
                font-size: 11px;
            }
            
            .profile-card {
                padding: 30px 25px;
            }
            
            .profile-header h1 {
                font-size: 32px;
            }
            
            .form-row {
                grid-template-columns: 1fr;
            }
            
            .button-group {
                flex-direction: column;
            }
        }
    </style>
</head>
<body>
    <nav class="navbar">
        <div class="nav-container">
            <div class="logo">
                <!-- CBRTCH<span>.</span><!-->
                <a href="index.php" style="text-decoration: none;">
                <img src="images/cybertech-logo-main2.png" width="370" height="80" class="shield"></img>
                </a>
            </div>
            <div class="nav-links">
                <a href="index.php">Home</a>
                <a href="dashboard.php">Dashboard</a>
                <a href="profile.php">Profile</a>
                <a href="logout.php" class="btn-logout">Logout</a>
            </div>
        </div>
    </nav>
    
    <div class="container">
        <div class="profile-card">
            <div class="profile-header">
                <h1>My Secure Profile</h1>
                <div class="username">@<?php echo htmlspecialchars($user['username']); ?></div>
            </div>
            
            <?php if ($message): ?>
                <div class="message <?php echo $messageType; ?>">
                    <?php echo htmlspecialchars($message); ?>
                </div>
            <?php endif; ?>
            
            <form method="POST" action="profile.php">
                <div class="form-section">
                    <h2>🔑 Account Information</h2>
                    <div class="form-row">
                        <div class="info-display">
                            <strong>Username</strong>
                            <p><?php echo htmlspecialchars($user['username']); ?></p>
                        </div>
                        <div class="info-display">
                            <strong>Email</strong>
                            <p><?php echo htmlspecialchars($user['email']); ?></p>
                        </div>
                    </div>
                </div>
                
                <div class="form-section">
                    <h2>👤 Personal Information</h2>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="full_name">Full Legal Name</label>
                            <input type="text" id="full_name" name="full_name" value="<?php echo htmlspecialchars($user['full_name'] ?? ''); ?>" placeholder="John Michael Smith">
                        </div>
                        <div class="form-group">
                            <label for="date_of_birth">Date of Birth</label>
                            <input type="date" id="date_of_birth" name="date_of_birth" value="<?php echo htmlspecialchars($user['date_of_birth'] ?? ''); ?>">
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="phone">Phone Number</label>
                            <input type="tel" id="phone" name="phone" value="<?php echo htmlspecialchars($user['phone'] ?? ''); ?>" placeholder="+1 (555) 123-4567">
                        </div>
                        <div class="form-group">
                            <label for="emergency_contact">Emergency Contact</label>
                            <input type="text" id="emergency_contact" name="emergency_contact" value="<?php echo htmlspecialchars($user['emergency_contact'] ?? ''); ?>" placeholder="Jane Smith - (555) 987-6543">
                        </div>
                    </div>
                </div>
                
                <div class="form-section">
                    <h2>📍 Address Details</h2>
                    <div class="form-group">
                        <label for="address">Street Address</label>
                        <input type="text" id="address" name="address" value="<?php echo htmlspecialchars($user['address'] ?? ''); ?>" placeholder="742 Evergreen Terrace, Apt 5B">
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="city">City</label>
                            <input type="text" id="city" name="city" value="<?php echo htmlspecialchars($user['city'] ?? ''); ?>" placeholder="Springfield">
                        </div>
                        <div class="form-group">
                            <label for="country">Country</label>
                            <input type="text" id="country" name="country" value="<?php echo htmlspecialchars($user['country'] ?? ''); ?>" placeholder="United States">
                        </div>
                    </div>
                </div>
                
                <div class="form-section">
                    <h2>🪪 Government IDs & Documents</h2>
                    <div class="form-row">
                        <div class="form-group sensitive-field">
                            <label for="drivers_license">Driver's License Number</label>
                            <input type="text" id="drivers_license" name="drivers_license" value="<?php echo htmlspecialchars($user['drivers_license'] ?? ''); ?>" placeholder="D1234567">
                        </div>
                        <div class="form-group sensitive-field">
                            <label for="ssn">Social Security Number</label>
                            <input type="text" id="ssn" name="ssn" value="<?php echo htmlspecialchars($user['ssn'] ?? ''); ?>" placeholder="XXX-XX-XXXX">
                        </div>
                    </div>
                </div>
                
                <div class="form-section">
                    <h2>💳 Financial Information</h2>
                    <div class="form-row">
                        <div class="form-group sensitive-field">
                            <label for="credit_card">Credit Card Number</label>
                            <input type="text" id="credit_card" name="credit_card" value="<?php echo htmlspecialchars($user['credit_card'] ?? ''); ?>" placeholder="XXXX-XXXX-XXXX-XXXX">
                        </div>
                        <div class="form-group sensitive-field">
                            <label for="bank_account">Bank Account Number</label>
                            <input type="text" id="bank_account" name="bank_account" value="<?php echo htmlspecialchars($user['bank_account'] ?? ''); ?>" placeholder="XXXXXXXXXXXX">
                        </div>
                    </div>
                </div>
                
                <div class="form-section">
                    <h2>📝 Additional Notes</h2>
                    <div class="form-group">
                        <label for="notes">Private Notes (Security Questions, Passwords, etc.)</label>
                        <textarea id="notes" name="notes" placeholder="Mother's maiden name, favorite pet, password hints, crypto wallet info, etc."><?php echo htmlspecialchars($user['notes'] ?? ''); ?></textarea>
                    </div>
                </div>
                
                <div class="button-group">
                    <button type="submit" class="save-btn">💾 Save Profile</button>
                    <button type="button" class="cancel-btn" onclick="window.location.href='dashboard.php'">Cancel</button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>