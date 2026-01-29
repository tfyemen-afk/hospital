@echo off
echo ========================================
echo   إعادة تشغيل Apache في XAMPP
echo ========================================
echo.

cd C:\xampp

echo إيقاف Apache...
call apache_stop.bat
timeout /t 5 /nobreak >nul

echo بدء Apache...
call apache_start.bat
timeout /t 5 /nobreak >nul

echo.
echo ========================================
echo   تم إعادة تشغيل Apache
echo ========================================
echo.
echo يمكنك الآن فتح:
echo http://localhost:8080/hospital/assets/check_curl.php
echo.
pause
