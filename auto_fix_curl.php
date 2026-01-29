<?php
/**
 * إصلاح تلقائي لـ CURL
 * افتح: http://localhost:8080/hospital/auto_fix_curl.php
 */

error_reporting(E_ALL);
ini_set('display_errors', 1);

?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>إصلاح CURL تلقائياً</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 20px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            direction: rtl;
            min-height: 100vh;
        }
        .container {
            max-width: 900px;
            margin: 0 auto;
            background: white;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.3);
        }
        .success {
            color: #27ae60;
            font-size: 20px;
            font-weight: bold;
            padding: 15px;
            background: #d4edda;
            border-radius: 5px;
            margin: 15px 0;
            border: 2px solid #27ae60;
        }
        .error {
            color: #e74c3c;
            font-size: 20px;
            font-weight: bold;
            padding: 15px;
            background: #f8d7da;
            border-radius: 5px;
            margin: 15px 0;
            border: 2px solid #e74c3c;
        }
        .info {
            background: #e8f4f8;
            padding: 15px;
            border-radius: 5px;
            margin: 15px 0;
        }
        .warning {
            background: #fff3cd;
            padding: 15px;
            border-radius: 5px;
            margin: 15px 0;
            border: 2px solid #ffc107;
        }
        h1 {
            color: #2c3e50;
            text-align: center;
            border-bottom: 4px solid #3498db;
            padding-bottom: 15px;
        }
        code {
            background: #2c3e50;
            color: #ecf0f1;
            padding: 8px 12px;
            border-radius: 5px;
            display: block;
            margin: 10px 0;
            font-family: 'Courier New', monospace;
            direction: ltr;
            text-align: left;
        }
        .step {
            background: #f8f9fa;
            padding: 15px;
            margin: 10px 0;
            border-radius: 5px;
            border-right: 4px solid #3498db;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 15px 0;
        }
        th, td {
            padding: 10px;
            text-align: right;
            border-bottom: 1px solid #ddd;
        }
        th {
            background: #3498db;
            color: white;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🔧 فحص وإصلاح CURL تلقائياً</h1>
        
        <?php
        // 1. فحص CURL
        echo "<h2>1. حالة CURL الحالية:</h2>";
        $curl_loaded = extension_loaded('curl');
        $openssl_loaded = extension_loaded('openssl');
        
        if ($curl_loaded) {
            echo "<div class='success'>✅ CURL مفعل بنجاح!</div>";
            $version = curl_version();
            echo "<div class='info'>";
            echo "<strong>معلومات CURL:</strong><br>";
            echo "الإصدار: " . $version['version'] . "<br>";
            echo "SSL Version: " . $version['ssl_version'] . "<br>";
            echo "Host: " . $version['host'] . "<br>";
            echo "</div>";
        } else {
            echo "<div class='error'>❌ CURL غير مفعل</div>";
        }
        
        // 2. فحص OpenSSL
        echo "<h2>2. حالة OpenSSL:</h2>";
        if ($openssl_loaded) {
            echo "<div class='success'>✅ OpenSSL مفعل</div>";
        } else {
            echo "<div class='error'>❌ OpenSSL غير مفعل (مطلوب لـ CURL)</div>";
        }
        
        // 3. معلومات php.ini
        echo "<h2>3. معلومات ملف php.ini:</h2>";
        $php_ini_file = php_ini_loaded_file();
        echo "<div class='info'>";
        echo "<strong>ملف php.ini المستخدم:</strong><br>";
        echo "<code>$php_ini_file</code>";
        echo "</div>";
        
        // 4. فحص محتوى php.ini
        if ($php_ini_file && file_exists($php_ini_file)) {
            echo "<h2>4. فحص محتوى ملف php.ini:</h2>";
            $ini_content = file_get_contents($php_ini_file);
            $lines = explode("\n", $ini_content);
            
            $curl_found = false;
            $openssl_found = false;
            $curl_line_num = 0;
            $openssl_line_num = 0;
            $curl_enabled = false;
            $openssl_enabled = false;
            
            foreach ($lines as $num => $line) {
                $line_num = $num + 1;
                $line_trimmed = trim($line);
                
                // البحث عن CURL
                if (preg_match('/^\s*extension\s*=\s*(php_curl\.dll|curl)/i', $line_trimmed)) {
                    $curl_found = true;
                    $curl_line_num = $line_num;
                    if (strpos($line_trimmed, ';') !== 0) {
                        $curl_enabled = true;
                    }
                }
                
                // البحث عن OpenSSL
                if (preg_match('/^\s*extension\s*=\s*(php_openssl\.dll|openssl)/i', $line_trimmed)) {
                    $openssl_found = true;
                    $openssl_line_num = $line_num;
                    if (strpos($line_trimmed, ';') !== 0) {
                        $openssl_enabled = true;
                    }
                }
            }
            
            echo "<table>";
            echo "<tr><th>الامتداد</th><th>موجود</th><th>مفعّل</th><th>رقم السطر</th></tr>";
            
            if ($curl_found) {
                $status = $curl_enabled ? "✅ نعم" : "❌ لا (معطل)";
                echo "<tr><td>CURL</td><td>✅ نعم</td><td>$status</td><td>$curl_line_num</td></tr>";
            } else {
                echo "<tr><td>CURL</td><td>❌ لا</td><td>-</td><td>-</td></tr>";
            }
            
            if ($openssl_found) {
                $status = $openssl_enabled ? "✅ نعم" : "❌ لا (معطل)";
                echo "<tr><td>OpenSSL</td><td>✅ نعم</td><td>$status</td><td>$openssl_line_num</td></tr>";
            } else {
                echo "<tr><td>OpenSSL</td><td>❌ لا</td><td>-</td><td>-</td></tr>";
            }
            
            echo "</table>";
        }
        
        // 5. فحص الملفات
        echo "<h2>5. فحص ملفات DLL:</h2>";
        $ext_dir = ini_get('extension_dir');
        if (empty($ext_dir)) {
            $ext_dir = 'C:\\xampp\\php\\ext';
        }
        
        $curl_dll = rtrim($ext_dir, '\\/') . '\\php_curl.dll';
        $openssl_dll = rtrim($ext_dir, '\\/') . '\\php_openssl.dll';
        
        echo "<table>";
        echo "<tr><th>الملف</th><th>الحالة</th><th>المسار</th></tr>";
        
        if (file_exists($curl_dll)) {
            echo "<tr><td>php_curl.dll</td><td class='success'>✅ موجود</td><td><code>$curl_dll</code></td></tr>";
        } else {
            echo "<tr><td>php_curl.dll</td><td class='error'>❌ غير موجود</td><td><code>$curl_dll</code></td></tr>";
        }
        
        if (file_exists($openssl_dll)) {
            echo "<tr><td>php_openssl.dll</td><td class='success'>✅ موجود</td><td><code>$openssl_dll</code></td></tr>";
        } else {
            echo "<tr><td>php_openssl.dll</td><td class='warning'>⚠️ قد يكون اسمه مختلفاً</td><td><code>$openssl_dll</code></td></tr>";
        }
        
        echo "</table>";
        
        // 6. الحلول
        if (!$curl_loaded) {
            echo "<h2>6. الحل المطلوب:</h2>";
            echo "<div class='warning'>";
            echo "<h3>⚠️ CURL غير مفعل - اتبع الخطوات التالية:</h3>";
            echo "<div class='step'>";
            echo "<h4>الخطوة 1: افتح ملف php.ini</h4>";
            echo "<p>افتح: <code>$php_ini_file</code></p>";
            echo "<p>أو من XAMPP Control Panel → Config → PHP (php.ini)</p>";
            echo "</div>";
            
            echo "<div class='step'>";
            echo "<h4>الخطوة 2: تحقق من CURL و OpenSSL</h4>";
            echo "<p>ابحث عن:</p>";
            echo "<code>extension=openssl<br>extension=php_curl.dll</code>";
            echo "<p>يجب أن يكونا بدون <code>;</code> في البداية</p>";
            echo "</div>";
            
            echo "<div class='step'>";
            echo "<h4>الخطوة 3: احفظ الملف</h4>";
            echo "<p>اضغط Ctrl+S لحفظ الملف</p>";
            echo "</div>";
            
            echo "<div class='step'>";
            echo "<h4>الخطوة 4: أعد تشغيل Apache (مهم جداً!)</h4>";
            echo "<ol>";
            echo "<li>XAMPP Control Panel → Stop Apache</li>";
            echo "<li>انتظر 15 ثانية</li>";
            echo "<li>Start Apache</li>";
            echo "<li>انتظر 15 ثانية</li>";
            echo "</ol>";
            echo "<p class='error'><strong>⚠️ لا تكفي إعادة تحميل الصفحة - يجب إعادة تشغيل Apache!</strong></p>";
            echo "</div>";
            
            echo "<div class='step'>";
            echo "<h4>الخطوة 5: اختبر مرة أخرى</h4>";
            echo "<p>أعد فتح هذه الصفحة بعد إعادة تشغيل Apache</p>";
            echo "</div>";
            
            echo "</div>";
        } else {
            echo "<h2>6. النتيجة:</h2>";
            echo "<div class='success'>";
            echo "<h3>✅ كل شيء يعمل بشكل صحيح!</h3>";
            echo "<p>CURL و OpenSSL مفعلان بنجاح</p>";
            echo "<p>يمكنك الآن المتابعة إلى صفحة التثبيت:</p>";
            echo "<a href='index.php/install/index' style='display: inline-block; padding: 12px 25px; background: #27ae60; color: white; text-decoration: none; border-radius: 5px; font-weight: bold; margin-top: 10px;'>ابدأ التثبيت</a>";
            echo "</div>";
        }
        
        // 7. معلومات إضافية
        echo "<h2>7. معلومات إضافية:</h2>";
        echo "<div class='info'>";
        echo "<p><strong>إصدار PHP:</strong> " . PHP_VERSION . "</p>";
        echo "<p><strong>Server API:</strong> " . php_sapi_name() . "</p>";
        echo "<p><strong>extension_dir:</strong> <code>$ext_dir</code></p>";
        echo "</div>";
        ?>
        
        <hr style="margin: 30px 0;">
        <div style="text-align: center;">
            <a href="index.php/install/index" style="display: inline-block; padding: 12px 25px; background: #3498db; color: white; text-decoration: none; border-radius: 5px; font-weight: bold; margin: 5px;">صفحة التثبيت</a>
            <a href="check_curl.php" style="display: inline-block; padding: 12px 25px; background: #27ae60; color: white; text-decoration: none; border-radius: 5px; font-weight: bold; margin: 5px;">فحص CURL</a>
        </div>
    </div>
</body>
</html>
