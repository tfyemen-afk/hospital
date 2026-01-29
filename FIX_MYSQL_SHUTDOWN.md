
# 🔧 حل مشكلة MySQL Shutdown Unexpectedly

## 🔍 المشكلة:
```
MySQL shutdown unexpectedly
This may be due to a blocked port, missing dependencies, 
improper privileges, a crash, or a shutdown by another method.
```

## ✅ الحلول:

### الحل 1: تحقق من ملف mysql_error.log

1. **افتح ملف السجل:**
   ```
   C:\xampp\mysql\data\mysql_error.log
   ```

2. **ابحث عن الأخطاء** في نهاية الملف

### الحل 2: حذف ملفات PID و Lock

1. **أوقف MySQL** من XAMPP Control Panel

2. **احذف هذه الملفات:**
   - `C:\xampp\mysql\data\mysql.pid`
   - `C:\xampp\mysql\data\*.pid` (إن وجدت)

3. **شغّل MySQL مرة أخرى**

### الحل 3: إصلاح قاعدة البيانات

1. **أوقف MySQL**

2. **افتح Command Prompt كمسؤول**

3. **انتقل إلى مجلد MySQL:**
   ```
   cd C:\xampp\mysql\bin
   ```

4. **شغّل إصلاح قاعدة البيانات:**
   ```
   mysqlcheck --all-databases --repair --auto-repair -u root
   ```

### الحل 4: إعادة تهيئة MySQL

**تحذير:** هذا سيحذف جميع قواعد البيانات!

1. **أوقف MySQL**

2. **احذف محتوى مجلد data:**
   - `C:\xampp\mysql\data\*` (احتفظ بالمجلد فقط)

3. **شغّل MySQL** - سيتم إنشاء قواعد البيانات الأساسية تلقائياً

### الحل 5: تحقق من المنفذ 3306

1. **افتح Command Prompt**

2. **تحقق من المنفذ:**
   ```
   netstat -ano | findstr :3306
   ```

3. **إذا كان مستخدماً، أوقف العملية**

## 📋 الحل السريع:

1. ✅ أوقف MySQL من XAMPP Control Panel
2. ✅ احذف: `C:\xampp\mysql\data\mysql.pid`
3. ✅ شغّل MySQL مرة أخرى

---

**ابدأ بالحل السريع!** 🔄
