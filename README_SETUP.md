# 🏥 دليل الإعداد السريع - منصة المستشفى

## ✅ الحالة الحالية

تم إصلاح جميع المشاكل وإعداد النظام للتثبيت:

### الإصلاحات المطبقة:
- ✅ إصلاح مشاكل PHP 8.4 (E_STRICT, Dynamic Properties)
- ✅ التحقق من تفعيل CURL في php.ini
- ✅ إنشاء ملفات فحص واختبار شاملة

## 🚀 البدء السريع

### 1. إعادة تشغيل Apache (مهم جداً!)

**الطريقة 1: من XAMPP Control Panel**
1. افتح XAMPP Control Panel
2. انقر **Stop** بجانب Apache
3. انتظر قليلاً
4. انقر **Start** بجانب Apache

**الطريقة 2: استخدام السكريبت**
- انقر بزر الماوس الأيمن على `restart_apache.ps1`
- اختر **Run with PowerShell**

### 2. فحص النظام

افتح في المتصفح:
```
http://localhost/hospital/check_system.php
```

هذا الملف سيفحص كل شيء ويعطيك تقريراً كاملاً.

### 3. بدء التثبيت

بعد التأكد من أن كل شيء بخير:
```
http://localhost/hospital/index.php/install/index
```

## 📁 الملفات المتاحة

| الملف | الوصف |
|------|-------|
| `check_system.php` | فحص شامل للنظام (افتحه أولاً!) |
| `test_curl.php` | اختبار CURL فقط |
| `restart_apache.ps1` | سكريبت لإعادة تشغيل Apache |
| `INSTALL_AR.md` | دليل التثبيت الكامل |
| `ENABLE_CURL_AR.md` | دليل تفعيل CURL |
| `AUTO_SETUP_AR.md` | ملخص الإعداد التلقائي |
| `FIXES_AR.md` | تفاصيل الإصلاحات |

## 🔍 خطوات التثبيت

1. **إنشاء قاعدة البيانات**
   - افتح: `http://localhost/phpmyadmin`
   - أنشئ قاعدة بيانات: `hospital`

2. **فحص النظام**
   - افتح: `http://localhost/hospital/check_system.php`
   - تأكد من أن كل شيء ✅

3. **بدء التثبيت**
   - افتح: `http://localhost/hospital/index.php/install/index`
   - اتبع الخطوات

4. **إدخال معلومات قاعدة البيانات**
   - Hostname: `localhost`
   - Database: `hospital`
   - Username: `root`
   - Password: (فارغ)

## ⚠️ ملاحظات مهمة

- **يجب إعادة تشغيل Apache** بعد أي تغيير في php.ini
- CURL مفعل بالفعل في php.ini (السطر 921)
- إذا ظهرت مشاكل، راجع ملفات المساعدة أعلاه

## 🆘 المساعدة

إذا واجهت أي مشاكل:
1. افتح `check_system.php` لمعرفة المشكلة
2. راجع الملفات التوثيقية
3. تأكد من إعادة تشغيل Apache

---

**ابدأ الآن بفتح `check_system.php`!** 🎯
