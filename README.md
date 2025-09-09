# coachtechフリマ

## 環境構築
1. リポジトリを取得
git clone https://github.com/hiro869/frema-app.git cd frema-app
2. Docker コンテナ起動
docker compose up -d --build
3. laravel セットアップ
docker compose exec app composer install
cp src/.env.example src/.env
docker compose exec app php artisan key:generate

.env のDB設定は以下にしてください。
DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=laravel
DB_USERNAME=laravel
DB_PASSWORD=laravel

4. データベースの準備
docker compose exec app php artisan migrate
docker compose exec app php artisan db:seed

5. ストレージリンク作成
docker compose exec app php artisan storage:link


## 使用技術
PHP: 8.3 (FPM)

Laravel: 11.x

MySQL: 8.0.26

nginx: latest (alpine)

Docker / Docker Compose
