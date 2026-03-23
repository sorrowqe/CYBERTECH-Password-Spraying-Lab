# 🛴 CYBERTECH SCOOT

<div align="center">

![CYBERTECH SCOOT](https://img.shields.io/badge/CYBERTECH-SCOOT-DC143C?style=for-the-badge&logo=security&logoColor=white)
![License](https://img.shields.io/badge/License-MIT-green?style=for-the-badge)
![Security Lab](https://img.shields.io/badge/Security-Lab-orange?style=for-the-badge)
![PHP](https://img.shields.io/badge/PHP-777BB4?style=for-the-badge&logo=php&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-4479A1?style=for-the-badge&logo=mysql&logoColor=white)

**A deliberately vulnerable web application built for cybersecurity training and password-cracking demonstrations.**

[Quick Start](#-quick-start) • [Installation](#-installation) • [Troubleshooting](#-troubleshooting)

</div>

---

> [!WARNING]
> **FOR EDUCATIONAL AND AUTHORIZED TESTING ONLY**
>
> This application is intentionally insecure. Do not deploy to production or expose to the internet.
> Use in isolated lab environments with proper authorization only.

---
<img width="2491" height="1247" alt="image" src="https://github.com/user-attachments/assets/eac09614-c267-4b76-a9a1-0d810a77cf82" />

<img width="2495" height="1250" alt="image" src="https://github.com/user-attachments/assets/c60458a3-c113-40a9-92af-b1d59e25cf89" />

<img width="2486" height="1227" alt="image" src="https://github.com/user-attachments/assets/bf48c9ba-36bc-4c4c-902e-948982309936" />

---

## **What is this?**

**CYBERTECH SCOOT** is a deliberately vulnerable scooter rental platform that looks and feels like a real-world application, but contains critical security flaws. It is designed to teach:

- How password cracking attacks work (including brute-force and password spraying)
- Why weak passwords are dangerous
- What happens when user accounts are compromised
- How to use common penetration testing tools
- Perfect for security labs, red team training, and hands-on cybersecurity practice.

---

## **Features**

### Application Features
- **Modern UI/UX** - Professional design with responsive layout
- **User Authentication** - Login and registration system
- **Dashboard** - User rental history and statistics
- **Profile Management** - Complete user profiles with sensitive data

### Intentional Vulnerabilities
- **MD5 Password Hashing** - Crackable in seconds
- **No Rate Limiting** - Spam login attempts all day
- **No Account Lockout** - Brute force away
- **Plaintext Sensitive Data** - SSN, credit cards stored unencrypted
- **No MFA** - Single-factor authentication only
- **Predictable Sessions** - Easy to hijack

---

## **Quick Start**

```bash
git clone https://github.com/sorrowqe/CYBERTECH-Password-Spraying-Lab.git
cd cybertech-scoot
sudo bash setup.sh
```

Access the site at `http://localhost` or `http://YOUR_IP`

**Test account:** `JackieCh4n` / `geminis`

---

## **Installation**
> [!CAUTION]
> Tested only on Ubuntu 22.04.

### Automated Setup (Recommended)

Run the setup script and it handles everything:

```bash
sudo bash setup.sh
```

### Manual Installation

If you prefer doing it manually:

**1. Install packages:**
```bash
sudo apt update
sudo apt install apache2 mysql-server php libapache2-mod-php php-mysql -y
```

**2. Create database:**
```bash
sudo mysql
```
```sql
CREATE DATABASE cbrtch_db;
CREATE USER 'cbrtch_user'@'localhost' IDENTIFIED BY 'cbrtch_pass123';
GRANT ALL PRIVILEGES ON cbrtch_db.* TO 'cbrtch_user'@'localhost';
FLUSH PRIVILEGES;
EXIT;
```

**3. Import schema:**
```bash
sudo mysql -u cbrtch_user -pcbrtch_pass123 cbrtch_db < database.sql
```

**4. Deploy files:**
```bash
sudo rm /var/www/html/index.html
sudo cp *.php *.js *.css /var/www/html/
sudo cp -r images /var/www/html/ 2>/dev/null || true
sudo chown -R www-data:www-data /var/www/html/
sudo chmod -R 755 /var/www/html/
```

**5. Restart services:**
```bash
sudo systemctl restart apache2 mysql
```

---

## **Test Account**

The database includes one pre-configured user:

| Field | Value |
|-------|-------|
| **Username** | `JackieCh4n` |
| **Password** | `geminis` |
| **Email** | JackieCh4n@cbrtch.com |

### User Profile Data

The test account contains realistic sensitive information:

-  **Full Name:** Peeter Meeter
-  **Phone:** +372 123 456
-  **Address:** Kopli 1, Tartu linn, Eesti
-  **Date of Birth:** May 5, 1996
-  **Driver's License:** BAK-355
-  **SSN:** 5060212345
-  **Credit Card:** 5412-2222-2222-2222
-  **Bank Account:** 2222222222
-  **Emergency Contact:** Minu mams - +372123456789
-  **Notes:** "Loodan, et see konto hakki ei saa. Kaardi kehtivus lopeb: 12/2026"

---

## **Database Structure**

**users table:**
- Stores username, email, password (MD5 hash)

**user_profiles table:**
- Stores all the sensitive personal data

View contents:
```bash
sudo mysql cbrtch_db -e "SELECT username, email FROM users;"
sudo mysql cbrtch_db -e "SELECT * FROM user_profiles;"
```

---

## **Troubleshooting**

**Can't connect to database?**
```bash
sudo systemctl restart mysql
sudo mysql -e "SHOW DATABASES;"
```

**Getting 404 errors?**
```bash
sudo systemctl restart apache2
ls -la /var/www/html/
```

**Permission errors?**
```bash
sudo chown -R www-data:www-data /var/www/html/
sudo chmod -R 755 /var/www/html/
```

**PHP code showing instead of executing?**
```bash
sudo apt install --reinstall libapache2-mod-php
sudo systemctl restart apache2
```

---

## **Cleanup**

To remove everything:

```bash
sudo mysql -e "DROP DATABASE IF EXISTS cbrtch_db;"
sudo mysql -e "DROP USER IF EXISTS 'cbrtch_user'@'localhost';"
sudo rm -f /var/www/html/*.php /var/www/html/*.js /var/www/html/*.css
echo "<h1>It works!</h1>" | sudo tee /var/www/html/index.html
```

---

## **License**

MIT License - see LICENSE file

---

<div align="center">

© 2026 CYBERTECH Eesti OÜ. All rights reserved. For demonstration purposes only.

**⚠️ Remember: Strong passwords + MFA = Actually unhackable! 🔒**

Made to showcase cybersecurity exploitation techniques at [Cybertech.global](https://cybertech.global/en/cybersecurity/). Stay legal, stay curious.

![GitHub stars](https://img.shields.io/github/stars/sorrowqe/CYBERTECH-Password-Spraying-Lab?style=social)

</div>
