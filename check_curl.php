<?php
/**
 * فحص CURL - يمكن الوصول إليه من نفس المسار
 * افتح: http://localhost:8080/hospital/check_curl.php
 */
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>فحص CURL</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 40px;
            background: #f0f0f0;
            direction: rtl;
        }
        .box {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .success {
            color: #27ae60;
            font-size: 24px;
            font-weight: bold;
            padding: 20px;
            background: #d4edda;
            border-radius: 5px;
            margin: 20px 0;
            border: 2px solid #27ae60;
        }
        .error {
            color: #e74c3c;
            font-size: 24px;
            font-weight: bold;
            padding: 20px;
            background: #f8d7da;
            border-radius: 5px;
            margin: 20px 0;
            border: 2px solid #e74c3c;
        }
        code {
            background: #2c3e50;
            color: #ecf0f1;
            padding: 10px;
            border-radius: 5px;
            display: block;
            margin: 10px 0;
            direction: ltr;
            text-align: left;
            font-family: 'Courier New', monospace;
        }
        h1 {
            color: #2c3e50;
            text-align: center;
            border-bottom: 3px solid #3498db;
            padding-bottom: 15px;
        }
        .step {
            background: #e8f4f8;
            padding: 15px;
            margin: 10px 0;
            border-radius: 5px;
            border-right: 4px solid #3498db;
        }
        .btn {
            display: inline-block;
            padding: 12px 25px;
            background: #27ae60;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin: 10px 5px;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="box">
        <h1>🔍 فحص CURL</h1>
        
        <?php
        echo "<h2>1. حالة CURL:</h2>";
        if (extension_loaded('curl')) {
            echo "<div class='success'>✅ CURL مفعل بنجاح!</div>";
            $version = curl_version();
            echo "<p><strong>الإصدار:</strong> " . $version['version'] . "</p>";
            echo "<p><strong>SSL:</strong> " . $version['ssl_version'] . "</p>";
            echo "<p><strong>Host:</strong> " . $version['host'] . "</p>";
        } else {
            echo "<div class='error'>❌ CURL غير مفعل</div>";
        }
        
        echo "<h2>2. معلومات PHP:</h2>";
        echo "<p><strong>إصدار PHP:</strong> " . PHP_VERSION . "</p>";
        echo "<p><strong>ملف php.ini المستخدم:</strong></p>";
        echo "<code>" . php_ini_loaded_file() . "</code>";
        
        echo "<h2>3. حالة OpenSSL:</h2>";
        if (extension_loaded('openssl')) {
            echo "<p class='success'>✅ OpenSSL مفعل</p>";
        } else {
            echo "<p class='error'>❌ OpenSSL غير مفعل</p>";
        }
        
        echo "<h2>4. جميع الامتدادات المثبتة:</h2>";
        $extensions = get_loaded_extensions();
        sort($extensions);
        echo "<p><strong>عدد الامتدادات:</strong> " . count($extensions) . "</p>";
        echo "<p><strong>CURL موجود:</strong> " . (in_array('curl', $extensions) ? 'نعم ✅' : 'لا ❌') . "</p>";
        echo "<p><strong>OpenSSL موجود:</strong> " . (in_array('openssl', $extensions) ? 'نعم ✅' : 'لا ❌') . "</p>";
        
        if (!extension_loaded('curl')) {
            echo "<h2>5. الحل:</h2>";
            echo "<div class='step'>";
            echo "<h3>اتبع هذه الخطوات بالترتيب:</h3>";
            echo "<ol>";
            echo "<li><strong>افتح XAMPP Control Panel</strong></li>";
            echo "<li><strong>انقر على Config بجانب Apache</strong></li>";
            echo "<li><strong>اختر PHP (php.ini)</strong></li>";
            echo "<li><strong>ابحث عن:</strong> <code>extension=curl</code> أو <code>extension=php_curl.dll</code></li>";
            echo "<li><strong>تأكد من:</strong> أنه بدون <code>;</code> في البداية</li>";
            echo "<li><strong>تأكد من وجود:</strong> <code>extension=openssl</code> قبل CURL</li>";
            echo "<li><strong>احفظ الملف</strong> (Ctrl+S)</li>";
            echo "<li><strong>أعد تشغيل Apache:</strong><br>";
            echo "   XAMPP Control Panel → Stop Apache → انتظر 15 ثانية → Start Apache</li>";
            echo "<li><strong>أعد فتح هذه الصفحة</strong> (Ctrl+F5)</li>";
            echo "</ol>";
            echo "</div>";
            
            echo "<div class='step'>";
            echo "<h3>معلومات إضافية:</h3>";
            $ext_dir = ini_get('extension_dir');
            if (empty($ext_dir)) {
                $ext_dir = 'C:\\xampp\\php\\ext';
            }
            echo "<p><strong>extension_dir:</strong> <code>$ext_dir</code></p>";
            $curl_dll = rtrim($ext_dir, '\\/') . '\\php_curl.dll';
            if (file_exists($curl_dll)) {
                echo "<p class='success'>✅ php_curl.dll موجود</p>";
            } else {
                echo "<p class='error'>❌ php_curl.dll غير موجود في: $curl_dll</p>";
            }
            echo "</div>";
        } else {
            echo "<h2>5. النتيجة:</h2>";
            echo "<div class='success'>";
            echo "<p>✅ كل شيء يعمل بشكل صحيح!</p>";
            echo "<p>يمكنك الآن المتابعة إلى صفحة التثبيت:</p>";
            echo "<a href='index.php/install/index' class='btn'>ابدأ التثبيت</a>";
            echo "</div>";
        }
        ?>
        
        <hr style="margin: 30px 0;">
        <p style="text-align: center;">
            <a href="index.php/install/index" class="btn">صفحة التثبيت</a>
        </p>
    </div>
</body>
</html>
