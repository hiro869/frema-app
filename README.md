#  coachtechフリマ

##  環境構築

### 🐳 Docker ビルド

```bash
git clone https://github.com/hiro869/frema-app.git
cd frema-app
docker compose up -d --build
⚙️ Laravel 環境構築

docker compose exec app composer install
cp .env.example .env
.env の環境変数を以下のように変更：


APP_NAME=Frema
APP_ENV=local
APP_DEBUG=true
APP_TIMEZONE=Asia/Tokyo
APP_URL=http://localhost

DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=laravel
DB_USERNAME=laravel
DB_PASSWORD=laravel

FILESYSTEM_DISK=public

MAIL_MAILER=smtp
MAIL_HOST=mailhog
MAIL_PORT=1025
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS="no-reply@example.test"
MAIL_FROM_NAME="Frema"
その後に以下を実行してください：


docker compose exec app php artisan key:generate
docker compose exec app php artisan migrate --seed
docker compose exec app php artisan storage:link
docker compose exec app php artisan optimize:clear
🌐 開発環境URL
種類	URL
アプリケーション	http://localhost/
Mailhog（メール確認）	http://localhost:8025
phpMyAdmin（DB確認）	http://localhost:8080

🧩 使用技術（実行環境）
項目	内容
言語	PHP 8.3
フレームワーク	Laravel 11.x
Webサーバー	Nginx 1.25.3
データベース	MySQL 8.0.26
開発環境	Docker Compose
メール認証	Mailhog
決済	Stripe（テストモード）

## 🗺️ ER図

![ER図](./public/images/er_diagram.png)
