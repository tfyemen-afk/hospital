# حل مشكلة CURL على المنفذ 8080

## ✅ الصفحة تعمل على:
```
http://localhost:8080/hospital/install/index
```

## 🔍 فحص CURL:

افتح هذا الرابط:
```
http://localhost:8080/hospital/check_curl.php
```

هذا الملف سيفحص CURL ويعطيك تعليمات واضحة.

## 🔧 إذا كان CURL غير مفعل:

### الخطوات:

1. **افتح ملف php.ini:**
   - XAMPP Control Panel → Config → PHP (php.ini)

2. **ابحث عن CURL:**
   - اضغط Ctrl+F
   - ابحث: `extension=curl` أو `extension=php_curl.dll`

3. **تأكد من التفعيل:**
   - يجب أن يكون: `extension=php_curl.dll` (بدون `;`)
   - يجب أن يكون OpenSSL مفعل قبل CURL: `extension=openssl`

4. **احفظ الملف** (Ctrl+S)

5. **أعد تشغيل Apache:**
   - XAMPP Control Panel → Stop Apache
   - انتظر 15 ثانية
   - Start Apache
   - انتظر 15 ثانية

6. **اختبر مرة أخرى:**
   - افتح: `http://localhost:8080/hospital/check_curl.php`
   - إذا ظهر "CURL مفعل"، فكل شيء بخير!

7. **ارجع إلى صفحة التثبيت:**
   - افتح: `http://localhost:8080/hospital/install/index`
   - يجب أن يختفي تحذير CURL الآن!

## 📋 ملاحظات:

- المنفذ 8080 يعني أن Apache يعمل على منفذ غير الافتراضي
- هذا لا يؤثر على CURL
- المهم هو إعادة تشغيل Apache بعد تعديل php.ini

---

**ابدأ بفتح `check_curl.php` للتحقق من CURL!** 🔍
