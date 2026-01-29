<?php
/**
 * التحقق النهائي من CURL
 * افتح: http://localhost:8080/hospital/assets/VERIFY_CURL_FIX.php
 */
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>التحقق النهائي من CURL</title>
    <style>
        body { font-family: Arial; padding: 40px; background: #f0f0f0; direction: rtl; }
        .box { max-width: 800px; margin: 0 auto; background: white; padding: 30px; border-radius: 10px; }
        .success { color: #27ae60; font-size: 24px; font-weight: bold; padding: 20px; background: #d4edda; border-radius: 5px; margin: 20px 0; }
        .error { color: #e74c3c; font-size: 24px; font-weight: bold; padding: 20px; background: #f8d7da; border-radius: 5px; margin: 20px 0; }
        .info { background: #e8f4f8; padding: 15px; border-radius: 5px; margin: 15px 0; }
        code { background: #2c3e50; color: #ecf0f1; padding: 10px; border-radius: 5px; display: block; margin: 10px 0; direction: ltr; }
        h1 { color: #2c3e50; text-align: center; }
    </style>
</head>
<body>
    <div class="box">
        <h1>🔍 التحقق النهائي من CURL</h1>
        
        <?php
        $php_ini = php_ini_loaded_file();
        
        echo "<div class='info'>";
        echo "<h2>1. معلومات PHP:</h2>";
        echo "<p><strong>إصدار PHP:</strong> " . PHP_VERSION . "</p>";
        echo "<p><strong>ملف php.ini المستخدم:</strong></p>";
        echo "<code>$php_ini</code>";
        echo "</div>";
        
        echo "<h2>2. حالة CURL:</h2>";
        if (extension_loaded('curl')) {
            echo "<div class='success'>✅ CURL مفعل بنجاح!</div>";
            $v = curl_version();
            echo "<p>الإصدار: " . $v['version'] . "</p>";
            echo "<p>SSL: " . $v['ssl_version'] . "</p>";
        } else {
            echo "<div class='error'>❌ CURL لا يزال غير مفعل</div>";
            
            echo "<div class='info'>";
            echo "<h3>التحقق من ملف php.ini:</h3>";
            if ($php_ini && file_exists($php_ini)) {
                $content = file_get_contents($php_ini);
                $lines = explode("\n", $content);
                $curl_line = '';
                $curl_line_num = 0;
                
                foreach ($lines as $num => $line) {
                    if (preg_match('/^\s*;?\s*extension\s*=\s*(curl|php_curl\.dll)/i', trim($line))) {
                        $curl_line = trim($line);
                        $curl_line_num = $num + 1;
                        break;
                    }
                }
                
                if ($curl_line) {
                    echo "<p><strong>السطر $curl_line_num:</strong></p>";
                    echo "<code>$curl_line</code>";
                    
                    if (strpos($curl_line, ';') === 0) {
                        echo "<p class='error'>❌ CURL معطل (يوجد ; في البداية)</p>";
                        echo "<p><strong>الحل:</strong> احذف ; من بداية السطر $curl_line_num</p>";
                    } else {
                        echo "<p class='success'>✅ CURL مفعل في الملف</p>";
                        echo "<p class='error'>⚠️ لكنه غير محمّل في PHP!</p>";
                        echo "<p><strong>الحل:</strong> أعد تشغيل Apache الآن!</p>";
                    }
                } else {
                    echo "<p class='error'>❌ CURL غير موجود في الملف</p>";
                    echo "<p><strong>الحل:</strong> أضف السطر التالي في ملف php.ini:</p>";
                    echo "<code>extension=curl</code>";
                }
            }
            echo "</div>";
        }
        
        echo "<h2>3. حالة OpenSSL:</h2>";
        if (extension_loaded('openssl')) {
            echo "<div class='success'>✅ OpenSSL مفعل</div>";
        } else {
            echo "<div class='error'>❌ OpenSSL غير مفعل</div>";
        }
        
        if (!extension_loaded('curl')) {
            echo "<div class='info'>";
            echo "<h2>4. الحل النهائي:</h2>";
            echo "<ol>";
            echo "<li><strong>افتح ملف php.ini:</strong><br>";
            echo "<code>$php_ini</code></li>";
            echo "<li><strong>ابحث عن:</strong> <code>extension=curl</code> أو <code>extension=php_curl.dll</code></li>";
            echo "<li><strong>تأكد من:</strong> أنه بدون <code>;</code> في البداية</li>";
            echo "<li><strong>احفظ الملف</strong></li>";
            echo "<li><strong>أعد تشغيل Apache:</strong><br>";
            echo "   استخدم ملف: <code>force_restart_apache.bat</code><br>";
            echo "   أو من XAMPP Control Panel: Stop → انتظر 15 ثانية → Start</li>";
            echo "<li><strong>أعد فتح هذه الصفحة</strong></li>";
            echo "</ol>";
            echo "</div>";
        } else {
            echo "<div class='success'>";
            echo "<h2>4. النتيجة:</h2>";
            echo "<p>✅ كل شيء يعمل بشكل صحيح!</p>";
            echo "<p>يمكنك الآن المتابعة إلى صفحة التثبيت:</p>";
            echo "<a href='../index.php/install/index' style='display: inline-block; padding: 12px 25px; background: #27ae60; color: white; text-decoration: none; border-radius: 5px; margin-top: 10px;'>ابدأ التثبيت</a>";
            echo "</div>";
        }
        ?>
        
        <hr style="margin: 30px 0;">
        <p style="text-align: center;">
            <a href="../index.php/install/index" style="padding: 10px 20px; background: #3498db; color: white; text-decoration: none; border-radius: 5px; margin: 5px;">صفحة التثبيت</a>
        </p>
    </div>
</body>
</html>
