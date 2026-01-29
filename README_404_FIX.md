# حل مشكلة 404

## إذا ظهرت رسالة "404 الصفحة غير موجودة"

### الحل السريع:

1. **تأكد من أن Apache يعمل**
   - افتح XAMPP Control Panel
   - تأكد من أن Apache يعمل (باللون الأخضر)

2. **جرب هذه الروابط:**

```
http://localhost/hospital/test.php
http://localhost/hospital/curl.php
```

3. **إذا لم تعمل، جرب:**

```
http://localhost/hospital/index.php
```

4. **إذا ظهرت صفحة CodeIgniter، جرب:**

```
http://localhost/hospital/index.php/install/index
```

## الملفات المتاحة للاختبار:

- `test.php` - اختبار PHP بسيط
- `curl.php` - فحص CURL
- `index.php` - الصفحة الرئيسية

## إذا استمرت المشكلة:

1. تحقق من أن الملفات موجودة في: `C:\xampp\htdocs\hospital\`
2. تحقق من أن Apache يعمل
3. تحقق من أن المنفذ 80 غير مستخدم من برنامج آخر
