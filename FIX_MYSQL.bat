@echo off
echo ========================================
echo   إصلاح MySQL - حذف ملفات PID و Lock
echo ========================================
echo.

cd C:\xampp\mysql\data

echo [1/3] حذف ملفات PID و Lock...
if exist mysql.pid del mysql.pid
if exist *.pid del *.pid
echo تم حذف ملفات PID

echo [2/3] التحقق من ملفات Lock...
if exist *.lock del *.lock
echo تم حذف ملفات Lock

echo [3/3] جاهز لتشغيل MySQL...
echo.
echo ========================================
echo   تم الإصلاح بنجاح!
echo ========================================
echo.
echo الآن:
echo 1. افتح XAMPP Control Panel
echo 2. انقر على Start بجانب MySQL
echo 3. انتظر حتى يتحول للون الأخضر
echo.
pause
