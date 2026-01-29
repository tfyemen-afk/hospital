<?php
/**
 * الحل النهائي الشامل لـ CURL
 * افتح في المتصفح: http://localhost/hospital/ULTIMATE_CURL_FIX.php
 */

error_reporting(E_ALL);
ini_set('display_errors', 1);

?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>الحل النهائي لـ CURL</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 20px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            direction: rtl;
            min-height: 100vh;
        }
        .container {
            max-width: 1000px;
            margin: 0 auto;
            background: white;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.3);
        }
        .success { 
            color: #27ae60; 
            font-weight: bold; 
            font-size: 18px;
            background: #d4edda;
            padding: 15px;
            border-radius: 5px;
            border: 2px solid #27ae60;
        }
        .error { 
            color: #e74c3c; 
            font-weight: bold; 
            font-size: 18px;
            background: #f8d7da;
            padding: 15px;
            border-radius: 5px;
            border: 2px solid #e74c3c;
        }
        .critical {
            background: #fff3cd;
            padding: 20px;
            border-radius: 10px;
            border: 3px solid #ffc107;
            margin: 20px 0;
        }
        .solution-box {
            background: #e7f3ff;
            padding: 20px;
            border-radius: 10px;
            border-right: 5px solid #3498db;
            margin: 15px 0;
        }
        h1 {
            color: #2c3e50;
            text-align: center;
            border-bottom: 4px solid #3498db;
            padding-bottom: 15px;
        }
        h2 {
            color: #34495e;
            border-bottom: 2px solid #ecf0f1;
            padding-bottom: 10px;
            margin-top: 30px;
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
        .btn {
            display: inline-block;
            padding: 12px 25px;
            background: #3498db;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin: 10px 5px;
            font-weight: bold;
            transition: all 0.3s;
        }
        .btn:hover {
            background: #2980b9;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        }
        .btn-success {
            background: #27ae60;
        }
        .btn-success:hover {
            background: #229954;
        }
        .step {
            background: #f8f9fa;
            padding: 15px;
            margin: 10px 0;
            border-radius: 5px;
            border-right: 4px solid #3498db;
        }
        .step-number {
            display: inline-block;
            width: 30px;
            height: 30px;
            background: #3498db;
            color: white;
            border-radius: 50%;
            text-align: center;
            line-height: 30px;
            margin-left: 10px;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🔥 الحل النهائي الشامل لـ CURL</h1>
        
        <?php
        $php_ini_file = php_ini_loaded_file();
        $ext_dir = ini_get('extension_dir');
        if (empty($ext_dir)) {
            $ext_dir = 'C:\\xampp\\php\\ext';
        }
        
        // فحص CURL
        $curl_loaded = extension_loaded('curl');
        $openssl_loaded = extension_loaded('openssl');
        
        echo "<div class='critical'>";
        echo "<h2>📊 الحالة الحالية</h2>";
        
        if ($curl_loaded) {
            echo "<p class='success'>✅ CURL مفعل بنجاح!</p>";
            $version = curl_version();
            echo "<p><strong>إصدار CURL:</strong> " . $version['version'] . "</p>";
            echo "<p><strong>SSL Version:</strong> " . $version['ssl_version'] . "</p>";
        } else {
            echo "<p class='error'>❌ CURL غير مفعل</p>";
        }
        
        if ($openssl_loaded) {
            echo "<p class='success'>✅ OpenSSL مفعل</p>";
        } else {
            echo "<p class='error'>❌ OpenSSL غير مفعل</p>";
        }
        echo "</div>";
        
        if (!$curl_loaded) {
            echo "<div class='solution-box'>";
            echo "<h2>🔧 الحل الشامل - اتبع الخطوات بالترتيب</h2>";
            
            // الخطوة 1
            echo "<div class='step'>";
            echo "<span class='step-number'>1</span>";
            echo "<h3>افتح ملف php.ini الصحيح</h3>";
            echo "<p><strong>مسار الملف:</strong></p>";
            echo "<code>$php_ini_file</code>";
            echo "<p><strong>أو من XAMPP Control Panel:</strong></p>";
            echo "<ol>";
            echo "<li>افتح XAMPP Control Panel</li>";
            echo "<li>انقر على <strong>Config</strong> بجانب Apache</li>";
            echo "<li>اختر <strong>PHP (php.ini)</strong></li>";
            echo "</ol>";
            echo "</div>";
            
            // الخطوة 2
            echo "<div class='step'>";
            echo "<span class='step-number'>2</span>";
            echo "<h3>ابحث عن CURL و OpenSSL</h3>";
            echo "<p>في ملف php.ini، اضغط <strong>Ctrl+F</strong> وابحث عن:</p>";
            echo "<code>extension=curl</code>";
            echo "<p>أو</p>";
            echo "<code>extension=php_curl.dll</code>";
            echo "<p>و</p>";
            echo "<code>extension=openssl</code>";
            echo "<p>أو</p>";
            echo "<code>extension=php_openssl.dll</code>";
            echo "</div>";
            
            // الخطوة 3
            echo "<div class='step'>";
            echo "<span class='step-number'>3</span>";
            echo "<h3>تأكد من التفعيل الصحيح</h3>";
            echo "<p><strong>يجب أن تكون الأسطر هكذا (بدون ; في البداية):</strong></p>";
            echo "<code>extension=openssl<br>extension=php_curl.dll</code>";
            echo "<p><strong>إذا كانت هكذا (مع ; في البداية)، احذف ;:</strong></p>";
            echo "<code>;extension=curl  ❌ خطأ<br>extension=curl  ✅ صحيح</code>";
            echo "</div>";
            
            // الخطوة 4
            echo "<div class='step'>";
            echo "<span class='step-number'>4</span>";
            echo "<h3>إذا لم تجد CURL في الملف</h3>";
            echo "<p>أضف هذه الأسطر في نهاية قسم الامتدادات (بعد السطر 940 تقريباً):</p>";
            echo "<code>extension=openssl<br>extension=php_curl.dll</code>";
            echo "</div>";
            
            // الخطوة 5
            echo "<div class='step'>";
            echo "<span class='step-number'>5</span>";
            echo "<h3>احفظ الملف</h3>";
            echo "<p>اضغط <strong>Ctrl+S</strong> لحفظ الملف</p>";
            echo "<p><strong>مهم:</strong> تأكد من حفظ الملف بنجاح</p>";
            echo "</div>";
            
            // الخطوة 6
            echo "<div class='step'>";
            echo "<span class='step-number'>6</span>";
            echo "<h3>أعد تشغيل Apache (مهم جداً!)</h3>";
            echo "<p><strong>الطريقة الصحيحة:</strong></p>";
            echo "<ol>";
            echo "<li>افتح <strong>XAMPP Control Panel</strong></li>";
            echo "<li>انقر على <strong>Stop</strong> بجانب Apache</li>";
            echo "<li><strong>انتظر 15 ثانية</strong> حتى يتوقف تماماً (تأكد من اختفاء PID)</li>";
            echo "<li>انقر على <strong>Start</strong> بجانب Apache</li>";
            echo "<li><strong>انتظر 15 ثانية</strong> حتى يبدأ تماماً</li>";
            echo "<li>تأكد من أن Apache يعمل (باللون الأخضر)</li>";
            echo "</ol>";
            echo "<p class='error'><strong>⚠️ لا تكفي إعادة تحميل الصفحة - يجب إعادة تشغيل Apache!</strong></p>";
            echo "</div>";
            
            // الخطوة 7
            echo "<div class='step'>";
            echo "<span class='step-number'>7</span>";
            echo "<h3>اختبر مرة أخرى</h3>";
            echo "<p>بعد إعادة تشغيل Apache:</p>";
            echo "<ol>";
            echo "<li>أعد فتح هذه الصفحة: <code>http://localhost/hospital/ULTIMATE_CURL_FIX.php</code></li>";
            echo "<li>اضغط <strong>Ctrl+F5</strong> لإعادة التحميل الكامل</li>";
            echo "<li>تحقق من أن CURL مفعل</li>";
            echo "</ol>";
            echo "</div>";
            
            echo "</div>";
            
            // معلومات إضافية
            echo "<div class='solution-box'>";
            echo "<h2>📋 معلومات إضافية</h2>";
            echo "<p><strong>ملف php.ini المستخدم:</strong> <code>$php_ini_file</code></p>";
            echo "<p><strong>extension_dir:</strong> <code>$ext_dir</code></p>";
            
            $curl_dll = rtrim($ext_dir, '\\/') . '\\php_curl.dll';
            if (file_exists($curl_dll)) {
                echo "<p class='success'>✅ php_curl.dll موجود</p>";
            } else {
                echo "<p class='error'>❌ php_curl.dll غير موجود في: $curl_dll</p>";
                echo "<p>قد تحتاج إلى إعادة تثبيت XAMPP</p>";
            }
            
            echo "<p><strong>إصدار PHP:</strong> " . PHP_VERSION . "</p>";
            echo "<p><strong>Server API:</strong> " . php_sapi_name() . "</p>";
            echo "</div>";
            
            // حلول بديلة
            echo "<div class='critical'>";
            echo "<h2>🆘 إذا لم يعمل بعد كل هذا</h2>";
            echo "<h3>الحل البديل 1: استخدام الصيغة الكاملة</h3>";
            echo "<p>في ملف php.ini، استخدم:</p>";
            echo "<code>extension=php_curl.dll</code>";
            echo "<p>بدلاً من:</p>";
            echo "<code>extension=curl</code>";
            
            echo "<h3>الحل البديل 2: إعادة تثبيت XAMPP</h3>";
            echo "<p>إذا لم يعمل أي شيء:</p>";
            echo "<ol>";
            echo "<li>احتفظ بنسخة احتياطية من قاعدة البيانات</li>";
            echo "<li>احذف XAMPP</li>";
            echo "<li>حمّل أحدث إصدار من XAMPP</li>";
            echo "<li>ثبّت XAMPP</li>";
            echo "<li>فعّل CURL من البداية</li>";
            echo "</ol>";
            
            echo "<h3>الحل البديل 3: استخدام PHP CLI للتحقق</h3>";
            echo "<p>افتح Command Prompt واكتب:</p>";
            echo "<code>cd C:\\xampp\\php<br>php -m | findstr curl</code>";
            echo "<p>إذا ظهر 'curl'، فالمشكلة في Apache وليس PHP</p>";
            echo "</div>";
        } else {
            echo "<div class='solution-box'>";
            echo "<h2>🎉 تم حل المشكلة!</h2>";
            echo "<p class='success'>CURL يعمل الآن بشكل صحيح</p>";
            echo "<p>يمكنك الآن المتابعة إلى صفحة التثبيت:</p>";
            echo "<a href='index.php/install/index' class='btn btn-success'>ابدأ التثبيت</a>";
            echo "</div>";
        }
        ?>
        
        <hr style="margin: 30px 0;">
        <div style="text-align: center;">
            <a href="complete_curl_check.php" class="btn">فحص شامل</a>
            <a href="phpinfo.php" class="btn">phpinfo</a>
            <a href="check_system.php" class="btn">فحص النظام</a>
        </div>
    </div>
</body>
</html>
