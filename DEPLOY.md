# 心靈測驗平台 — GCP 部署手冊

> 預估總時間：30–45 分鐘
> 難度：中等（只需複製貼上指令）

---

## 準備事項（開始前確認）

- [ ] 已有 Google 帳號
- [ ] 已有域名（若無可先用 IP 訪問）
- [ ] 已將最新程式碼 push 到 GitHub（`git push origin main`）
- [ ] 手邊準備好：OpenAI API Key、Stripe Key（若要上線收款）

---

## 第一部分：GCP Console 網頁操作

### 步驟 1 — 建立 GCP 專案

1. 前往 https://console.cloud.google.com
2. 點擊頂部的專案下拉選單 → **New Project**
3. 填寫：
   - Project name: `quiz-app`
   - 點擊 **Create**
4. 等待建立完成（約 30 秒）

---

### 步驟 2 — 建立 VM 虛擬機

1. 左側選單 → **Compute Engine** → **VM instances**
2. 若出現「Enable Compute Engine API」→ 點擊 **Enable**（需等待 1-2 分鐘）
3. 點擊 **Create Instance**，填寫以下設定：

| 欄位 | 值 |
|---|---|
| Name | `quiz-server` |
| Region | `asia-east1 (Taiwan)` |
| Zone | `asia-east1-b` |
| Machine type | `e2-small`（建議，約 $13/月）或 `e2-micro`（免費方案） |
| Boot disk OS | Ubuntu 22.04 LTS |
| Boot disk size | 20 GB |
| Firewall | 勾選 **Allow HTTP traffic** 和 **Allow HTTPS traffic** |

4. 點擊 **Create**，等待 VM 建立（約 1 分鐘）

---

### 步驟 3 — 保留靜態 IP

> 如果不保留靜態 IP，VM 重啟後 IP 會改變，域名設定會失效。

1. 左側選單 → **VPC Network** → **External IP addresses**
2. 找到你的 VM（`quiz-server`），Type 欄位顯示 `Ephemeral`
3. 點擊 `Ephemeral` → 選擇 **Static**
4. Name: `quiz-ip` → 點擊 **Reserve**
5. **記下這個 IP 位址**（之後要用）

---

### 步驟 4 — 設定域名 DNS（若有域名）

到你的域名服務商（GoDaddy / Cloudflare 等），新增：

| 類型 | 名稱 | 值 |
|---|---|---|
| A | `@` 或 `quiz` | `步驟 3 記下的 IP` |
| A | `www` | `步驟 3 記下的 IP` |

> DNS 生效時間：5 分鐘到 24 小時不等

---

## 第二部分：SSH 進入 VM 執行指令

### 步驟 5 — SSH 連線

在 GCP Console → VM instances 頁面，點擊 VM 右邊的 **SSH** 按鈕，會開啟瀏覽器內的終端機視窗。

---

### 步驟 6 — 安裝伺服器環境

```bash
# 切換到 root
sudo su

# 下載並執行安裝腳本（先不要跑，繼續看下一步）
# 我們先直接貼上指令
```

**複製以下整段指令，貼到 SSH 視窗執行：**

```bash
sudo apt update && sudo apt upgrade -y && \
sudo apt install -y git curl wget unzip software-properties-common certbot python3-certbot-nginx ufw && \
sudo add-apt-repository ppa:ondrej/php -y && \
sudo apt update && \
sudo apt install -y nginx php8.4-fpm php8.4-cli php8.4-mysql php8.4-mbstring php8.4-xml php8.4-curl php8.4-zip php8.4-gd php8.4-intl php8.4-bcmath php8.4-tokenizer php8.4-fileinfo mysql-server && \
sudo systemctl enable nginx php8.4-fpm mysql && \
curl -sS https://getcomposer.org/installer -o /tmp/composer-setup.php && \
sudo php /tmp/composer-setup.php --install-dir=/usr/local/bin --filename=composer && \
sudo rm /tmp/composer-setup.php && \
sudo ufw allow OpenSSH && \
sudo ufw allow 'Nginx Full' && \
sudo ufw --force enable && \
echo "安裝完成！"
```

等待完成（約 5-10 分鐘），看到「安裝完成！」即可繼續。

---

### 步驟 7 — 建立 MySQL 資料庫

```bash
# 進入 MySQL（第一次不需要密碼）
sudo mysql
```

進入 MySQL 後，貼上以下指令（**把 YOUR_STRONG_PASSWORD 換成你自訂的密碼，記下來**）：

```sql
CREATE DATABASE quiz CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER 'quiz_user'@'localhost' IDENTIFIED BY 'YOUR_STRONG_PASSWORD';
GRANT ALL PRIVILEGES ON quiz.* TO 'quiz_user'@'localhost';
FLUSH PRIVILEGES;
EXIT;
```

---

### 步驟 8 — 拉取程式碼

```bash
cd /var/www
sudo git clone https://github.com/jayzeng0711/quiz.git
sudo chown -R www-data:www-data /var/www/quiz
cd /var/www/quiz
```

---

### 步驟 9 — 設定 .env 正式環境

```bash
sudo cp .env.example .env
sudo nano .env
```

**在 nano 編輯器中，修改以下欄位**（用 Ctrl+W 搜尋）：

```env
APP_NAME="心靈測驗"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://YOUR_DOMAIN         # 換成你的域名，或先填 http://你的IP

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=quiz
DB_USERNAME=quiz_user
DB_PASSWORD=YOUR_STRONG_PASSWORD    # 步驟 7 設定的密碼

QUEUE_CONNECTION=database           # 正式環境用 database

MAIL_MAILER=smtp                    # 換成真實 SMTP（見下方說明）
MAIL_FROM_ADDRESS="noreply@YOUR_DOMAIN"
MAIL_FROM_NAME="心靈測驗"

PAYMENT_PROVIDER=stripe             # 或 ecpay
STRIPE_KEY=pk_live_xxx              # 你的 Stripe 正式金鑰
STRIPE_SECRET=sk_live_xxx
STRIPE_WEBHOOK_SECRET=whsec_xxx

OPENAI_API_KEY=sk-proj-xxx          # 你的 OpenAI API Key
OPENAI_MODEL=gpt-4o-mini
```

> **Mail 設定建議（擇一）：**
> - **Resend**（最簡單，免費方案每月 3000 封）：`MAIL_MAILER=resend`，`RESEND_KEY=re_xxx`
> - **Mailgun**：`MAIL_MAILER=mailgun`，填入 domain 和 secret
> - **Gmail**：`MAIL_MAILER=smtp`，`MAIL_HOST=smtp.gmail.com`，`MAIL_PORT=587`，填入 app password

修改完成後：按 **Ctrl+O** 儲存，**Ctrl+X** 離開。

---

### 步驟 10 — 初始化 Laravel

```bash
cd /var/www/quiz

# 安裝套件（不含開發套件）
sudo -u www-data composer install --no-dev --optimize-autoloader

# 生成 APP_KEY
sudo -u www-data php artisan key:generate

# 執行資料庫 migration（建立所有資料表）
sudo -u www-data php artisan migrate --force

# 建立初始資料（測驗題目、結果類型等）
sudo -u www-data php artisan db:seed --force

# 建立快取
sudo -u www-data php artisan config:cache
sudo -u www-data php artisan route:cache
sudo -u www-data php artisan view:cache

# 設定目錄權限
sudo chown -R www-data:www-data /var/www/quiz
sudo chmod -R 755 /var/www/quiz/storage
sudo chmod -R 755 /var/www/quiz/bootstrap/cache
```

---

### 步驟 11 — 設定 Nginx

```bash
# 複製設定檔
sudo cp /var/www/quiz/deploy/nginx.conf /etc/nginx/sites-available/quiz

# 修改設定檔中的 YOUR_DOMAIN（換成你的域名）
sudo nano /etc/nginx/sites-available/quiz
# 把所有 YOUR_DOMAIN 改成你的實際域名（例如 quiz.example.com）
# Ctrl+\ 可以搜尋取代：YOUR_DOMAIN → quiz.example.com

# 啟用設定
sudo ln -s /etc/nginx/sites-available/quiz /etc/nginx/sites-enabled/
sudo rm -f /etc/nginx/sites-enabled/default   # 移除預設設定

# 測試設定
sudo nginx -t

# 重載 Nginx
sudo systemctl reload nginx
```

此時用瀏覽器訪問 `http://你的IP`，應該可以看到網站首頁。

---

### 步驟 12 — 安裝 SSL 憑證（HTTPS）

> 需要先完成步驟 4 的 DNS 設定，且 DNS 已生效。

```bash
# 把 YOUR_DOMAIN 換成你的域名
sudo certbot --nginx -d YOUR_DOMAIN -d www.YOUR_DOMAIN

# 依照提示輸入：
# - 電子郵件（用於接收憑證到期通知）
# - 同意條款：A
# - 是否訂閱 EFF 電子報：N（可以選 N）
```

certbot 會自動修改 Nginx 設定，加入 SSL。

**設定自動更新憑證：**

```bash
# 測試自動更新
sudo certbot renew --dry-run

# 加入 cron 自動更新（每天中午12點嘗試更新）
(crontab -l 2>/dev/null; echo "0 12 * * * /usr/bin/certbot renew --quiet") | crontab -
```

---

### 步驟 13 — 啟動 Queue Worker

```bash
# 安裝 systemd 服務
sudo cp /var/www/quiz/deploy/quiz-queue.service /etc/systemd/system/

# 載入並啟動
sudo systemctl daemon-reload
sudo systemctl enable quiz-queue
sudo systemctl start quiz-queue

# 確認是否正常運行（應看到 Active: active (running)）
sudo systemctl status quiz-queue
```

---

### 步驟 14 — 設定排程器（Cron）

```bash
# 編輯 www-data 的 crontab
sudo crontab -u www-data -e

# 在檔案末尾加入這一行
* * * * * cd /var/www/quiz && php artisan schedule:run >> /dev/null 2>&1
```

---

### 步驟 15 — 建立 Admin 帳號

```bash
cd /var/www/quiz
sudo -u www-data php artisan tinker --execute="
App\Models\User::firstOrCreate(
    ['email' => 'admin@yourdomain.com'],
    ['name' => 'Admin', 'password' => bcrypt('your-admin-password'), 'email_verified_at' => now()]
);
echo 'Admin created!';
"
```

訪問 `https://YOUR_DOMAIN/admin`，用上面的帳密登入後台。

---

## 第三部分：每次更新程式碼

當你在本地修改了程式碼，push 到 GitHub 後，在 VM 上執行：

```bash
cd /var/www/quiz
sudo git pull origin main
sudo bash deploy/after-pull.sh
```

---

## 常用維護指令

```bash
# 查看錯誤日誌
sudo tail -f /var/log/nginx/quiz_error.log
sudo tail -f /var/www/quiz/storage/logs/laravel.log

# 重啟所有服務
sudo systemctl restart nginx php8.4-fpm mysql quiz-queue

# Queue Worker 狀態
sudo systemctl status quiz-queue
sudo journalctl -u quiz-queue -f

# 補跑缺少的 AI 報告
cd /var/www/quiz && sudo -u www-data php artisan quiz:fill-ai

# 資料庫備份
mysqldump -u quiz_user -p quiz > ~/backup_$(date +%Y%m%d).sql
```

---

## 常見問題排解

| 問題 | 解決方式 |
|---|---|
| 500 Internal Server Error | 查看 `storage/logs/laravel.log` |
| 502 Bad Gateway | `sudo systemctl restart php8.4-fpm` |
| 網站無法訪問 | `sudo systemctl status nginx` 確認 Nginx 正在運行 |
| SSL 憑證錯誤 | 確認 DNS 已生效，再執行 `sudo certbot --nginx ...` |
| Queue 不處理 | `sudo systemctl restart quiz-queue` |
| Permission denied | `sudo chown -R www-data:www-data /var/www/quiz` |

---

## 費用估算

| 項目 | 每月費用 |
|---|---|
| e2-small VM (asia-east1) | ~$13 USD |
| 20GB 磁碟 | ~$1 USD |
| 靜態 IP（VM 執行時免費） | $0 |
| 網路流量（前 1GB 免費） | <$1 USD |
| **合計** | **約 $14–15 USD / 月** |

> 如果選 e2-micro（US region），VM 本身免費，但效能較低。
