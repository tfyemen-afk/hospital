# كيفية تفعيل CURL PHP في XAMPP

## الخطوات المطلوبة

### الطريقة 1: من خلال XAMPP Control Panel (الأسهل)

1. **افتح XAMPP Control Panel**
   - انقر بزر الماوس الأيمن على XAMPP Control Panel
   - اختر "Run as administrator" (تشغيل كمسؤول)

2. **افتح ملف php.ini**
   - في XAMPP Control Panel، انقر على زر **Config** بجانب Apache
   - اختر **PHP (php.ini)** من القائمة

3. **ابحث عن extension=curl**
   - اضغط `Ctrl + F` للبحث
   - ابحث عن: `extension=curl`
   - ستجد سطراً مثل: `;extension=curl` (مع فاصلة منقوطة في البداية)

4. **فعّل CURL**
   - احذف الفاصلة المنقوطة `;` من بداية السطر
   - يجب أن يصبح السطر: `extension=curl`
   - احفظ الملف (`Ctrl + S`)

5. **أعد تشغيل Apache**
   - في XAMPP Control Panel، أوقف Apache ثم شغّله مرة أخرى
   - أو انقر على **Stop** ثم **Start** بجانب Apache

### الطريقة 2: فتح ملف php.ini يدوياً

1. **حدد موقع ملف php.ini**
   - عادة يكون في: `C:\xampp\php\php.ini`
   - أو يمكنك فتحه من XAMPP Control Panel → Config → PHP (php.ini)

2. **افتح الملف**
   - افتح الملف باستخدام Notepad++ أو أي محرر نصوص
   - **مهم:** افتحه كمسؤول (Run as administrator)

3. **ابحث عن extension=curl**
   - اضغط `Ctrl + F`
   - ابحث عن: `extension=curl`
   - ستجد سطراً مثل: `;extension=curl`

4. **فعّل CURL**
   - احذف `;` من بداية السطر
   - يجب أن يصبح: `extension=curl`

5. **احفظ الملف وأعد تشغيل Apache**

### الطريقة 3: التحقق من وجود ملف php_curl.dll

إذا لم يعمل CURL بعد التفعيل:

1. **تحقق من وجود الملف**
   - اذهب إلى: `C:\xampp\php\ext\`
   - تأكد من وجود ملف: `php_curl.dll`

2. **إذا كان الملف غير موجود**
   - قد تحتاج إلى إعادة تثبيت XAMPP
   - أو نسخ الملف من نسخة أخرى من PHP

## التحقق من تفعيل CURL

### الطريقة 1: من خلال phpinfo()

1. أنشئ ملف `test_curl.php` في مجلد `htdocs`:
   ```php
   <?php
   phpinfo();
   ?>
   ```

2. افتح المتصفح: `http://localhost/test_curl.php`
3. ابحث عن "curl" في الصفحة
4. إذا ظهر قسم "curl" مع معلومات، فمعنى ذلك أن CURL مفعل ✅

### الطريقة 2: من خلال سطر الأوامر

1. افتح Command Prompt كمسؤول
2. اذهب إلى مجلد PHP:
   ```
   cd C:\xampp\php
   ```
3. نفّذ الأمر:
   ```
   php -m | findstr curl
   ```
4. إذا ظهر "curl"، فمعنى ذلك أنه مفعل ✅

### الطريقة 3: اختبار بسيط

أنشئ ملف `test_curl.php`:
```php
<?php
if (function_exists('curl_version')) {
    echo "CURL مفعل! ✅<br>";
    $version = curl_version();
    echo "إصدار CURL: " . $version['version'];
} else {
    echo "CURL غير مفعل ❌";
}
?>
```

افتحه في المتصفح: `http://localhost/test_curl.php`

## استكشاف الأخطاء

### المشكلة: CURL لا يزال غير مفعل بعد التفعيل

**الحلول:**
1. تأكد من حذف الفاصلة المنقوطة `;` من السطر
2. تأكد من إعادة تشغيل Apache
3. تحقق من أن ملف `php_curl.dll` موجود في `C:\xampp\php\ext\`
4. تأكد من فتح ملف php.ini كمسؤول

### المشكلة: ملف php_curl.dll غير موجود

**الحل:**
- قد تحتاج إلى إعادة تثبيت XAMPP
- أو نسخ الملف من نسخة أخرى من PHP

### المشكلة: خطأ في تحميل extension

**الحل:**
1. تحقق من أن مسار `extension_dir` في php.ini صحيح:
   ```
   extension_dir = "C:\xampp\php\ext"
   ```
2. تأكد من عدم وجود أخطاء في ملف php.ini

## ملاحظات مهمة

⚠️ **مهم:**
- يجب إعادة تشغيل Apache بعد أي تغيير في php.ini
- تأكد من فتح ملف php.ini كمسؤول
- احتفظ بنسخة احتياطية من php.ini قبل التعديل

## بعد تفعيل CURL

بعد تفعيل CURL بنجاح:
1. أعد تشغيل Apache
2. افتح صفحة التثبيت: `http://localhost/hospital/index.php/install/index`
3. يجب أن يختفي تحذير CURL من قائمة المتطلبات ✅

---

**بعد التفعيل، أعد تشغيل Apache وأخبرني بالنتيجة!** 🚀
