<?php
/**
 * ملف إصلاح وتحقق من CURL
 * افتح في المتصفح: http://localhost/hospital/fix_curl.php
 */

echo "<h2>🔧 فحص وإصلاح CURL</h2>";
echo "<hr>";

// 1. التحقق من CURL
echo "<h3>1. حالة CURL الحالية:</h3>";
if (extension_loaded('curl')) {
    echo "<p style='color: green; font-size: 18px;'>✅ CURL مفعل بنجاح!</p>";
    $version = curl_version();
    echo "<p>الإصدار: " . $version['version'] . "</p>";
    echo "<p>SSL Version: " . $version['ssl_version'] . "</p>";
} else {
    echo "<p style='color: red; font-size: 18px;'>❌ CURL غير مفعل</p>";
}

echo "<hr>";

// 2. معلومات PHP
echo "<h3>2. معلومات PHP:</h3>";
echo "<p><strong>إصدار PHP:</strong> " . PHP_VERSION . "</p>";
echo "<p><strong>مسار php.ini:</strong> " . php_ini_loaded_file() . "</p>";
echo "<p><strong>ملفات php.ini الإضافية:</strong> " . php_ini_scanned_files() . "</p>";

echo "<hr>";

// 3. فحص ملف php_curl.dll
echo "<h3>3. فحص ملف php_curl.dll:</h3>";
$curl_dll = "C:\\xampp\\php\\ext\\php_curl.dll";
if (file_exists($curl_dll)) {
    echo "<p style='color: green;'>✅ الملف موجود: $curl_dll</p>";
    echo "<p>حجم الملف: " . round(filesize($curl_dll) / 1024, 2) . " KB</p>";
} else {
    echo "<p style='color: red;'>❌ الملف غير موجود: $curl_dll</p>";
}

echo "<hr>";

// 4. فحص extension_dir
echo "<h3>4. فحص extension_dir:</h3>";
$ext_dir = ini_get('extension_dir');
echo "<p><strong>extension_dir:</strong> $ext_dir</p>";
if ($ext_dir && file_exists($ext_dir . "\\php_curl.dll")) {
    echo "<p style='color: green;'>✅ php_curl.dll موجود في extension_dir</p>";
} else {
    echo "<p style='color: red;'>❌ php_curl.dll غير موجود في extension_dir</p>";
}

echo "<hr>";

// 5. جميع الامتدادات المثبتة
echo "<h3>5. الامتدادات المثبتة:</h3>";
$extensions = get_loaded_extensions();
sort($extensions);
echo "<ul>";
foreach ($extensions as $ext) {
    $color = ($ext === 'curl') ? 'color: green; font-weight: bold;' : '';
    echo "<li style='$color'>$ext</li>";
}
echo "</ul>";

echo "<hr>";

// 6. الحلول المقترحة
echo "<h3>6. الحلول المقترحة:</h3>";

if (!extension_loaded('curl')) {
    echo "<div style='background: #fff3cd; padding: 15px; border-radius: 5px; border: 1px solid #ffc107;'>";
    echo "<h4>⚠️ CURL غير مفعل - اتبع الخطوات التالية:</h4>";
    echo "<ol>";
    echo "<li><strong>افتح ملف php.ini:</strong><br>";
    echo "   XAMPP Control Panel → Config → PHP (php.ini)<br>";
    echo "   أو: " . php_ini_loaded_file() . "</li>";
    echo "<li><strong>ابحث عن:</strong> <code>extension=curl</code></li>";
    echo "<li><strong>تأكد من:</strong> أنه بدون <code>;</code> في البداية</li>";
    echo "<li><strong>احفظ الملف</strong></li>";
    echo "<li><strong>أعد تشغيل Apache:</strong><br>";
    echo "   XAMPP Control Panel → Stop Apache → Start Apache</li>";
    echo "<li><strong>أعد تحميل هذه الصفحة</strong> (Ctrl+F5)</li>";
    echo "</ol>";
    echo "</div>";
} else {
    echo "<div style='background: #d4edda; padding: 15px; border-radius: 5px; border: 1px solid #28a745;'>";
    echo "<h4>✅ CURL يعمل بشكل صحيح!</h4>";
    echo "<p>يمكنك الآن المتابعة إلى صفحة التثبيت:</p>";
    echo "<a href='index.php/install/index' style='display: inline-block; padding: 10px 20px; background: #28a745; color: white; text-decoration: none; border-radius: 5px;'>ابدأ التثبيت</a>";
    echo "</div>";
}

echo "<hr>";
echo "<p><a href='check_system.php'>← العودة إلى فحص النظام</a> | <a href='test_curl.php'>اختبار CURL</a></p>";

?>
