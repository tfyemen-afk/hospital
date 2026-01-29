<?php
echo "<h1>PHP يعمل! ✅</h1>";
echo "<p>إصدار PHP: " . PHP_VERSION . "</p>";

if (extension_loaded('curl')) {
    echo "<p style='color: green; font-size: 24px; font-weight: bold;'>✅ CURL مفعل!</p>";
    $version = curl_version();
    echo "<p>إصدار CURL: " . $version['version'] . "</p>";
} else {
    echo "<p style='color: red; font-size: 24px; font-weight: bold;'>❌ CURL غير مفعل</p>";
    echo "<p>ملف php.ini: " . php_ini_loaded_file() . "</p>";
}
?>
