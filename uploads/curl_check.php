<?php
/**
 * فحص CURL - في مجلد uploads
 * افتح: http://localhost:8080/hospital/uploads/curl_check.php
 */
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>فحص CURL</title>
    <style>
        body { font-family: Arial; padding: 40px; background: #f0f0f0; direction: rtl; }
        .box { max-width: 700px; margin: 0 auto; background: white; padding: 30px; border-radius: 10px; }
        .success { color: #27ae60; font-size: 24px; font-weight: bold; padding: 20px; background: #d4edda; border-radius: 5px; margin: 20px 0; }
        .error { color: #e74c3c; font-size: 24px; font-weight: bold; padding: 20px; background: #f8d7da; border-radius: 5px; margin: 20px 0; }
        code { background: #2c3e50; color: #ecf0f1; padding: 10px; border-radius: 5px; display: block; margin: 10px 0; direction: ltr; }
        h1 { color: #2c3e50; text-align: center; }
    </style>
</head>
<body>
    <div class="box">
        <h1>🔍 فحص CURL</h1>
        <?php
        if (extension_loaded('curl')) {
            echo "<div class='success'>✅ CURL مفعل!</div>";
            $v = curl_version();
            echo "<p>الإصدار: " . $v['version'] . "</p>";
        } else {
            echo "<div class='error'>❌ CURL غير مفعل</div>";
            echo "<p><strong>الحل:</strong></p>";
            echo "<ol>";
            echo "<li>XAMPP Control Panel → Config → PHP (php.ini)</li>";
            echo "<li>ابحث عن: <code>extension=php_curl.dll</code></li>";
            echo "<li>احذف <code>;</code> من البداية إذا كان موجوداً</li>";
            echo "<li>احفظ الملف</li>";
            echo "<li><strong>أعد تشغيل Apache</strong></li>";
            echo "</ol>";
        }
        echo "<p><strong>ملف php.ini:</strong><br><code>" . php_ini_loaded_file() . "</code></p>";
        ?>
        <hr>
        <p><a href="../index.php/install/index" style="padding: 10px 20px; background: #27ae60; color: white; text-decoration: none; border-radius: 5px;">صفحة التثبيت</a></p>
    </div>
</body>
</html>
