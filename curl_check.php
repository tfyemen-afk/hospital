<?php
/**
 * فحص CURL بسيط
 * افتح: http://localhost/hospital/curl_check.php
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
            max-width: 600px;
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
        }
        .error {
            color: #e74c3c;
            font-size: 24px;
            font-weight: bold;
            padding: 20px;
            background: #f8d7da;
            border-radius: 5px;
            margin: 20px 0;
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
        }
        h1 {
            color: #2c3e50;
            text-align: center;
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
        } else {
            echo "<div class='error'>❌ CURL غير مفعل</div>";
        }
        
        echo "<h2>2. معلومات PHP:</h2>";
        echo "<p><strong>إصدار PHP:</strong> " . PHP_VERSION . "</p>";
        echo "<p><strong>ملف php.ini:</strong></p>";
        echo "<code>" . php_ini_loaded_file() . "</code>";
        
        echo "<h2>3. الحل:</h2>";
        if (!extension_loaded('curl')) {
            echo "<ol>";
            echo "<li>افتح XAMPP Control Panel</li>";
            echo "<li>Config → PHP (php.ini)</li>";
            echo "<li>ابحث عن: <code>extension=curl</code></li>";
            echo "<li>احذف <code>;</code> من البداية إذا كان موجوداً</li>";
            echo "<li>احفظ الملف</li>";
            echo "<li><strong>أعد تشغيل Apache</strong> (Stop → Start)</li>";
            echo "<li>أعد فتح هذه الصفحة</li>";
            echo "</ol>";
        }
        ?>
        
        <hr>
        <p style="text-align: center;">
            <a href="test.php" style="padding: 10px 20px; background: #3498db; color: white; text-decoration: none; border-radius: 5px;">اختبار PHP</a>
            <a href="index.php/install/index" style="padding: 10px 20px; background: #27ae60; color: white; text-decoration: none; border-radius: 5px; margin-right: 10px;">صفحة التثبيت</a>
        </p>
    </div>
</body>
</html>
