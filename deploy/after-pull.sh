#!/usr/bin/env bash
# ============================================================
# deploy/after-pull.sh
# 每次 git pull 更新程式碼後執行此腳本
#
# 使用方式（在 VM 上 /var/www/quiz 目錄執行）：
#   sudo bash deploy/after-pull.sh
# ============================================================
set -e

APP_DIR="/var/www/quiz"
echo "=============================="
echo "  部署更新中..."
echo "=============================="

cd "$APP_DIR"

# 暫時開啟維護模式
echo "[1/7] 開啟維護模式..."
sudo -u www-data php artisan down --render="errors::503" || true

# 安裝/更新 Composer 套件（正式環境，不含 dev）
echo "[2/7] 安裝 Composer 套件..."
sudo -u www-data composer install \
    --no-dev \
    --optimize-autoloader \
    --no-interaction

# 執行資料庫 migration
echo "[3/7] 執行資料庫 migration..."
sudo -u www-data php artisan migrate --force

# 清除並重建快取
echo "[4/7] 重建快取..."
sudo -u www-data php artisan config:clear
sudo -u www-data php artisan route:clear
sudo -u www-data php artisan view:clear
sudo -u www-data php artisan config:cache
sudo -u www-data php artisan route:cache
sudo -u www-data php artisan view:cache

# 設定目錄權限
echo "[5/7] 設定目錄權限..."
chown -R www-data:www-data "$APP_DIR"
chmod -R 755 "$APP_DIR/storage"
chmod -R 755 "$APP_DIR/bootstrap/cache"

# 重啟 Queue Worker
echo "[6/7] 重啟 Queue Worker..."
systemctl restart quiz-queue || true

# 關閉維護模式
echo "[7/7] 關閉維護模式..."
sudo -u www-data php artisan up

echo ""
echo "=============================="
echo "  更新完成！"
echo "=============================="
