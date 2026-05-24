#!/usr/bin/env bash
# RPM Hotspot Manager — Raspberry Pi install script
# Run as root: sudo bash install.sh

set -e

DEPLOY_DIR="/var/www/rpm"
DB_NAME="rpm_hotspot"
DB_USER="rpm_user"
DB_PASS="$(openssl rand -base64 16)"

echo "=== RPM Hotspot Manager Installer ==="
echo

# ── System packages ───────────────────────────────────────────────
apt-get update -qq
apt-get install -y apache2 php php-pdo php-pdo-mysql mariadb-server \
    hostapd dnsmasq iptables-persistent curl

# ── Apache mods ───────────────────────────────────────────────────
a2enmod rewrite
systemctl enable apache2 --now

# ── Copy application ──────────────────────────────────────────────
mkdir -p "$DEPLOY_DIR"
cp -r . "$DEPLOY_DIR"
chown -R www-data:www-data "$DEPLOY_DIR"
chmod -R 750 "$DEPLOY_DIR"

# Apache vhost
cp apache/rpm.conf /etc/apache2/sites-available/rpm.conf
a2ensite rpm.conf
a2dissite 000-default.conf
systemctl reload apache2

# ── Database ──────────────────────────────────────────────────────
systemctl enable mariadb --now
mysql -e "CREATE DATABASE IF NOT EXISTS \`${DB_NAME}\` CHARACTER SET utf8mb4;"
mysql -e "CREATE USER IF NOT EXISTS '${DB_USER}'@'localhost' IDENTIFIED BY '${DB_PASS}';"
mysql -e "GRANT ALL ON \`${DB_NAME}\`.* TO '${DB_USER}'@'localhost';"
mysql -e "FLUSH PRIVILEGES;"
mysql "$DB_NAME" < database/schema.sql

# Write DB config
sed -i "s/define('DB_USER', 'rpm_user')/define('DB_USER', '${DB_USER}')/" "$DEPLOY_DIR/app/config/database.php"
sed -i "s/define('DB_PASS', 'change_me')/define('DB_PASS', '${DB_PASS}')/" "$DEPLOY_DIR/app/config/database.php"

# ── Hosts entry ───────────────────────────────────────────────────
grep -q "rpm.local" /etc/hosts || echo "127.0.0.1 rpm.local" >> /etc/hosts

echo
echo "=== Install complete ==="
echo "  URL  : http://rpm.local"
echo "  DB   : ${DB_NAME}"
echo "  User : ${DB_USER}"
echo "  Pass : ${DB_PASS}"
echo
echo "Save the DB password above — it will not be shown again."
