<?php
/**
 * اختبار الاتصال بـ MySQL
 * افتح: http://localhost:8080/hospital/assets/test_mysql.php
 */

error_reporting(E_ALL);
ini_set('display_errors', 1);

?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>اختبار MySQL</title>
    <style>
        body { font-family: Arial; padding: 40px; background: #f0f0f0; direction: rtl; }
        .box { max-width: 700px; margin: 0 auto; background: white; padding: 30px; border-radius: 10px; }
        .success { color: #27ae60; font-size: 20px; font-weight: bold; padding: 15px; background: #d4edda; border-radius: 5px; margin: 15px 0; }
        .error { color: #e74c3c; font-size: 20px; font-weight: bold; padding: 15px; background: #f8d7da; border-radius: 5px; margin: 15px 0; }
        .info { background: #e8f4f8; padding: 15px; border-radius: 5px; margin: 15px 0; }
        code { background: #2c3e50; color: #ecf0f1; padding: 10px; border-radius: 5px; display: block; margin: 10px 0; direction: ltr; }
        h1 { color: #2c3e50; text-align: center; }
    </style>
</head>
<body>
    <div class="box">
        <h1>🔍 اختبار الاتصال بـ MySQL</h1>
        
        <?php
        $hosts = ['localhost', '127.0.0.1'];
        $user = 'root';
        $pass = '';
        $db = 'hospital';
        
        echo "<div class='info'>";
        echo "<h2>1. معلومات الاتصال:</h2>";
        echo "<p><strong>Host:</strong> localhost</p>";
        echo "<p><strong>Database:</strong> $db</p>";
        echo "<p><strong>Username:</strong> $user</p>";
        echo "<p><strong>Password:</strong> (فارغ)</p>";
        echo "</div>";
        
        $connected = false;
        $working_host = '';
        
        foreach ($hosts as $host) {
            echo "<h2>2. محاولة الاتصال بـ: $host</h2>";
            
            $conn = @mysqli_connect($host, $user, $pass);
            
            if ($conn) {
                echo "<div class='success'>✅ نجح الاتصال!</div>";
                
                // محاولة الاتصال بقاعدة البيانات
                if (@mysqli_select_db($conn, $db)) {
                    echo "<div class='success'>✅ قاعدة البيانات '$db' موجودة ومتاحة!</div>";
                    $connected = true;
                    $working_host = $host;
                    mysqli_close($conn);
                    break;
                } else {
                    echo "<div class='error'>⚠️ الاتصال نجح لكن قاعدة البيانات '$db' غير موجودة</div>";
                    echo "<p><strong>الحل:</strong> أنشئ قاعدة البيانات من phpMyAdmin</p>";
                    mysqli_close($conn);
                }
            } else {
                $error = mysqli_connect_error();
                echo "<div class='error'>❌ فشل الاتصال: $error</div>";
            }
        }
        
        if ($connected) {
            echo "<div class='success'>";
            echo "<h2>3. النتيجة:</h2>";
            echo "<p>✅ كل شيء يعمل بشكل صحيح!</p>";
            echo "<p><strong>استخدم هذه المعلومات في صفحة التثبيت:</strong></p>";
            echo "<ul>";
            echo "<li><strong>اسم المضيف:</strong> $working_host</li>";
            echo "<li><strong>قاعدة البيانات:</strong> $db</li>";
            echo "<li><strong>اسم المستخدم:</strong> $user</li>";
            echo "<li><strong>كلمة المرور:</strong> (فارغ)</li>";
            echo "</ul>";
            echo "</div>";
        } else {
            echo "<div class='error'>";
            echo "<h2>3. الحل:</h2>";
            echo "<ol>";
            echo "<li><strong>تأكد من أن MySQL يعمل:</strong><br>";
            echo "   XAMPP Control Panel → MySQL يجب أن يكون باللون الأخضر</li>";
            echo "<li><strong>إذا لم يكن يعمل:</strong><br>";
            echo "   انقر على Start بجانب MySQL</li>";
            echo "<li><strong>أنشئ قاعدة البيانات:</strong><br>";
            echo "   افتح: <a href='http://localhost:8080/phpmyadmin' target='_blank'>http://localhost:8080/phpmyadmin</a><br>";
            echo "   أنشئ قاعدة بيانات باسم: <code>$db</code></li>";
            echo "<li><strong>أعد فتح هذه الصفحة</strong></li>";
            echo "</ol>";
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
