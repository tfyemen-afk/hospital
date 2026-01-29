<?php
/**
 * ملف اختبار CURL
 * افتح هذا الملف في المتصفح: http://localhost/hospital/test_curl.php
 */

echo "<h2>اختبار CURL PHP</h2>";
echo "<hr>";

// التحقق من وجود دالة curl_version
if (function_exists('curl_version')) {
    echo "<div style='color: green; font-size: 18px;'>✅ CURL مفعل بنجاح!</div><br>";
    
    $version = curl_version();
    echo "<strong>معلومات CURL:</strong><br>";
    echo "الإصدار: " . $version['version'] . "<br>";
    echo "SSL Version: " . $version['ssl_version'] . "<br>";
    echo "Host: " . $version['host'] . "<br>";
    
    // اختبار بسيط
    echo "<hr>";
    echo "<strong>اختبار الاتصال:</strong><br>";
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "https://www.google.com");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 5);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    
    $result = curl_exec($ch);
    $error = curl_error($ch);
    curl_close($ch);
    
    if ($result !== false) {
        echo "✅ الاتصال يعمل بشكل صحيح!<br>";
    } else {
        echo "⚠️ تحذير: " . $error . "<br>";
    }
    
} else {
    echo "<div style='color: red; font-size: 18px;'>❌ CURL غير مفعل!</div><br>";
    echo "<strong>خطوات التفعيل:</strong><br>";
    echo "1. افتح ملف php.ini من XAMPP Control Panel → Config → PHP (php.ini)<br>";
    echo "2. ابحث عن: ;extension=curl<br>";
    echo "3. احذف الفاصلة المنقوطة ; من بداية السطر<br>";
    echo "4. احفظ الملف وأعد تشغيل Apache<br>";
    echo "<br>";
    echo "راجع ملف ENABLE_CURL_AR.md للتفاصيل الكاملة.";
}

echo "<hr>";
echo "<h3>معلومات PHP:</h3>";
echo "إصدار PHP: " . PHP_VERSION . "<br>";
echo "مسار php.ini: " . php_ini_loaded_file() . "<br>";

// عرض جميع الامتدادات المثبتة
echo "<hr>";
echo "<h3>الامتدادات المثبتة:</h3>";
$extensions = get_loaded_extensions();
sort($extensions);
echo "<ul>";
foreach ($extensions as $ext) {
    $color = ($ext === 'curl') ? 'color: green; font-weight: bold;' : '';
    echo "<li style='$color'>$ext</li>";
}
echo "</ul>";

?>
