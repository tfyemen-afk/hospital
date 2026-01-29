@echo off
echo ========================================
echo   إصلاح MySQL - InnoDB Recovery
echo ========================================
echo.

cd C:\xampp\mysql\data

echo [1/4] إيقاف MySQL...
cd ..\..
call mysql_stop.bat
timeout /t 5 /nobreak >nul

echo [2/4] حذف ملفات PID و Lock...
cd mysql\data
if exist mysql.pid del mysql.pid
if exist *.pid del *.pid
if exist *.lock del *.lock
echo تم حذف ملفات PID و Lock

echo [3/4] حذف ملفات InnoDB المؤقتة...
if exist ibtmp1 del ibtmp1
if exist ib_logfile0 del ib_logfile0
if exist ib_logfile1 del ib_logfile1
echo تم حذف ملفات InnoDB المؤقتة

echo [4/4] جاهز لتشغيل MySQL...
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
echo ملاحظة: إذا استمرت المشكلة، قد تحتاج
echo إلى إعادة تثبيت XAMPP أو استعادة نسخة
echo احتياطية من قاعدة البيانات.
echo.
pause
