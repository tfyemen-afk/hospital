# 🚨 حل عاجل - CURL غير مفعل

## ✅ التحقق من الإعدادات:

تم التحقق من ملف `php.ini` ووجدت:
- ✅ OpenSSL مفعل في السطر 933: `extension=openssl`
- ✅ CURL مفعل في السطر 934: `extension=php_curl.dll`
- ✅ ملف `php_curl.dll` موجود

## 🔄 المشكلة الوحيدة:

**Apache لم يُعاد تشغيله بعد التعديلات!**

## 🚀 الحل السريع (3 طرق):

### الطريقة 1: استخدام السكريبت (الأسهل)

1. انقر بزر الماوس الأيمن على ملف: `restart_apache.bat`
2. اختر **Run as administrator**
3. انتظر حتى ينتهي السكريبت
4. افتح: `http://localhost:8080/hospital/assets/check_curl.php`

### الطريقة 2: من XAMPP Control Panel

1. افتح **XAMPP Control Panel**
2. انقر على **Stop** بجانب Apache
3. **انتظر 15 ثانية** (مهم جداً!)
4. انقر على **Start** بجانب Apache
5. **انتظر 15 ثانية** (مهم جداً!)
6. افتح: `http://localhost:8080/hospital/assets/check_curl.php`

### الطريقة 3: من سطر الأوامر

1. افتح Command Prompt كمسؤول
2. اكتب:
   ```
   cd C:\xampp
   apache_stop.bat
   ```
3. انتظر 10 ثوان
4. اكتب:
   ```
   apache_start.bat
   ```
5. انتظر 10 ثوان
6. افتح: `http://localhost:8080/hospital/assets/check_curl.php`

## ✅ بعد إعادة التشغيل:

1. افتح: `http://localhost:8080/hospital/assets/check_curl.php`
2. إذا ظهر "CURL مفعل بنجاح" ✅
3. افتح: `http://localhost:8080/hospital/install/index`
4. يجب أن يختفي تحذير CURL الآن!

## ⚠️ ملاحظات مهمة:

- **يجب إعادة تشغيل Apache** بعد أي تغيير في php.ini
- **لا تكفي إعادة تحميل الصفحة** فقط
- **تأكد من أن Apache متوقف تماماً** قبل إعادة تشغيله
- **انتظر 15 ثانية** بعد كل عملية (Stop أو Start)

## 🔍 إذا لم يعمل بعد إعادة التشغيل:

1. تحقق من أن Apache يعمل (باللون الأخضر في XAMPP)
2. افتح: `http://localhost:8080/hospital/assets/check_curl.php`
3. إذا ظهر خطأ، تحقق من ملف `error_log` في XAMPP

---

**ابدأ الآن: استخدم restart_apache.bat أو أعد تشغيل Apache يدوياً!** 🔄
