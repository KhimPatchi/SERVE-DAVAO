#!/bin/bash

# Navigate to project root
cd /home/site/wwwroot

# Clear existing cache
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Run production configurations
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache

# Link storage (fallback/temp)
php artisan storage:link

# Start queue worker as background process
nohup php artisan queue:work --sleep=3 --tries=3 --max-time=3600 > /dev/null 2>&1 &

# Start Laravel Reverb WebSocket server as background process
nohup php artisan reverb:start --host=0.0.0.0 --port=8080 > /dev/null 2>&1 &

# Start nginx with heroku config
vendor/bin/heroku-php-nginx -C nginx.conf public/
