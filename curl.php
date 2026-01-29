<?php
/**
 * فحص CURL بسيط
 * افتح: http://localhost/hospital/curl.php
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
            max-width: 700px;
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
        
        if (!extension_loaded('curl')) {
            echo "<h2>4. الحل:</h2>";
            echo "<div class='step'>";
            echo "<ol>";
            echo "<li><strong>افتح XAMPP Control Panel</strong></li>";
            echo "<li><strong>انقر على Config بجانب Apache</strong></li>";
            echo "<li><strong>اختر PHP (php.ini)</strong></li>";
            echo "<li><strong>ابحث عن:</strong> <code>extension=curl</code> أو <code>extension=php_curl.dll</code></li>";
            echo "<li><strong>تأكد من:</strong> أنه بدون <code>;</code> في البداية</li>";
            echo "<li><strong>احفظ الملف</strong> (Ctrl+S)</li>";
            echo "<li><strong>أعد تشغيل Apache:</strong><br>";
            echo "   Stop Apache → انتظر 15 ثانية → Start Apache</li>";
            echo "<li><strong>أعد فتح هذه الصفحة</strong></li>";
            echo "</ol>";
            echo "</div>";
        } else {
            echo "<h2>4. النتيجة:</h2>";
            echo "<div class='success'>";
            echo "<p>✅ كل شيء يعمل بشكل صحيح!</p>";
            echo "<p>يمكنك الآن المتابعة إلى صفحة التثبيت:</p>";
            echo "<a href='index.php/install/index' style='display: inline-block; padding: 10px 20px; background: #27ae60; color: white; text-decoration: none; border-radius: 5px; margin-top: 10px;'>ابدأ التثبيت</a>";
            echo "</div>";
        }
        ?>
        
        <hr style="margin: 30px 0;">
        <p style="text-align: center;">
            <a href="test.php" style="padding: 10px 20px; background: #3498db; color: white; text-decoration: none; border-radius: 5px; margin: 5px;">اختبار PHP</a>
            <a href="index.php/install/index" style="padding: 10px 20px; background: #27ae60; color: white; text-decoration: none; border-radius: 5px; margin: 5px;">صفحة التثبيت</a>
        </p>
    </div>
</body>
</html>
