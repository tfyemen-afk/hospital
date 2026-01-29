# ✅ تم إصلاح مشكلة Timeout

## 🔍 المشكلة:
```
Fatal error: Maximum execution time of 30 seconds exceeded
```

## ✅ ما تم إصلاحه:

### 1. زيادة max_execution_time
- **قبل:** 30 ثانية
- **بعد:** 300 ثانية (5 دقائق)
- **الملف:** `C:\php82\php.ini`

### 2. زيادة max_input_time
- **قبل:** 60 ثانية
- **بعد:** 300 ثانية (5 دقائق)
- **الملف:** `C:\php82\php.ini`

## 🔄 الخطوة التالية:

### أعد تشغيل Apache

**الطريقة الأسهل:**
1. انقر بزر الماوس الأيمن على: `RESTART_APACHE_FIXED.bat`
2. اختر **Run as administrator**
3. انتظر حتى ينتهي

**أو من XAMPP Control Panel:**
1. انقر على **Stop** بجانب Apache
2. انتظر 5 ثوان
3. انقر على **Start** بجانب Apache

## ✅ بعد إعادة التشغيل:

1. **افتح phpMyAdmin:**
   ```
   http://localhost:8080/phpmyadmin
   ```
   - يجب أن يعمل الآن بدون مشاكل timeout ✅

2. **أنشئ قاعدة البيانات:**
   - انقر على **New**
   - أدخل الاسم: `hospital`
   - اختر **Collation:** `utf8_general_ci`
   - انقر على **Create**

3. **أكمل التثبيت:**
   ```
   http://localhost:8080/hospital/install/index
   ```

---

**أعد تشغيل Apache الآن!** 🔄
