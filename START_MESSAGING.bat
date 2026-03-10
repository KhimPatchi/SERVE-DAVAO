@echo off
title ServeDavao Real-time Server
echo ---------------------------------------------------
echo Starting ServeDavao Real-time Messaging Server...
echo ---------------------------------------------------
echo [INFO] Keeping this window open will keep chat live.
echo [INFO] Press Ctrl+C if you want to stop the server.
echo.
php artisan reverb:start --debug
pause
