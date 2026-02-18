# AIVidCatalog18 — План деплоя на VPS

> Путь на сервере: `/ssd/www/sameadult`
> MySQL: root / qweasd333123
> Репозиторий: https://github.com/alexevil1979/sameadult.git

---

## Шаг 1: Подготовка сервера

```bash
# Обновить пакеты
sudo apt update && sudo apt upgrade -y

# Установить необходимое ПО (если не установлено)
sudo apt install -y php8.2 php8.2-fpm php8.2-mysql php8.2-mbstring php8.2-xml \
    php8.2-curl php8.2-zip php8.2-gd php8.2-intl php8.2-bcmath \
    apache2 libapache2-mod-php8.2 mysql-server git unzip curl

# Включить модули Apache
sudo a2enmod rewrite headers ssl php8.2
sudo systemctl restart apache2

# Установить Composer
curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer
```

---

## Шаг 2: Клонировать проект

```bash
# Создать директорию если нет
sudo mkdir -p /ssd/www/sameadult
sudo chown -R www-data:www-data /ssd/www/sameadult

# Клонировать
cd /ssd/www
git clone https://github.com/alexevil1979/sameadult.git sameadult
cd sameadult

# Установить зависимости
composer install --no-dev --optimize-autoloader
```

---

## Шаг 3: Настроить MySQL

```bash
# Войти в MySQL
mysql -u root -p'qweasd333123'

# Выполнить:
CREATE DATABASE aividcatalog18 CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER 'aividcatalog'@'localhost' IDENTIFIED BY 'AiV1dC4t_s3cur3_2026!';
GRANT ALL PRIVILEGES ON aividcatalog18.* TO 'aividcatalog'@'localhost';
FLUSH PRIVILEGES;
EXIT;
```

---

## Шаг 4: Настроить .env

```bash
cd /ssd/www/sameadult
cp .env.example .env

# Отредактировать .env:
nano .env
```

**Заменить значения:**

```env
APP_NAME=AIVidCatalog18
APP_ENV=production
APP_DEBUG=false
APP_URL=https://ваш-домен.com

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=aividcatalog18
DB_USERNAME=aividcatalog
DB_PASSWORD=AiV1dC4t_s3cur3_2026!

SESSION_DRIVER=database
CACHE_STORE=database
QUEUE_CONNECTION=database

MAIL_MAILER=smtp
MAIL_HOST=your-smtp-host
MAIL_PORT=587
MAIL_USERNAME=your-email
MAIL_PASSWORD=your-password
MAIL_FROM_ADDRESS=noreply@ваш-домен.com

NOWPAYMENTS_API_KEY=ваш-реальный-ключ
NOWPAYMENTS_IPN_SECRET=ваш-реальный-секрет
NOWPAYMENTS_SANDBOX=false
```

---

## Шаг 5: Инициализация Laravel

```bash
cd /ssd/www/sameadult

# Сгенерировать ключ
php artisan key:generate

# Миграции + сиды
php artisan migrate --seed

# Кэширование для production
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Создать симлинк storage
php artisan storage:link

# Создать директории для видео
mkdir -p storage/app/videos
mkdir -p storage/app/public/thumbnails

# Установить права
sudo chown -R www-data:www-data /ssd/www/sameadult
sudo chmod -R 775 storage bootstrap/cache
```

---

## Шаг 6: Настроить Apache VirtualHost

```bash
sudo nano /etc/apache2/sites-available/sameadult.conf
```

**Содержимое:**

```apache
<VirtualHost *:80>
    ServerName ваш-домен.com
    ServerAlias www.ваш-домен.com
    DocumentRoot /ssd/www/sameadult/public

    <Directory /ssd/www/sameadult/public>
        AllowOverride All
        Require all granted
        Options -Indexes
    </Directory>

    # Логи
    ErrorLog ${APACHE_LOG_DIR}/sameadult-error.log
    CustomLog ${APACHE_LOG_DIR}/sameadult-access.log combined

    # Redirect to HTTPS (раскомментировать после установки SSL)
    # RewriteEngine On
    # RewriteCond %{HTTPS} off
    # RewriteRule ^ https://%{HTTP_HOST}%{REQUEST_URI} [L,R=301]
</VirtualHost>

# HTTPS (раскомментировать после certbot)
# <VirtualHost *:443>
#     ServerName ваш-домен.com
#     DocumentRoot /ssd/www/sameadult/public
#
#     <Directory /ssd/www/sameadult/public>
#         AllowOverride All
#         Require all granted
#         Options -Indexes
#     </Directory>
#
#     SSLEngine on
#     SSLCertificateFile /etc/letsencrypt/live/ваш-домен.com/fullchain.pem
#     SSLCertificateKeyFile /etc/letsencrypt/live/ваш-домен.com/privkey.pem
#
#     ErrorLog ${APACHE_LOG_DIR}/sameadult-ssl-error.log
#     CustomLog ${APACHE_LOG_DIR}/sameadult-ssl-access.log combined
# </VirtualHost>
```

```bash
# Активировать сайт
sudo a2ensite sameadult.conf
sudo a2dissite 000-default.conf  # опционально
sudo systemctl reload apache2
```

---

## Шаг 7: SSL сертификат (Let's Encrypt)

```bash
sudo apt install -y certbot python3-certbot-apache
sudo certbot --apache -d ваш-домен.com -d www.ваш-домен.com

# Автопродление
sudo crontab -e
# Добавить:
0 3 * * * certbot renew --quiet
```

После установки SSL — раскомментировать HTTPS VirtualHost и редирект в конфиге.

---

## Шаг 8: Cron для Laravel Scheduler

```bash
sudo crontab -e -u www-data
```

Добавить:

```
* * * * * cd /ssd/www/sameadult && php artisan schedule:run >> /dev/null 2>&1
```

---

## Шаг 9: Проверка

```bash
# 1. Проверить что сайт открывается
curl -I http://ваш-домен.com

# 2. Проверить маршруты
cd /ssd/www/sameadult
php artisan route:list

# 3. Проверить подключение к БД
php artisan tinker
>>> \App\Models\Plan::all();
>>> \App\Models\User::where('is_admin', true)->first();
>>> exit

# 4. Проверить права на storage
ls -la storage/app/videos/
ls -la storage/app/public/thumbnails/
```

---

## Шаг 10: Безопасность (ОБЯЗАТЕЛЬНО!)

```bash
# 1. Сменить пароль админа
cd /ssd/www/sameadult
php artisan tinker
>>> $u = \App\Models\User::where('email','admin@aividcatalog18.com')->first();
>>> $u->password = bcrypt('ВАШ-НОВЫЙ-СЛОЖНЫЙ-ПАРОЛЬ');
>>> $u->save();
>>> exit

# 2. Убедиться что .env не доступен из браузера
curl http://ваш-домен.com/.env
# Должен вернуть 403 Forbidden

# 3. Проверить .htaccess безопасность
curl http://ваш-домен.com/composer.json
# Должен вернуть 403

# 4. Настроить файрвол
sudo ufw allow 80
sudo ufw allow 443
sudo ufw allow 22
sudo ufw enable
```

---

## Шаг 11: Обновление с GitHub (последующие деплои)

```bash
cd /ssd/www/sameadult
git pull origin main
composer install --no-dev --optimize-autoloader
php artisan migrate --force
php artisan config:cache
php artisan route:cache
php artisan view:cache
sudo chown -R www-data:www-data /ssd/www/sameadult
```

---

## Быстрая шпаргалка (копировать целиком)

```bash
# === ПОЛНЫЙ ДЕПЛОЙ ОДНОЙ КОМАНДОЙ ===
cd /ssd/www && \
git clone https://github.com/alexevil1979/sameadult.git sameadult && \
cd sameadult && \
composer install --no-dev --optimize-autoloader && \
cp .env.example .env && \
php artisan key:generate && \
mysql -u root -p'qweasd333123' -e "CREATE DATABASE IF NOT EXISTS aividcatalog18 CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;" && \
sed -i 's/DB_USERNAME=root/DB_USERNAME=root/' .env && \
sed -i 's/DB_PASSWORD=/DB_PASSWORD=qweasd333123/' .env && \
sed -i 's/APP_ENV=local/APP_ENV=production/' .env && \
sed -i 's/APP_DEBUG=true/APP_DEBUG=false/' .env && \
php artisan migrate --seed --force && \
php artisan storage:link && \
mkdir -p storage/app/videos storage/app/public/thumbnails && \
php artisan config:cache && \
php artisan route:cache && \
php artisan view:cache && \
chown -R www-data:www-data /ssd/www/sameadult && \
chmod -R 775 storage bootstrap/cache && \
echo "=== ДЕПЛОЙ ЗАВЕРШЁН ==="
```

После этого: настроить Apache VirtualHost (шаг 6), SSL (шаг 7), сменить пароль админа (шаг 10).

---

## Тестовые данные

| Что | Значение |
|---|---|
| Admin email | admin@aividcatalog18.com |
| Admin пароль | admin12345 (**СМЕНИТЬ!**) |
| Plans | Basic $9.99/30d, Premium $19.99/30d |
| API health | GET /api/ping |
| Webhook | POST /webhook/nowpayments |
