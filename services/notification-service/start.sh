#!/bin/sh

echo "🚀 Starting Notification Service..."

# Ждем пока RabbitMQ будет готов (без netcat)
echo "🐰 Waiting for RabbitMQ to be ready..."
while true; do
    if php -r "\$c = @fsockopen('rabbitmq', 5672); if (\$c) { fclose(\$c); exit(0); } exit(1);"; then
        echo "✅ RabbitMQ is ready!"
        break
    fi
    echo "Waiting for RabbitMQ..."
    sleep 2
done

# Запускаем PHP-FPM в фоне (для веб-запросов через nginx)
echo "📡 Starting PHP-FPM..."
php-fpm -F -R &

# Ждем немного чтобы PHP-FPM запустился
sleep 3

# Запускаем Messenger worker (блокирующая команда)
echo "🔄 Starting Messenger worker..."
php bin/console messenger:consume async -vv > /var/www/html/var/log/messenger.log 2>&1

# Если worker упадет, контейнер завершится
echo "❌ Messenger worker stopped"
