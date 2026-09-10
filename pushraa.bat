@echo off
echo ================= PUSH TO RAA ================

set /p commit_message="Enter commit message: "

php artisan migrate:fresh
php artisan optimize:clear
git pull origin master
git pull origin raa
git add .
git commit -m "%commit_message%"
git push origin raa

echo.
echo ================= DONE =================
pause
