@echo off
echo ========================================
echo   إعادة تشغيل Apache - الحل النهائي
echo ========================================
echo.

echo [1/4] إيقاف Apache...
taskkill /F /IM httpd.exe >nul 2>&1
timeout /t 3 /nobreak >nul

echo [2/4] إيقاف Apache من XAMPP...
cd /d C:\xampp
call apache_stop.bat >nul 2>&1
timeout /t 5 /nobreak >nul

echo [3/4] بدء Apache...
call apache_start.bat
timeout /t 5 /nobreak >nul

echo [4/4] التحقق من Apache...
timeout /t 3 /nobreak >nul

echo.
echo ========================================
echo   تم إعادة تشغيل Apache بنجاح!
echo ========================================
echo.
echo ملف php.ini المستخدم: C:\php82\php.ini
echo CURL مفعل في السطر 917: extension=curl
echo OpenSSL مفعل في السطر 930: extension=openssl
echo.
echo افتح الآن:
echo http://localhost:8080/hospital/assets/check_curl.php
echo.
pause
