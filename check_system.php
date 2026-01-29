<?php
/**
 * ملف فحص شامل للنظام
 * افتح في المتصفح: http://localhost/hospital/check_system.php
 */

error_reporting(E_ALL);
ini_set('display_errors', 1);

?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>فحص النظام - منصة المستشفى</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f5f5f5;
            padding: 20px;
            direction: rtl;
        }
        .container {
            max-width: 900px;
            margin: 0 auto;
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        h1 {
            color: #2c3e50;
            border-bottom: 3px solid #3498db;
            padding-bottom: 10px;
        }
        .section {
            margin: 20px 0;
            padding: 15px;
            background: #f9f9f9;
            border-radius: 5px;
        }
        .success {
            color: #27ae60;
            font-weight: bold;
        }
        .error {
            color: #e74c3c;
            font-weight: bold;
        }
        .warning {
            color: #f39c12;
            font-weight: bold;
        }
        .info {
            color: #3498db;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 10px 0;
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
        .btn {
            display: inline-block;
            padding: 10px 20px;
            background: #3498db;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin: 10px 5px;
        }
        .btn:hover {
            background: #2980b9;
        }
        .btn-success {
            background: #27ae60;
        }
        .btn-success:hover {
            background: #229954;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🔍 فحص شامل للنظام</h1>
        
        <?php
        $all_ok = true;
        $errors = [];
        $warnings = [];
        $success = [];
        
        // 1. فحص إصدار PHP
        echo "<div class='section'>";
        echo "<h2>1. إصدار PHP</h2>";
        $php_version = phpversion();
        if (version_compare($php_version, '5.6', '>=')) {
            echo "<p class='success'>✅ إصدار PHP: $php_version (ممتاز)</p>";
            $success[] = "PHP $php_version";
        } else {
            echo "<p class='error'>❌ إصدار PHP قديم: $php_version (يحتاج 5.6 أو أحدث)</p>";
            $errors[] = "PHP version too old";
            $all_ok = false;
        }
        echo "</div>";
        
        // 2. فحص امتدادات PHP
        echo "<div class='section'>";
        echo "<h2>2. امتدادات PHP المطلوبة</h2>";
        echo "<table>";
        echo "<tr><th>الامتداد</th><th>الحالة</th><th>الإصدار/المعلومات</th></tr>";
        
        $required_extensions = [
            'mysqli' => 'MySQLi',
            'mbstring' => 'MBString',
            'curl' => 'CURL',
            'zip' => 'ZIP',
            'gd' => 'GD',
            'openssl' => 'OpenSSL'
        ];
        
        foreach ($required_extensions as $ext => $name) {
            if (extension_loaded($ext)) {
                $info = '';
                if ($ext === 'curl' && function_exists('curl_version')) {
                    $curl_info = curl_version();
                    $info = $curl_info['version'];
                } elseif ($ext === 'gd' && function_exists('gd_info')) {
                    $gd_info = gd_info();
                    $info = $gd_info['GD Version'];
                } else {
                    $info = 'مفعّل';
                }
                echo "<tr><td><strong>$name</strong></td><td class='success'>✅ مفعّل</td><td>$info</td></tr>";
                $success[] = "$name extension";
            } else {
                echo "<tr><td><strong>$name</strong></td><td class='error'>❌ غير مفعّل</td><td>-</td></tr>";
                $errors[] = "$name extension not loaded";
                $all_ok = false;
            }
        }
        echo "</table>";
        echo "</div>";
        
        // 3. فحص allow_url_fopen
        echo "<div class='section'>";
        echo "<h2>3. إعدادات PHP</h2>";
        if (ini_get('allow_url_fopen')) {
            echo "<p class='success'>✅ allow_url_fopen: مفعّل</p>";
            $success[] = "allow_url_fopen enabled";
        } else {
            echo "<p class='warning'>⚠️ allow_url_fopen: غير مفعّل (قد يسبب مشاكل)</p>";
            $warnings[] = "allow_url_fopen disabled";
        }
        echo "</div>";
        
        // 4. فحص المجلدات والصلاحيات
        echo "<div class='section'>";
        echo "<h2>4. المجلدات والصلاحيات</h2>";
        echo "<table>";
        echo "<tr><th>المجلد</th><th>الحالة</th></tr>";
        
        $folders = [
            'mvc/config' => 'ملف الإعدادات',
            'mvc/libraries' => 'المكتبات',
            'uploads' => 'مجلد الرفع'
        ];
        
        foreach ($folders as $folder => $name) {
            $path = __DIR__ . '/' . $folder;
            if (is_dir($path) || is_file($path)) {
                if (is_writable($path)) {
                    echo "<tr><td>$name ($folder)</td><td class='success'>✅ قابل للكتابة</td></tr>";
                    $success[] = "$name writable";
                } else {
                    echo "<tr><td>$name ($folder)</td><td class='warning'>⚠️ غير قابل للكتابة</td></tr>";
                    $warnings[] = "$name not writable";
                }
            } else {
                echo "<tr><td>$name ($folder)</td><td class='error'>❌ غير موجود</td></tr>";
                $errors[] = "$name not found";
                $all_ok = false;
            }
        }
        echo "</table>";
        echo "</div>";
        
        // 5. فحص قاعدة البيانات
        echo "<div class='section'>";
        echo "<h2>5. اتصال قاعدة البيانات</h2>";
        
        // قراءة إعدادات قاعدة البيانات
        $db_config_file = __DIR__ . '/mvc/config/database.php';
        if (file_exists($db_config_file)) {
            include($db_config_file);
            
            if (!empty($db['default']['hostname']) && !empty($db['default']['database'])) {
                $host = $db['default']['hostname'];
                $user = $db['default']['username'];
                $pass = $db['default']['password'];
                $dbname = $db['default']['database'];
                
                echo "<p class='info'>📋 معلومات قاعدة البيانات:</p>";
                echo "<ul>";
                echo "<li>Host: $host</li>";
                echo "<li>Database: $dbname</li>";
                echo "<li>Username: $user</li>";
                echo "</ul>";
                
                // محاولة الاتصال
                $conn = @mysqli_connect($host, $user, $pass, $dbname);
                if ($conn) {
                    echo "<p class='success'>✅ الاتصال بقاعدة البيانات نجح!</p>";
                    mysqli_close($conn);
                    $success[] = "Database connection";
                } else {
                    echo "<p class='error'>❌ فشل الاتصال بقاعدة البيانات: " . mysqli_connect_error() . "</p>";
                    $errors[] = "Database connection failed";
                    $all_ok = false;
                }
            } else {
                echo "<p class='warning'>⚠️ إعدادات قاعدة البيانات غير محددة بعد</p>";
                $warnings[] = "Database not configured";
            }
        } else {
            echo "<p class='error'>❌ ملف إعدادات قاعدة البيانات غير موجود</p>";
            $errors[] = "Database config file not found";
            $all_ok = false;
        }
        echo "</div>";
        
        // 6. معلومات النظام
        echo "<div class='section'>";
        echo "<h2>6. معلومات النظام</h2>";
        echo "<table>";
        echo "<tr><th>المعلومة</th><th>القيمة</th></tr>";
        echo "<tr><td>مسار php.ini</td><td>" . php_ini_loaded_file() . "</td></tr>";
        echo "<tr><td>مسار PHP</td><td>" . PHP_BINARY . "</td></tr>";
        echo "<tr><td>نظام التشغيل</td><td>" . PHP_OS . "</td></tr>";
        echo "<tr><td>Server API</td><td>" . php_sapi_name() . "</td></tr>";
        echo "</table>";
        echo "</div>";
        
        // الملخص النهائي
        echo "<div class='section'>";
        echo "<h2>📊 الملخص</h2>";
        echo "<p class='info'>✅ النجاحات: " . count($success) . "</p>";
        echo "<p class='warning'>⚠️ التحذيرات: " . count($warnings) . "</p>";
        echo "<p class='error'>❌ الأخطاء: " . count($errors) . "</p>";
        
        if ($all_ok && empty($errors)) {
            echo "<h3 class='success'>🎉 النظام جاهز للتثبيت!</h3>";
            echo "<a href='index.php/install/index' class='btn btn-success'>ابدأ التثبيت الآن</a>";
        } else {
            echo "<h3 class='error'>⚠️ يرجى إصلاح الأخطاء قبل المتابعة</h3>";
            if (in_array('CURL extension not loaded', $errors)) {
                echo "<p class='error'>🔧 CURL غير مفعّل. راجع ملف ENABLE_CURL_AR.md</p>";
            }
        }
        echo "</div>";
        ?>
        
        <div style="margin-top: 30px; text-align: center;">
            <a href="test_curl.php" class="btn">اختبار CURL</a>
            <a href="index.php/install/index" class="btn btn-success">صفحة التثبيت</a>
        </div>
    </div>
</body>
</html>
