# ⚠️ حل مشكلة CURL الآن!

## المشكلة
CURL مفعل في `php.ini` لكن Apache لم يُعاد تشغيله بعد، لذلك لا يزال غير مفعل.

## الحل السريع (3 خطوات فقط!)

### الخطوة 1: تأكد من CURL مفعل في php.ini ✅
- CURL مفعل بالفعل في السطر 921: `extension=curl`
- لا حاجة لتغيير أي شيء هنا

### الخطوة 2: أعد تشغيل Apache (مهم جداً!) 🔄

**الطريقة الأسهل:**
1. افتح **XAMPP Control Panel**
2. انقر على **Stop** بجانب Apache (انتظر حتى يتوقف تماماً)
3. انقر على **Start** بجانب Apache (انتظر حتى يبدأ تماماً)
4. تأكد من أن Apache يعمل (باللون الأخضر)

**أو استخدم السكريبت:**
- انقر بزر الماوس الأيمن على `restart_apache.ps1`
- اختر **Run with PowerShell**

### الخطوة 3: اختبر CURL ✅

افتح في المتصفح:
```
http://localhost/hospital/fix_curl.php
```

أو:
```
http://localhost/hospital/test_curl.php
```

إذا ظهر "CURL مفعل بنجاح"، فكل شيء بخير! ✅

---

## إذا لم يعمل بعد إعادة التشغيل

### الحل البديل 1: تحقق من ملف php.ini المستخدم

1. افتح: `http://localhost/hospital/fix_curl.php`
2. انظر إلى "مسار php.ini" في الصفحة
3. افتح هذا الملف بالضبط
4. ابحث عن `extension=curl`
5. تأكد من أنه بدون `;` في البداية

### الحل البديل 2: تفعيل CURL يدوياً

1. افتح XAMPP Control Panel
2. انقر على **Config** بجانب Apache
3. اختر **PHP (php.ini)**
4. اضغط `Ctrl + F` للبحث
5. ابحث عن: `extension=curl`
6. إذا كان: `;extension=curl`، احذف `;`
7. احفظ الملف (`Ctrl + S`)
8. **أعد تشغيل Apache** (Stop → Start)

### الحل البديل 3: تحقق من وجود الملفات

تأكد من وجود:
- `C:\xampp\php\ext\php_curl.dll` ✅ (موجود)
- `C:\xampp\php\php.ini` ✅ (موجود)

---

## ملاحظات مهمة

⚠️ **يجب إعادة تشغيل Apache بعد أي تغيير في php.ini**

⚠️ **لا تكفي إعادة تحميل الصفحة فقط - يجب إعادة تشغيل Apache**

⚠️ **تأكد من أن Apache متوقف تماماً قبل إعادة تشغيله**

---

## بعد إعادة التشغيل

1. افتح: `http://localhost/hospital/fix_curl.php`
2. إذا ظهر "CURL مفعل بنجاح" ✅
3. افتح: `http://localhost/hospital/index.php/install/index`
4. يجب أن يختفي تحذير CURL الآن!

---

**الخطوة الأهم: أعد تشغيل Apache الآن!** 🔄
