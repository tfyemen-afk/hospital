# ربط قاعدة البيانات مع Render

المشروع معدّ الآن لقراءة إعدادات قاعدة البيانات من **متغيرات البيئة** على Render.

## أمر البدء (Start Command) — مهم

يجب أن يكون **Start Command** على Render كما يلي، وليس رقماً أو نصاً خاطئاً (مثل `2026`):

```bash
php -S 0.0.0.0:$PORT -t .
```

- في لوحة Render: **Settings** → **Build & Deploy** → **Start Command** → الصق الأمر أعلاه.
- إذا كان الحقل فارغاً أو خاطئاً، ستظهر رسالة مثل `command not found` أو `Exited with status 127`.

### إذا ظهر خطأ `php: command not found`

معناه أن بيئة التشغيل على Render **ليست PHP** (مثلاً الخدمة مُعدّة كـ Node). يمكنك:

**الطريقة 1 — استخدام Docker (موصى بها):**

1. في Render: **Settings** → **Build & Deploy**.
2. في **Environment** اختر **Docker** (بدلاً من Native).
3. احفظ ثم أعد النشر. المشروع يحتوي على `Dockerfile` يجعل PHP يعمل.

**الطريقة 2 — تغيير الـ Runtime إلى PHP:**

1. في Render: **Settings** → إذا وُجد خيار **Runtime** أو **Environment** فاختر **PHP**.
2. احفظ ثم أعد النشر.

بعد ذلك يجب أن يتوفر أمر `php` ويُنجح النشر.

### إذا ظهر خطأ 503 Service Unavailable

- تأكد أن متغير البيئة **`ENVIRONMENT`** مضبوط على **`production`** (بحروف صغيرة، بدون مسافات زائدة). إن كان فارغاً أو مكتوباً بشكل خاطئ (مثل `Production` بحرف كبير)، قد يظهر 503.
- تم تعديل المشروع ليقبل أي صيغة لـ `ENVIRONMENT` (مثل Production أو production) وتصحيحها تلقائياً.
- على الخطة المجانية: الرسالة "Your free instance will spin down with inactivity" تعني أن أول طلب بعد فترة خمول قد يستغرق نحو 50 ثانية؛ انتظر ثم حدّث الصفحة.

## ملاحظة مهمة

- **Render** يوفر قاعدة بيانات **PostgreSQL** افتراضياً.
- مشروع المستشفى يعمل على **MySQL/MariaDB** (mysqli).
- لذلك تحتاج إلى قاعدة بيانات **MySQL خارجية** ثم ربطها من Render.

## خيارات قاعدة بيانات MySQL للسحابة

1. **[PlanetScale](https://planetscale.com)** – MySQL مجاني للتجربة
2. **[Railway](https://railway.app)** – يوفر MySQL
3. **[Aiven](https://aiven.io)** – MySQL مدفوع له trial
4. **[FreeSQLDatabase](https://www.freesqldatabase.com)** – MySQL مجاني محدود

أنشئ قاعدة بيانات MySQL في أحد هذه الخدمات، ثم احصل على:
- **Host** (مثال: `xxx.mysql.region.planetscale.io`)
- **Username**
- **Password**
- **Database name**

## إعداد متغيرات البيئة على Render

1. ادخل إلى [Dashboard Render](https://dashboard.render.com)
2. اختر خدمة المشروع (Web Service)
3. من القائمة الجانبية: **Environment**
4. أضف المتغيرات التالية:

| المفتاح        | القيمة        | مثال |
|----------------|---------------|------|
| `DB_HOST`      | عنوان السيرفر | `xxx.mysql.region.planetscale.io` |
| `DB_USERNAME`  | اسم المستخدم  | `user` |
| `DB_PASSWORD`  | كلمة المرور   | `your_password` |
| `DB_DATABASE`  | اسم القاعدة   | `hospital` |
| `ENVIRONMENT`  | بيئة التشغيل  | `production` |

5. احفظ التغييرات (Save). سيتم إعادة نشر الخدمة تلقائياً في أغلب الأحيان.

## إذا كانت الخدمة تعطيك رابط اتصال واحد (Connection URL)

إذا كان شكل الرابط مثل:

```text
mysql://user:password@host:3306/database_name
```

يمكنك إما:

**الطريقة 1:** إضافة متغير بيئة واحد على Render باسم `DATABASE_URL` وقيمته هذا الرابط بالكامل. المشروع يقرأه تلقائياً.

**الطريقة 2:** تفكيك الرابط يدوياً وإدخال القيم في المتغيرات الأربعة:

- **Host:** الجزء بعد `@` وقبل `:3306`
- **Username:** الجزء بعد `mysql://`
- **Password:** الجزء بعد اسم المستخدم وقبل `@`
- **Database:** الجزء بعد `/` في آخر الرابط

ثم أنشئ على Render: `DB_HOST`, `DB_USERNAME`, `DB_PASSWORD`, `DB_DATABASE` كما في الجدول.

## SSL مع PlanetScale أو غيره

بعض الخدمات تفرض الاتصال عبر SSL. إذا ظهرت أخطاء اتصال متعلقة بـ SSL، قد تحتاج لتفعيل خيارات SSL في `mvc/config/database.php` (مثل `encrypt` و `ssl_options`) حسب توثيق الخدمة.

## التأكد من الاتصال

بعد حفظ المتغيرات وإعادة النشر:

1. افتح رابط الموقع على Render.
2. إذا كان التثبيت الأولي للمشروع يعرض صفحة إعداد قاعدة البيانات، أكمل الإعداد من هناك.
3. إذا ظهر خطأ اتصال، راجع سجلات الخدمة (Logs) على Render وتأكد من صحة `DB_HOST`, `DB_USERNAME`, `DB_PASSWORD`, `DB_DATABASE`.

## استخدام FreeSQLDatabase

إذا استخدمت [FreeSQLDatabase](https://www.freesqldatabase.com) لاستضافة قاعدة البيانات، اضبط على Render (أو منصة النشر) المتغيرات التالية فقط — **لا تضف كلمة المرور في الكود أو في المستودع**:

| المفتاح        | القيمة (مثال لـ FreeSQLDatabase) |
|----------------|-----------------------------------|
| `DB_HOST`      | `sql12.freesqldatabase.com`       |
| `DB_USERNAME`  | اسم المستخدم من لوحة FreeSQLDatabase |
| `DB_PASSWORD`  | كلمة المرور (اضبطها في **Environment** فقط، كسرّ) |
| `DB_DATABASE`  | اسم القاعدة (غالباً نفس اسم المستخدم) |
| `DB_PORT`      | `3306`                            |
| `ENVIRONMENT`  | `production`                      |

- احصل على القيم الفعلية من لوحة تحكم FreeSQLDatabase (Host, Database name, User, Password, Port).
- كلمة المرور: ضعها **فقط** في متغيرات البيئة على منصة النشر (مثل Render)، ولا تضعها أبداً في الملفات أو Git.

---

## التطوير المحلي

بدون تعريف متغيرات البيئة (مثل التشغيل على XAMPP)، يستخدم المشروع تلقائياً:

- `localhost`
- مستخدم: `root`
- كلمة مرور: فارغة
- قاعدة البيانات: `hospital`

لا حاجة لتعديل أي شيء محلياً إذا كنت تستخدم هذه القيم.
