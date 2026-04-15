#!/usr/bin/env bash
# ============================================================
# deploy/setup-server.sh
# 在全新的 Ubuntu 22.04 VM 上一鍵安裝所有需要的環境
#
# 使用方式（SSH 進入 VM 後）：
#   chmod +x setup-server.sh
#   sudo bash setup-server.sh
#
# 執行時間：約 5-10 分鐘
# ============================================================
set -e

echo "=============================="
echo "  Quiz App 伺服器環境安裝"
echo "=============================="

# ── 1. 系統更新 ────────────────────────────────────────────
echo "[1/8] 更新系統套件..."
apt update && apt upgrade -y

# ── 2. 安裝基本工具 ────────────────────────────────────────
echo "[2/8] 安裝基本工具..."
apt install -y git curl wget unzip software-properties-common \
    certbot python3-certbot-nginx ufw

# ── 3. 安裝 Nginx ──────────────────────────────────────────
echo "[3/8] 安裝 Nginx..."
apt install -y nginx
systemctl enable nginx
systemctl start nginx

# ── 4. 安裝 PHP 8.4 ───────────────────────────────────────
echo "[4/8] 安裝 PHP 8.4..."
add-apt-repository ppa:ondrej/php -y
apt update
apt install -y \
    php8.4-fpm \
    php8.4-cli \
    php8.4-mysql \
    php8.4-mbstring \
    php8.4-xml \
    php8.4-curl \
    php8.4-zip \
    php8.4-gd \
    php8.4-intl \
    php8.4-bcmath \
    php8.4-tokenizer \
    php8.4-fileinfo

# 調整 PHP-FPM 設定
sed -i 's/;cgi.fix_pathinfo=1/cgi.fix_pathinfo=0/' /etc/php/8.4/fpm/php.ini
sed -i 's/upload_max_filesize = 2M/upload_max_filesize = 20M/' /etc/php/8.4/fpm/php.ini
sed -i 's/post_max_size = 8M/post_max_size = 20M/' /etc/php/8.4/fpm/php.ini
sed -i 's/max_execution_time = 30/max_execution_time = 120/' /etc/php/8.4/fpm/php.ini
sed -i 's/memory_limit = 128M/memory_limit = 256M/' /etc/php/8.4/fpm/php.ini

systemctl enable php8.4-fpm
systemctl restart php8.4-fpm

# ── 5. 安裝 MySQL 8 ────────────────────────────────────────
echo "[5/8] 安裝 MySQL 8..."
apt install -y mysql-server
systemctl enable mysql
systemctl start mysql

# ── 6. 安裝 Composer ──────────────────────────────────────
echo "[6/8] 安裝 Composer..."
curl -sS https://getcomposer.org/installer -o /tmp/composer-setup.php
php /tmp/composer-setup.php --install-dir=/usr/local/bin --filename=composer
rm /tmp/composer-setup.php

# ── 7. 設定防火牆 ─────────────────────────────────────────
echo "[7/8] 設定防火牆..."
ufw allow OpenSSH
ufw allow 'Nginx Full'
ufw --force enable

# ── 8. 建立網站目錄 ────────────────────────────────────────
echo "[8/8] 建立網站目錄..."
mkdir -p /var/www/quiz
chown -R www-data:www-data /var/www

echo ""
echo "=============================="
echo "  安裝完成！"
echo "=============================="
echo ""
echo "下一步：執行 setup-mysql.sh 建立資料庫"
echo "  bash /root/setup-mysql.sh"
