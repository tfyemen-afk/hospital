# نشر مشروع المستشفى (PHP) على Render باستخدام Docker
FROM php:8.1-cli

WORKDIR /app

# تثبيت امتداد mysqli للاتصال بـ MySQL
RUN docker-php-ext-install mysqli

# نسخ ملفات المشروع
COPY . .

# المنفذ يُحدد من متغير PORT على Render
EXPOSE 8080

# تشغيل خادم PHP المدمج (PORT يُحقن من Render)
CMD ["sh", "-c", "php -S 0.0.0.0:${PORT:-8080} -t ."]
