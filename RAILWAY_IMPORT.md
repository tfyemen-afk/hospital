# استيراد قاعدة البيانات إلى Railway

هذا الدليل يشرح كيفية **تصدير** قاعدة البيانات من XAMPP (محلياً) ثم **استيرادها** إلى MySQL على منصة Railway.

---

## تنبيه: المشروع المؤقت

الرسالة الظاهرة "قم بالمطالبة بها لتصبح ملكك خلال 24 ساعة" تعني أن المشروع على Railway **مؤقت**. يُفضّل **المطالبة بالمشروع** (مشروع المطالبات) أولاً حتى لا يُحذف بعد 24 ساعة قبل أن تنتهي من استيراد البيانات.

---

## الخطوة 1: تصدير قاعدة البيانات من XAMPP

### الطريقة أ: من سطر الأوامر (موصى بها)

1. افتح **Command Prompt** أو **PowerShell**.
2. انتقل لمجلد MySQL في XAMPP (مثال):
   ```text
   cd C:\xampp\mysql\bin
   ```
3. نفّذ أمر التصدير (اضبط كلمة مرور root إن كانت لديك):
   ```text
   mysqldump -u root -p hospital > C:\xampp\htdocs\hospital\hospital_export.sql
   ```
4. أدخل كلمة مرور MySQL عند الطلب (اتركها فارغة ثم Enter إذا لم تكن قد ضبطت كلمة مرور).
5. سيتم إنشاء الملف `hospital_export.sql` في مجلد المشروع.

### الطريقة ب: من phpMyAdmin

1. افتح **http://localhost/phpmyadmin**
2. اختر قاعدة البيانات **hospital** من القائمة اليسرى.
3. انقر تبويب **Export** (تصدير).
4. اختر **Quick** ثم **Go** لحفظ الملف، أو **Custom** لاختيار الجداول وتنسيق التصدير.
5. احفظ الملف (مثلاً `hospital_export.sql`) في جهازك.

---

## الخطوة 2: الحصول على بيانات الاتصال من Railway

1. ادخل إلى مشروعك على **Railway**.
2. اختر خدمة **MySQL** (التي تظهر "متصل").
3. انقر **يتصل** (Connect) أو اذهب إلى تبويب **المتغيرات** (Variables).
4. سجّل القيم التالية (أو انسخ **Connection URL** إن وُجد):
   - **MYSQLHOST** أو **Host**
   - **MYSQLUSER** أو **User**
   - **MYSQLPASSWORD** أو **Password**
   - **MYSQLPORT** أو **Port** (غالباً 3306)
   - **MYSQLDATABASE** أو **Database** (اسم القاعدة)

إذا كان لديك **Connection URL** فقط (مثل `mysql://user:pass@host:port/dbname`)، استخدمه في الأمر في الخطوة التالية.

---

## الخطوة 3: استيراد الملف إلى MySQL على Railway

### إذا كان لديك MySQL client على جهازك (XAMPP)

من نفس مجلد `mysql\bin` في XAMPP:

```text
mysql -h HOST -u USER -p -P PORT DATABASE < C:\xampp\htdocs\hospital\hospital_export.sql
```

استبدل:
- **HOST** = عنوان MySQL من Railway (مثل `containers-us-west-xxx.railway.app`)
- **USER** = اسم المستخدم من Railway
- **PORT** = المنفذ (غالباً 3306 أو الرقم الذي يعطيك إياه Railway)
- **DATABASE** = اسم القاعدة (مثل `railway` أو الاسم الذي أنشأه Railway)

مثال (عدّل القيم حسب لوحة Railway):

```text
mysql -h containers-us-west-123.railway.app -u root -p -P 12345 railway < C:\xampp\htdocs\hospital\hospital_export.sql
```

### إذا كان Railway يعطيك Connection URL

بعض الخدمات تعرض رابطاً مثل:

```text
mysql://root:xxxxx@containers-us-west-xxx.railway.app:12345/railway
```

يمكنك استخدامه هكذا (على Linux/Mac أو Git Bash على Windows):

```bash
mysql "mysql://root:xxxxx@containers-us-west-xxx.railway.app:12345/railway" < hospital_export.sql
```

على Windows CMD/PowerShell قد تحتاج لتفكيك الرابط واستخدام `-h`, `-u`, `-p`, `-P`, واسم القاعدة كما في المثال السابق.

---

## الخطوة 4: التأكد من الاستيراد

1. في Railway، ادخل إلى MySQL → تبويب **قاعدة البيانات** (Database) → **بيانات** (Data).
2. تحقق من ظهور الجداول (مثل `generalsettings`, `inihospital`, إلخ).
3. في تطبيقك على Render (أو حيث يعمل المشروع)، تأكد من ضبط متغيرات البيئة:
   - `DB_HOST` = نفس Host من Railway
   - `DB_USERNAME` = نفس User
   - `DB_PASSWORD` = نفس Password
   - `DB_DATABASE` = نفس اسم القاعدة

بعد ذلك أعد تحميل الموقع؛ يجب أن يعمل مع البيانات المستوردة.

---

## مشاكل شائعة

| المشكلة | الحل |
|---------|------|
| **الاتصال مرفوض (Connection refused)** | تأكد أن عنوان Host والمنفذ (Port) صحيحان من Railway، وأن IP جهازك مسموح به إن كانت الخدمة تقيد الوصول. |
| **Access denied** | تحقق من اسم المستخدم وكلمة المرور في Railway (المتغيرات). |
| **Unknown database** | تأكد أن اسم القاعدة في الأمر يطابق اسم القاعدة على Railway (مثل `railway` أو `MYSQLDATABASE`). |
| **ملف كبير جداً** | إن كان الملف كبيراً، قد تحتاج رفع المهلة في MySQL أو تقسيم الملف وتشغيل الاستيراد على أجزاء. |

---

## بعد الاستيراد

- احذف ملف `hospital_export.sql` من المشروع إذا أضفته داخل المجلد قبل رفعه إلى Git (لا ترفع ملفات SQL الكبيرة إلى المستودع إن كانت تحتوي بيانات حساسة).
- استخدم ملف `.gitignore` وتأكد أن `*.sql` أو اسم الملف مضاف إن أردت منع رفع النسخ الاحتياطية بالخطأ.
