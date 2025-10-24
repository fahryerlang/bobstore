@echo off
echo Membersihkan semua session...
php artisan session:clear-all

echo Membersihkan cache...
php artisan cache:clear
php artisan config:clear

echo Memulai server Laravel...
php artisan serve

pause
