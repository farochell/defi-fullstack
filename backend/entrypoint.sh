#!/bin/bash
set -e

echo "🚀 ENTRYPOINT STARTED"

cd /var/www/html

# Composer
if [ -f "composer.json" ]; then
    echo "📦 Installing Composer dependencies..."
    COMPOSER_ALLOW_SUPERUSER=1 composer install --no-interaction --optimize-autoloader --no-scripts || true
    echo "🔧 Running Symfony auto-scripts..."
    COMPOSER_ALLOW_SUPERUSER=1 composer run-script --no-interaction auto-scripts || true
fi

# Créer répertoires var
mkdir -p var/cache var/log
chown -R www-data:www-data var/ 2>/dev/null || true
chmod -R 775 var/ 2>/dev/null || true

# -----------------------------
# 🔹 Lancer PHP-FPM immédiatement
# -----------------------------
echo "🎯 Starting PHP-FPM..."
php-fpm

# -----------------------------
# 🔹 Gestion BDD en arrière-plan
# -----------------------------
(
echo "🛠 Waiting for MySQL..."
DB_HOST=$(php -r "echo parse_url(getenv('DATABASE_URL'), PHP_URL_HOST);")
DB_NAME=$(php -r "echo ltrim(parse_url(getenv('DATABASE_URL'), PHP_URL_PATH), '/');")
DB_USER=$(php -r "echo parse_url(getenv('DATABASE_URL'), PHP_URL_USER);")
DB_PASS=$(php -r "echo parse_url(getenv('DATABASE_URL'), PHP_URL_PASS);")

# Utiliser mariadb-admin au lieu de mysqladmin pour éviter le warning
until mariadb-admin ping -h "$DB_HOST" -u "$DB_USER" -p"$DB_PASS" --silent; do
    echo "⏳ Waiting for MySQL at $DB_HOST..."
    sleep 2
done

# Créer base si inexistante
DB_EXISTS=$(mysql -h "$DB_HOST" -u "$DB_USER" -p"$DB_PASS" -e "SHOW DATABASES LIKE '$DB_NAME';" | grep "$DB_NAME" || true)
if [ -z "$DB_EXISTS" ]; then
    echo "🛠 Creating database $DB_NAME..."
    php bin/console doctrine:database:create || true
fi

# Migrations ou schema update
MIGRATIONS_COUNT=$(ls -1 migrations/* 2>/dev/null | wc -l)
if [ "$MIGRATIONS_COUNT" -gt 0 ]; then
    echo "🛠 Applying Doctrine migrations..."
    php bin/console doctrine:migrations:migrate --no-interaction || true
else
    echo "🛠 No migrations found, updating schema directly..."
    php bin/console doctrine:schema:update --force || true
fi

echo "✅ Database setup done"
) &

# Garder le container actif
wait
