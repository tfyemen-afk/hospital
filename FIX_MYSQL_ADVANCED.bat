@echo off
chcp 65001 >nul
echo ========================================
echo   إصلاح MySQL المتقدم - Advanced Fix
echo ========================================
echo.

echo [1/6] إيقاف جميع عمليات MySQL...
taskkill /F /IM mysqld.exe 2>nul
taskkill /F /IM mysql.exe 2>nul
timeout /t 3 /nobreak >nul
echo تم إيقاف جميع عمليات MySQL

echo [2/6] حذف ملفات PID و Lock...
cd C:\xampp\mysql\data
if exist mysql.pid del mysql.pid
if exist *.pid del *.pid
if exist *.lock del *.lock
echo تم حذف ملفات PID و Lock

echo [3/6] حذف ملفات InnoDB المؤقتة...
if exist ibtmp1 del ibtmp1
if exist ib_logfile0 del ib_logfile0
if exist ib_logfile1 del ib_logfile1
echo تم حذف ملفات InnoDB المؤقتة

echo [4/6] التحقق من المنفذ 3306...
netstat -ano | findstr :3306
if %errorlevel% == 0 (
    echo تحذير: المنفذ 3306 مستخدم!
    echo سيتم محاولة إيقاف العملية...
    for /f "tokens=5" %%a in ('netstat -ano ^| findstr :3306') do (
        taskkill /F /PID %%a 2>nul
    )
) else (
    echo المنفذ 3306 متاح
)

echo [5/6] إصلاح الصلاحيات...
icacls "C:\xampp\mysql\data" /grant Everyone:F /T /Q >nul 2>&1
icacls "C:\xampp\mysql\bin" /grant Everyone:F /T /Q >nul 2>&1
echo تم إصلاح الصلاحيات

echo [6/6] جاهز لتشغيل MySQL...
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
echo إذا استمرت المشكلة:
echo - تحقق من Windows Event Viewer
echo - تحقق من ملف mysql_error.log
echo - جرب إعادة تثبيت XAMPP
echo.
pause
