<?php
/**
 * محاولة تفعيل CURL برمجياً
 * افتح في المتصفح: http://localhost/hospital/force_enable_curl.php
 */

// محاولة تحميل CURL برمجياً
if (!extension_loaded('curl')) {
    // محاولة تحميل الامتداد
    $ext_dir = ini_get('extension_dir');
    if (empty($ext_dir)) {
        $ext_dir = 'C:\\xampp\\php\\ext';
    }
    
    $curl_dll = $ext_dir . '\\php_curl.dll';
    
    if (file_exists($curl_dll)) {
        if (function_exists('dl')) {
            @dl('php_curl.dll');
        }
    }
}

?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>تفعيل CURL قسرياً</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 20px;
            background: #f5f5f5;
            direction: rtl;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            padding: 30px;
            border-radius: 10px;
        }
        .success { color: #27ae60; font-weight: bold; font-size: 20px; }
        .error { color: #e74c3c; font-weight: bold; font-size: 20px; }
        .info { background: #e8f4f8; padding: 15px; border-radius: 5px; margin: 10px 0; }
        code { background: #f4f4f4; padding: 2px 6px; border-radius: 3px; }
    </style>
</head>
<body>
    <div class="container">
        <h2>🔧 محاولة تفعيل CURL قسرياً</h2>
        
        <?php
        echo "<h3>1. حالة CURL بعد المحاولة:</h3>";
        if (extension_loaded('curl')) {
            echo "<p class='success'>✅ CURL مفعل!</p>";
            $version = curl_version();
            echo "<div class='info'>";
            echo "<strong>معلومات CURL:</strong><br>";
            echo "الإصدار: " . $version['version'] . "<br>";
            echo "SSL Version: " . $version['ssl_version'] . "<br>";
            echo "</div>";
        } else {
            echo "<p class='error'>❌ CURL لا يزال غير مفعل</p>";
            echo "<div class='info'>";
            echo "<h4>المعلومات:</h4>";
            echo "<strong>extension_dir:</strong> " . ini_get('extension_dir') . "<br>";
            echo "<strong>php_curl.dll موجود:</strong> " . (file_exists($curl_dll) ? 'نعم ✅' : 'لا ❌') . "<br>";
            echo "<strong>دالة dl متاحة:</strong> " . (function_exists('dl') ? 'نعم' : 'لا') . "<br>";
            echo "</div>";
        }
        
        echo "<h3>2. معلومات PHP:</h3>";
        echo "<div class='info'>";
        echo "<strong>مسار php.ini:</strong> " . php_ini_loaded_file() . "<br>";
        echo "<strong>إصدار PHP:</strong> " . PHP_VERSION . "<br>";
        echo "<strong>Server API:</strong> " . php_sapi_name() . "<br>";
        echo "</div>";
        
        echo "<h3>3. جميع الامتدادات المثبتة:</h3>";
        $extensions = get_loaded_extensions();
        sort($extensions);
        echo "<div class='info'>";
        echo "<strong>عدد الامتدادات:</strong> " . count($extensions) . "<br>";
        echo "<strong>CURL موجود:</strong> " . (in_array('curl', $extensions) ? 'نعم ✅' : 'لا ❌') . "<br>";
        echo "<strong>OpenSSL موجود:</strong> " . (in_array('openssl', $extensions) ? 'نعم ✅' : 'لا ❌') . "<br>";
        echo "</div>";
        
        if (!extension_loaded('curl')) {
            echo "<h3>4. الحلول المقترحة:</h3>";
            echo "<div class='info'>";
            echo "<ol>";
            echo "<li><strong>تحقق من ملف php.ini:</strong><br>";
            echo "   افتح: <code>" . php_ini_loaded_file() . "</code><br>";
            echo "   ابحث عن: <code>extension=php_curl.dll</code> أو <code>extension=curl</code><br>";
            echo "   تأكد من أنه بدون <code>;</code> في البداية</li>";
            echo "<li><strong>تحقق من OpenSSL:</strong><br>";
            echo "   يجب أن يكون <code>extension=openssl</code> أو <code>extension=php_openssl.dll</code> مفعل</li>";
            echo "<li><strong>أعد تشغيل Apache:</strong><br>";
            echo "   XAMPP Control Panel → Stop Apache → Start Apache</li>";
            echo "<li><strong>تحقق من المكتبات المطلوبة:</strong><br>";
            echo "   تأكد من وجود: libeay32.dll و ssleay32.dll في مجلد PHP</li>";
            echo "</ol>";
            echo "</div>";
        }
        ?>
        
        <hr>
        <p>
            <a href="phpinfo.php">عرض phpinfo الكامل</a> |
            <a href="check_system.php">فحص النظام</a> |
            <a href="index.php/install/index">صفحة التثبيت</a>
        </p>
    </div>
</body>
</html>
