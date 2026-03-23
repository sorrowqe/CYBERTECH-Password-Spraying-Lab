#!/bin/bash

set -e

# Colors
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
CYAN='\033[0;36m'
MAGENTA='\033[0;35m'
NC='\033[0m'

clear

# ASCII Art Banner
echo -e "${CYAN}"
cat << "EOF"
   ▄████████ ▄██   ▄   ▀█████████▄     ▄████████    ▄████████     ███        ▄████████  ▄████████    ▄█    █▄
  ███    ███ ███   ██▄   ███    ███   ███    ███   ███    ███ ▀█████████▄   ███    ███ ███    ███   ███    ███
  ███    █▀  ███▄▄▄███   ███    ███   ███    █▀    ███    ███    ▀███▀▀██   ███    █▀  ███    █▀    ███    ███
  ███        ▀▀▀▀▀▀███  ▄███▄▄▄██▀   ▄███▄▄▄      ▄███▄▄▄▄██▀     ███   ▀  ▄███▄▄▄     ███         ▄███▄▄▄▄███▄▄
  ███        ▄██   ███ ▀▀███▀▀▀██▄  ▀▀███▀▀▀     ▀▀███▀▀▀▀▀       ███     ▀▀███▀▀▀     ███        ▀▀███▀▀▀▀███▀
  ███    █▄  ███   ███   ███    ██▄   ███    █▄  ▀███████████     ███       ███    █▄  ███    █▄    ███    ███
  ███    ███ ███   ███   ███    ███   ███    ███   ███    ███     ███       ███    ███ ███    ███   ███    ███
  ████████▀   ▀█████▀  ▄█████████▀    ██████████   ███    ███    ▄████▀     ██████████ ████████▀    ███    █▀
                                                    ███    ███
EOF
echo -e "${NC}"

echo -e "${MAGENTA}                            -= CYBERTECH.GLOBAL =-${NC}"
echo -e "${RED}                        [INTENTIONALLY VULNERABLE LAB ENVIRONMENT]${NC}"
echo ""
echo -e "${YELLOW}⚠  WARNING: FOR EDUCATIONAL USE ONLY${NC}"
echo ""

# Check root
if [ "$EUID" -ne 0 ]; then
    echo -e "${RED}[!] This script must be run as root${NC}"
    echo "    Please run: sudo bash setup.sh"
    exit 1
fi

# Detect OS
if grep -qi "kali" /etc/os-release 2>/dev/null; then
    OS="kali"
    DB_CMD="mariadb"
    DB_PKG="mariadb-server"
    DB_SERVICE="mariadb"
else
    OS="ubuntu"
    DB_CMD="mysql"
    DB_PKG="mysql-server"
    DB_SERVICE="mysql"
fi

echo -e "${CYAN}[*]${NC} Detected OS: $OS"
echo ""

# Config
DB_NAME="cbrtch_db"
DB_USER="cbrtch_user"
DB_PASS="cbrtch_pass123"
WEB_DIR="/var/www/html"

echo -e "${CYAN}[*]${NC} Starting installation..."
echo ""

# Install packages
echo -e "${CYAN}[1/9]${NC} Updating package lists..."
apt update -qq 2>&1 | grep -v "^(" || true

echo -e "${CYAN}[2/9]${NC} Installing Apache..."
DEBIAN_FRONTEND=noninteractive apt install -y -qq apache2 > /dev/null 2>&1

echo -e "${CYAN}[3/9]${NC} Installing Database ($DB_PKG)..."
DEBIAN_FRONTEND=noninteractive apt install -y -qq $DB_PKG > /dev/null 2>&1

echo -e "${CYAN}[4/9]${NC} Installing PHP..."
DEBIAN_FRONTEND=noninteractive apt install -y -qq php libapache2-mod-php php-mysql > /dev/null 2>&1

echo -e "${CYAN}[5/9]${NC} Starting services..."
systemctl start apache2 2>&1 | grep -v "^(" || true
systemctl enable apache2 > /dev/null 2>&1
systemctl start $DB_SERVICE 2>&1 | grep -v "^(" || true
systemctl enable $DB_SERVICE > /dev/null 2>&1

# Wait for DB to be ready
sleep 2

echo -e "${CYAN}[6/9]${NC} Creating database..."
$DB_CMD -u root <<MYSQL_SCRIPT 2>&1 | grep -v "^(" || true
CREATE DATABASE IF NOT EXISTS $DB_NAME;
CREATE USER IF NOT EXISTS '$DB_USER'@'localhost' IDENTIFIED BY '$DB_PASS';
GRANT ALL PRIVILEGES ON $DB_NAME.* TO '$DB_USER'@'localhost';
FLUSH PRIVILEGES;
MYSQL_SCRIPT

echo -e "${CYAN}[7/9]${NC} Importing database schema..."
if [ ! -f "database.sql" ]; then
    echo -e "${RED}[!] Error: database.sql not found${NC}"
    exit 1
fi
$DB_CMD -u $DB_USER -p$DB_PASS $DB_NAME < database.sql 2>&1 | grep -v "^(" || true

echo -e "${CYAN}[8/9]${NC} Deploying application files..."
[ -f "$WEB_DIR/index.html" ] && mv "$WEB_DIR/index.html" "$WEB_DIR/index.html.backup" 2>/dev/null || true
rm -f $WEB_DIR/*.php 2>/dev/null || true
cp *.php $WEB_DIR/ 2>/dev/null || true
cp *.js $WEB_DIR/ 2>/dev/null || true
cp *.css $WEB_DIR/ 2>/dev/null || true
[ -d "fonts" ] && cp -r fonts $WEB_DIR/ 2>/dev/null || true
[ -d "images" ] && cp -r images $WEB_DIR/ 2>/dev/null || true
[ -d "bootstrap-3.3.6-dist" ] && cp -r bootstrap-3.3.6-dist $WEB_DIR/ 2>/dev/null || true
chown -R www-data:www-data $WEB_DIR/
chmod -R 755 $WEB_DIR/

echo -e "${CYAN}[9/9]${NC} Configuring Apache..."
a2enmod rewrite > /dev/null 2>&1
systemctl restart apache2 2>&1 | grep -v "^(" || true

IP_ADDR=$(hostname -I | awk '{print $1}')

echo ""
echo -e "${GREEN}╔════════════════════════════════════════════════════════════════╗${NC}"
echo -e "${GREEN}║                    INSTALLATION COMPLETE                       ║${NC}"
echo -e "${GREEN}╚════════════════════════════════════════════════════════════════╝${NC}"
echo ""
echo -e "${CYAN}ACCESS APPLICATION:${NC}"
echo "  → http://localhost"
echo "  → http://$IP_ADDR"
echo ""
echo -e "${CYAN}DATABASE CREDENTIALS:${NC}"
echo "  Database: $DB_NAME"
echo "  User:     $DB_USER"
echo "  Password: $DB_PASS"
echo ""
echo -e "${CYAN}TEST ACCOUNT:${NC}"
echo "  Username: JackieCh4n"
echo "  Password: geminis"
echo ""
echo -e "${CYAN}QUICK COMMANDS:${NC}"
echo "  View users:    sudo $DB_CMD $DB_NAME -e 'SELECT * FROM users;'"
echo "  View profiles: sudo $DB_CMD $DB_NAME -e 'SELECT * FROM user_profiles;'"
echo ""
echo -e "${RED}⚠  SECURITY REMINDERS:${NC}"
echo "  • MD5 password hashing (intentionally weak)"
echo "  • No rate limiting or account lockout"
echo "  • Contains sensitive personal data"
echo "  • FOR LAB ENVIRONMENTS ONLY"
echo ""
echo -e "${GREEN}Happy hacking! (legally, of course)${NC}"
echo ""
