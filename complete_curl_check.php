<?php
/**
 * فحص شامل لـ CURL - الحل النهائي
 * افتح في المتصفح: http://localhost/hospital/complete_curl_check.php
 */

error_reporting(E_ALL);
ini_set('display_errors', 1);

?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>الفحص الشامل لـ CURL</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            padding: 20px;
            background: #f5f5f5;
            direction: rtl;
        }
        .container {
            max-width: 1000px;
            margin: 0 auto;
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .success { color: #27ae60; font-weight: bold; }
        .error { color: #e74c3c; font-weight: bold; }
        .warning { color: #f39c12; font-weight: bold; }
        .info { background: #e8f4f8; padding: 15px; border-radius: 5px; margin: 10px 0; }
        .critical { background: #ffe6e6; padding: 15px; border-radius: 5px; margin: 10px 0; border: 2px solid #e74c3c; }
        h2 { color: #2c3e50; border-bottom: 3px solid #3498db; padding-bottom: 10px; }
        code { background: #f4f4f4; padding: 2px 6px; border-radius: 3px; font-family: 'Courier New', monospace; }
        .btn {
            display: inline-block;
            padding: 10px 20px;
            background: #3498db;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin: 5px;
        }
        .btn:hover { background: #2980b9; }
        table { width: 100%; border-collapse: collapse; margin: 10px 0; }
        th, td { padding: 10px; text-align: right; border-bottom: 1px solid #ddd; }
        th { background: #3498db; color: white; }
    </style>
</head>
<body>
    <div class="container">
        <h1>🔍 الفحص الشامل لـ CURL</h1>
        
        <?php
        $issues = [];
        $fixes = [];
        
        // 1. فحص CURL
        echo "<h2>1. حالة CURL</h2>";
        if (extension_loaded('curl')) {
            echo "<p class='success' style='font-size: 20px;'>✅ CURL مفعل بنجاح!</p>";
            $version = curl_version();
            echo "<div class='info'>";
            echo "<strong>معلومات CURL:</strong><br>";
            echo "الإصدار: " . $version['version'] . "<br>";
            echo "SSL Version: " . $version['ssl_version'] . "<br>";
            echo "Host: " . $version['host'] . "<br>";
            echo "</div>";
        } else {
            echo "<p class='error' style='font-size: 20px;'>❌ CURL غير مفعل</p>";
            $issues[] = "CURL extension not loaded";
        }
        
        // 2. فحص OpenSSL
        echo "<h2>2. حالة OpenSSL</h2>";
        if (extension_loaded('openssl')) {
            echo "<p class='success'>✅ OpenSSL مفعل</p>";
        } else {
            echo "<p class='error'>❌ OpenSSL غير مفعل (مطلوب لـ CURL)</p>";
            $issues[] = "OpenSSL extension not loaded";
            $fixes[] = "فعّل OpenSSL في php.ini: extension=openssl";
        }
        
        // 3. معلومات php.ini
        echo "<h2>3. معلومات ملف php.ini</h2>";
        $php_ini_file = php_ini_loaded_file();
        $php_ini_scanned = php_ini_scanned_files();
        
        echo "<div class='info'>";
        echo "<strong>ملف php.ini المستخدم:</strong><br>";
        echo "<code style='background: #fff; padding: 5px; display: block; margin: 5px 0;'>$php_ini_file</code>";
        
        if ($php_ini_file && file_exists($php_ini_file)) {
            echo "<p class='success'>✅ الملف موجود</p>";
            
            // قراءة محتوى ملف php.ini للتحقق من CURL
            $ini_content = file_get_contents($php_ini_file);
            $curl_found = false;
            $openssl_found = false;
            $curl_line = '';
            $openssl_line = '';
            
            $lines = explode("\n", $ini_content);
            foreach ($lines as $num => $line) {
                $line_num = $num + 1;
                $line_trimmed = trim($line);
                
                // البحث عن CURL
                if (preg_match('/^\s*extension\s*=\s*(php_curl\.dll|curl)/i', $line_trimmed)) {
                    $curl_found = true;
                    $curl_line = "السطر $line_num: " . htmlspecialchars($line);
                    if (strpos($line_trimmed, ';') === 0) {
                        $issues[] = "CURL معطل في السطر $line_num";
                        $fixes[] = "احذف ; من بداية السطر $line_num في ملف php.ini";
                    }
                }
                
                // البحث عن OpenSSL
                if (preg_match('/^\s*extension\s*=\s*(php_openssl\.dll|openssl)/i', $line_trimmed)) {
                    $openssl_found = true;
                    $openssl_line = "السطر $line_num: " . htmlspecialchars($line);
                    if (strpos($line_trimmed, ';') === 0) {
                        $issues[] = "OpenSSL معطل في السطر $line_num";
                        $fixes[] = "احذف ; من بداية السطر $line_num في ملف php.ini";
                    }
                }
            }
            
            echo "<h3>نتائج البحث في ملف php.ini:</h3>";
            if ($curl_found) {
                echo "<p class='success'>✅ تم العثور على CURL:</p>";
                echo "<code>$curl_line</code>";
            } else {
                echo "<p class='error'>❌ CURL غير موجود في ملف php.ini</p>";
                $issues[] = "CURL not found in php.ini";
                $fixes[] = "أضف السطر التالي في ملف php.ini: extension=php_curl.dll";
            }
            
            if ($openssl_found) {
                echo "<p class='success'>✅ تم العثور على OpenSSL:</p>";
                echo "<code>$openssl_line</code>";
            } else {
                echo "<p class='error'>❌ OpenSSL غير موجود في ملف php.ini</p>";
                $issues[] = "OpenSSL not found in php.ini";
                $fixes[] = "أضف السطر التالي في ملف php.ini: extension=openssl";
            }
            
        } else {
            echo "<p class='error'>❌ ملف php.ini غير موجود أو غير قابل للقراءة</p>";
            $issues[] = "php.ini file not found";
        }
        echo "</div>";
        
        // 4. فحص extension_dir
        echo "<h2>4. فحص extension_dir</h2>";
        $ext_dir = ini_get('extension_dir');
        echo "<div class='info'>";
        echo "<strong>extension_dir:</strong> <code>$ext_dir</code><br>";
        
        if ($ext_dir) {
            $curl_dll = rtrim($ext_dir, '\\/') . '\\php_curl.dll';
            $openssl_dll = rtrim($ext_dir, '\\/') . '\\php_openssl.dll';
            
            if (file_exists($curl_dll)) {
                echo "<p class='success'>✅ php_curl.dll موجود في: $curl_dll</p>";
            } else {
                echo "<p class='error'>❌ php_curl.dll غير موجود في: $curl_dll</p>";
                $issues[] = "php_curl.dll file not found";
            }
            
            if (file_exists($openssl_dll)) {
                echo "<p class='success'>✅ php_openssl.dll موجود في: $openssl_dll</p>";
            } else {
                echo "<p class='warning'>⚠️ php_openssl.dll غير موجود (قد يكون اسمه مختلفاً)</p>";
            }
        } else {
            echo "<p class='error'>❌ extension_dir غير محدد</p>";
            $issues[] = "extension_dir not set";
        }
        echo "</div>";
        
        // 5. معلومات النظام
        echo "<h2>5. معلومات النظام</h2>";
        echo "<div class='info'>";
        echo "<strong>إصدار PHP:</strong> " . PHP_VERSION . "<br>";
        echo "<strong>Server API:</strong> " . php_sapi_name() . "<br>";
        echo "<strong>نظام التشغيل:</strong> " . PHP_OS . "<br>";
        echo "</div>";
        
        // 6. الملخص والحلول
        echo "<h2>6. الملخص والحلول</h2>";
        
        if (empty($issues)) {
            echo "<div class='info'>";
            echo "<h3 class='success'>✅ كل شيء على ما يرام!</h3>";
            echo "<p>إذا كان CURL لا يزال غير مفعل، المشكلة هي أن Apache لم يُعاد تشغيله بعد التعديلات.</p>";
            echo "<p><strong>الحل:</strong> أعد تشغيل Apache من XAMPP Control Panel</p>";
            echo "</div>";
        } else {
            echo "<div class='critical'>";
            echo "<h3 class='error'>❌ تم العثور على مشاكل:</h3>";
            echo "<ul>";
            foreach ($issues as $issue) {
                echo "<li>$issue</li>";
            }
            echo "</ul>";
            
            if (!empty($fixes)) {
                echo "<h4>الحلول المقترحة:</h4>";
                echo "<ol>";
                foreach ($fixes as $fix) {
                    echo "<li>$fix</li>";
                }
                echo "</ol>";
            }
            echo "</div>";
        }
        
        // 7. الخطوات التالية
        echo "<h2>7. الخطوات التالية</h2>";
        echo "<div class='info'>";
        echo "<ol>";
        echo "<li><strong>افتح ملف php.ini:</strong><br>";
        echo "   <code>$php_ini_file</code><br>";
        echo "   أو من XAMPP Control Panel → Config → PHP (php.ini)</li>";
        echo "<li><strong>تأكد من وجود:</strong><br>";
        echo "   <code>extension=openssl</code><br>";
        echo "   <code>extension=php_curl.dll</code><br>";
        echo "   (بدون ; في البداية)</li>";
        echo "<li><strong>احفظ الملف</strong></li>";
        echo "<li><strong>أعد تشغيل Apache:</strong><br>";
        echo "   XAMPP Control Panel → Stop Apache → انتظر 10 ثوان → Start Apache</li>";
        echo "<li><strong>أعد تحميل هذه الصفحة</strong> (Ctrl+F5)</li>";
        echo "</ol>";
        echo "</div>";
        ?>
        
        <hr>
        <div style="text-align: center; margin-top: 30px;">
            <a href="phpinfo.php" class="btn">عرض phpinfo الكامل</a>
            <a href="check_system.php" class="btn">فحص النظام</a>
            <a href="index.php/install/index" class="btn">صفحة التثبيت</a>
        </div>
    </div>
</body>
</html>
