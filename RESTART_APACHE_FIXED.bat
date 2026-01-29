@echo off
echo ========================================
echo   إعادة تشغيل Apache بعد إصلاح SSL
echo ========================================
echo.

cd C:\xampp

echo [1/3] إيقاف Apache...
call apache_stop.bat
timeout /t 5 /nobreak >nul

echo [2/3] بدء Apache...
call apache_start.bat
timeout /t 5 /nobreak >nul

echo [3/3] التحقق من Apache...
timeout /t 3 /nobreak >nul

echo.
echo ========================================
echo   تم إعادة تشغيل Apache بنجاح!
echo ========================================
echo.
echo تم إعادة تفعيل SSL في Apache
echo Apache يعمل الآن على:
echo - HTTP:  http://localhost:8080
echo - HTTPS: https://localhost:4433
echo.
echo يمكنك الآن:
echo 1. فتح: http://localhost:8080/hospital/install/index
echo 2. إنشاء قاعدة البيانات من phpMyAdmin
echo 3. إكمال التثبيت
echo.
pause
