#!/bin/bash

# Navigate to project root
cd /home/site/wwwroot

# Clear and rebuild cache (if view fails, we ignore it)
php artisan config:clear
php artisan route:clear
php artisan view:clear || true

# Run production configurations
php artisan config:cache
php artisan route:cache
php artisan event:cache

# Link storage (fallback/temp)
php artisan storage:link

# Start queue worker as background process
nohup php artisan queue:work --sleep=3 --tries=3 --max-time=3600 > /dev/null 2>&1 &

# Start Laravel Reverb WebSocket server as background process on port 8080
nohup php artisan reverb:start --host=0.0.0.0 --port=8080 > /dev/null 2>&1 &

echo "Applying custom Nginx configuration..."
# Copy our custom Nginx config over the Azure default config
cp /home/site/wwwroot/nginx.conf /etc/nginx/sites-available/default
cp /home/site/wwwroot/nginx.conf /etc/nginx/sites-enabled/default

# Reload nginx service to apply changes
service nginx reload || nginx -s reload
echo "Nginx reloaded successfully."
