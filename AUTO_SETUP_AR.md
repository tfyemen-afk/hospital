# إعداد تلقائي - منصة المستشفى

## ✅ ما تم إنجازه

### 1. إصلاحات PHP 8.4
- ✅ تم إصلاح مشكلة `E_STRICT` Deprecated
- ✅ تم إخفاء تحذيرات Dynamic Properties
- ✅ تم حل مشكلة Headers Already Sent

### 2. فحص CURL
- ✅ CURL مفعل في `php.ini` (السطر 921)
- ✅ ملف `php_curl.dll` موجود في `C:\xampp\php\ext\`
- ✅ `extension_dir` مضبوط بشكل صحيح

### 3. ملفات المساعدة
- ✅ `check_system.php` - فحص شامل للنظام
- ✅ `test_curl.php` - اختبار CURL
- ✅ `ENABLE_CURL_AR.md` - دليل تفعيل CURL
- ✅ `INSTALL_AR.md` - دليل التثبيت الكامل

## 🚀 الخطوات التالية

### الخطوة 1: إعادة تشغيل Apache

**مهم جداً:** يجب إعادة تشغيل Apache بعد أي تغيير في `php.ini`

1. افتح **XAMPP Control Panel**
2. انقر على **Stop** بجانب Apache
3. انتظر قليلاً
4. انقر على **Start** بجانب Apache
5. تأكد من أن Apache يعمل (باللون الأخضر)

### الخطوة 2: فحص النظام

افتح في المتصفح:
```
http://localhost/hospital/check_system.php
```

هذا الملف سيفحص:
- ✅ إصدار PHP
- ✅ جميع الامتدادات المطلوبة (CURL, MySQLi, MBString, etc.)
- ✅ صلاحيات المجلدات
- ✅ اتصال قاعدة البيانات

### الخطوة 3: اختبار CURL

افتح في المتصفح:
```
http://localhost/hospital/test_curl.php
```

إذا ظهر "CURL مفعل بنجاح"، فكل شيء على ما يرام ✅

### الخطوة 4: بدء التثبيت

بعد التأكد من أن كل شيء يعمل:

1. افتح: `http://localhost/hospital/index.php/install/index`
2. ستظهر صفحة **Check List**
3. تأكد من أن جميع العناصر بخير (✅)
4. انقر على **Next Step**

### الخطوة 5: إدخال معلومات قاعدة البيانات

في صفحة Database:
- **Hostname**: `localhost`
- **Database**: `hospital` (أو الاسم الذي أنشأته)
- **Username**: `root`
- **Password**: (اتركه فارغاً)

### الخطوة 6: إكمال التثبيت

اتبع الخطوات:
1. Purchase Code (يمكن تخطيه)
2. Database ✅
3. Time Zone
4. Site Config
5. System Admin
6. Done

## 🔧 إذا استمرت مشكلة CURL

### الحل السريع:

1. **افتح ملف php.ini**
   - XAMPP Control Panel → Config → PHP (php.ini)

2. **ابحث عن extension=curl**
   - اضغط `Ctrl + F`
   - ابحث: `extension=curl`

3. **تأكد من أنه مفعل**
   - يجب أن يكون: `extension=curl` (بدون `;` في البداية)
   - إذا كان: `;extension=curl`، احذف `;`

4. **احفظ الملف**

5. **أعد تشغيل Apache** (مهم جداً!)

6. **اختبر مرة أخرى**

## 📋 قائمة التحقق

قبل البدء بالتثبيت، تأكد من:

- [ ] Apache يعمل في XAMPP
- [ ] MySQL يعمل في XAMPP
- [ ] قاعدة البيانات `hospital` موجودة في phpMyAdmin
- [ ] CURL مفعل (اختبر بـ test_curl.php)
- [ ] جميع الامتدادات المطلوبة مفعلة (اختبر بـ check_system.php)

## 🆘 استكشاف الأخطاء

### المشكلة: CURL لا يزال غير مفعل

**الحل:**
1. تأكد من إعادة تشغيل Apache
2. امسح cache المتصفح (Ctrl+F5)
3. تحقق من ملف `php.ini` مرة أخرى
4. تأكد من أن ملف `php_curl.dll` موجود

### المشكلة: خطأ في قاعدة البيانات

**الحل:**
1. تأكد من أن MySQL يعمل
2. تأكد من وجود قاعدة البيانات في phpMyAdmin
3. تحقق من معلومات الاتصال

### المشكلة: صفحة بيضاء

**الحل:**
1. تحقق من ملف `error_log` في XAMPP
2. تأكد من أن Apache يعمل
3. تحقق من إعدادات PHP

---

**بعد إعادة تشغيل Apache، افتح check_system.php وأخبرني بالنتيجة!** 🚀
