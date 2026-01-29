<?php
/**
 * فحص CURL - في مجلد assets (لا يتأثر بـ .htaccess)
 * افتح: http://localhost:8080/hospital/assets/check_curl.php
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
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            direction: rtl;
            min-height: 100vh;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.3);
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
        .info {
            background: #e8f4f8;
            padding: 15px;
            border-radius: 5px;
            margin: 15px 0;
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
            border-bottom: 4px solid #3498db;
            padding-bottom: 15px;
        }
        .step {
            background: #f8f9fa;
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
    <div class="container">
        <h1>🔍 فحص CURL</h1>
        
        <?php
        echo "<h2>1. حالة CURL:</h2>";
        if (extension_loaded('curl')) {
            echo "<div class='success'>✅ CURL مفعل بنجاح!</div>";
            $version = curl_version();
            echo "<div class='info'>";
            echo "<strong>معلومات CURL:</strong><br>";
            echo "الإصدار: " . $version['version'] . "<br>";
            echo "SSL Version: " . $version['ssl_version'] . "<br>";
            echo "</div>";
        } else {
            echo "<div class='error'>❌ CURL غير مفعل</div>";
        }
        
        echo "<h2>2. معلومات PHP:</h2>";
        echo "<div class='info'>";
        echo "<strong>إصدار PHP:</strong> " . PHP_VERSION . "<br>";
        echo "<strong>ملف php.ini:</strong><br>";
        echo "<code>" . php_ini_loaded_file() . "</code>";
        echo "</div>";
        
        echo "<h2>3. حالة OpenSSL:</h2>";
        if (extension_loaded('openssl')) {
            echo "<div class='success'>✅ OpenSSL مفعل</div>";
        } else {
            echo "<div class='error'>❌ OpenSSL غير مفعل</div>";
        }
        
        if (!extension_loaded('curl')) {
            echo "<h2>4. الحل:</h2>";
            echo "<div class='step'>";
            echo "<h3>اتبع هذه الخطوات:</h3>";
            echo "<ol>";
            echo "<li>XAMPP Control Panel → Config → PHP (php.ini)</li>";
            echo "<li>ابحث عن: <code>extension=php_curl.dll</code></li>";
            echo "<li>تأكد من أنه بدون <code>;</code> في البداية</li>";
            echo "<li>احفظ الملف</li>";
            echo "<li><strong>أعد تشغيل Apache</strong> (Stop → انتظر 15 ثانية → Start)</li>";
            echo "<li>أعد فتح هذه الصفحة</li>";
            echo "</ol>";
            echo "</div>";
        } else {
            echo "<h2>4. النتيجة:</h2>";
            echo "<div class='success'>";
            echo "<p>✅ كل شيء يعمل بشكل صحيح!</p>";
            echo "<p>يمكنك الآن المتابعة إلى صفحة التثبيت:</p>";
            echo "<a href='../index.php/install/index' class='btn'>ابدأ التثبيت</a>";
            echo "</div>";
        }
        ?>
        
        <hr style="margin: 30px 0;">
        <p style="text-align: center;">
            <a href="../index.php/install/index" class="btn">صفحة التثبيت</a>
        </p>
    </div>
</body>
</html>
