@echo off
echo ========================================
echo   إعادة تشغيل XAMPP - Apache و MySQL
echo ========================================
echo.

cd C:\xampp

echo [1/4] إيقاف Apache...
call apache_stop.bat
timeout /t 3 /nobreak >nul

echo [2/4] إيقاف MySQL...
call mysql_stop.bat
timeout /t 3 /nobreak >nul

echo [3/4] بدء MySQL...
call mysql_start.bat
timeout /t 5 /nobreak >nul

echo [4/4] بدء Apache...
call apache_start.bat
timeout /t 5 /nobreak >nul

echo.
echo ========================================
echo   تم إعادة التشغيل بنجاح!
echo ========================================
echo.
echo تم تعطيل SSL في Apache (لحل مشكلة المنفذ 443)
echo.
echo يمكنك الآن:
echo 1. فتح: http://localhost:8080/hospital/install/index
echo 2. إنشاء قاعدة البيانات من phpMyAdmin
echo 3. إدخال معلومات قاعدة البيانات
echo.
pause
