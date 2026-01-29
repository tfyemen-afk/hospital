# سكريبت لإعادة تشغيل Apache في XAMPP
# انقر بزر الماوس الأيمن → Run with PowerShell

Write-Host "========================================" -ForegroundColor Cyan
Write-Host "  إعادة تشغيل Apache في XAMPP" -ForegroundColor Cyan
Write-Host "========================================" -ForegroundColor Cyan
Write-Host ""

# التحقق من وجود XAMPP
$xamppPath = "C:\xampp"
if (-not (Test-Path $xamppPath)) {
    Write-Host "❌ XAMPP غير موجود في المسار المتوقع: $xamppPath" -ForegroundColor Red
    Write-Host "يرجى تعديل المسار في السكريبت" -ForegroundColor Yellow
    pause
    exit
}

Write-Host "✅ تم العثور على XAMPP في: $xamppPath" -ForegroundColor Green
Write-Host ""

# محاولة إيقاف Apache
Write-Host "🛑 محاولة إيقاف Apache..." -ForegroundColor Yellow
$apacheStop = & "$xamppPath\apache_stop.bat" 2>&1
Start-Sleep -Seconds 3

# التحقق من أن Apache متوقف
$apacheProcess = Get-Process -Name "httpd" -ErrorAction SilentlyContinue
if ($apacheProcess) {
    Write-Host "⚠️ Apache لا يزال يعمل، جاري إيقافه قسراً..." -ForegroundColor Yellow
    Stop-Process -Name "httpd" -Force -ErrorAction SilentlyContinue
    Start-Sleep -Seconds 2
}

Write-Host "✅ Apache متوقف" -ForegroundColor Green
Write-Host ""

# بدء Apache
Write-Host "🚀 بدء Apache..." -ForegroundColor Yellow
$apacheStart = & "$xamppPath\apache_start.bat" 2>&1
Start-Sleep -Seconds 3

# التحقق من أن Apache يعمل
$apacheProcess = Get-Process -Name "httpd" -ErrorAction SilentlyContinue
if ($apacheProcess) {
    Write-Host "✅ Apache يعمل بنجاح!" -ForegroundColor Green
    Write-Host ""
    Write-Host "يمكنك الآن فتح:" -ForegroundColor Cyan
    Write-Host "  http://localhost/hospital/check_system.php" -ForegroundColor White
    Write-Host "  http://localhost/hospital/test_curl.php" -ForegroundColor White
    Write-Host "  http://localhost/hospital/index.php/install/index" -ForegroundColor White
} else {
    Write-Host "❌ فشل بدء Apache" -ForegroundColor Red
    Write-Host "يرجى تشغيل Apache يدوياً من XAMPP Control Panel" -ForegroundColor Yellow
}

Write-Host ""
Write-Host "اضغط أي مفتاح للخروج..."
$null = $Host.UI.RawUI.ReadKey("NoEcho,IncludeKeyDown")
