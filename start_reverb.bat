@echo off
echo Starting Laravel Reverb and Queue Worker...
start "Servedavao Queue" php artisan queue:listen
start "Servedavao Reverb" php artisan reverb:start
echo Reverb Server and Queue Listener are running in the background.
echo Do not close the popped-up windows.
pause
