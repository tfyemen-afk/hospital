@echo off
echo ========================================
echo   تشغيل MySQL في XAMPP
echo ========================================
echo.

cd C:\xampp

echo بدء MySQL...
call mysql_start.bat
timeout /t 5 /nobreak >nul

echo.
echo ========================================
echo   تم تشغيل MySQL
echo ========================================
echo.
echo يمكنك الآن:
echo 1. فتح: http://localhost:8080/phpmyadmin
echo 2. إنشاء قاعدة بيانات باسم: hospital
echo 3. العودة إلى صفحة التثبيت
echo.
pause
