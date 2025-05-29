#!/bin/sh
set -e

echo "Waiting for MySQL database to be ready..."
# Chờ database khởi động (tối đa 60s)
max_tries=30
counter=0
until mysql -h $DB_HOST -u $DB_USERNAME -p$DB_PASSWORD -e "SHOW DATABASES;" > /dev/null 2>&1; do
    echo "Waiting for MySQL to be available... ($counter/$max_tries)"
    counter=$((counter+1))
    if [ $counter -gt $max_tries ]; then
        echo "Error: Failed to connect to MySQL after $max_tries attempts!"
        exit 1
    fi
    sleep 2
done
echo "MySQL database is ready!"

# Chạy migrations
echo "Running migrations..."
php artisan migrate --force

# Tối ưu hóa Laravel
echo "Optimizing Laravel..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Thực thi lệnh được chỉ định
echo "Starting application..."
exec "$@"