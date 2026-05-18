# 🧪 Локальное тестирование API с фронтенда

## Подготовка:

### 1. Backend (Laravel):
```bash
cd backend

# Установить зависимости
composer install

# Скопировать .env.example в .env (если ещё не сделано)
cp .env.example .env

# Сгенерировать APP_KEY
php artisan key:generate

# Создать SQLite БД
touch database/database.sqlite

# Запустить миграции
php artisan migrate

# Запустить сервер
php artisan serve
# Или через Vite + artisan:
composer run dev
```

Backend будет доступен на: `http://localhost:8000/api`

### 2. Frontend (Vue.js):
```bash
cd frontend

# Установить зависимости
npm install

# Запустить Vite dev server
npm run dev
```

Frontend будет доступен на: `http://localhost:5173`

## 📋 Тестирование API endpoints:

### 1. Health Check:
```bash
curl -X GET http://localhost:8000/api/health
```

Ожидаемый ответ:
```json
{"status":"ok"}
```

### 2. Получить статистику (public):
```bash
curl -X GET http://localhost:8000/api/stats
```

### 3. Регистрация:
```bash
curl -X POST http://localhost:8000/api/register \
  -H "Content-Type: application/json" \
  -d '{
    "name": "John Doe",
    "email": "john@example.com",
    "password": "password123"
  }'
```

Ожидаемый ответ содержит `token` для дальнейших запросов.

### 4. Вход:
```bash
curl -X POST http://localhost:8000/api/login \
  -H "Content-Type: application/json" \
  -d '{
    "email": "john@example.com",
    "password": "password123"
  }'
```

### 5. Получить текущего пользователя (protected):
```bash
curl -X GET http://localhost:8000/api/user \
  -H "Authorization: Bearer YOUR_TOKEN_HERE"
```

### 6. CORS Preflight Test:
```bash
curl -X OPTIONS http://localhost:8000/api/collections \
  -H "Origin: http://localhost:5173" \
  -H "Access-Control-Request-Method: POST" \
  -H "Access-Control-Request-Headers: Content-Type" \
  -v
```

Должны видеть ответ с CORS заголовками:
```
Access-Control-Allow-Origin: http://localhost:5173
Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS, PATCH
Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With, Accept
Access-Control-Allow-Credentials: true
```

## 🔍 Отладка при проблемах:

### 1. CORS ошибка в браузере:
- Проверить консоль браузера (DevTools -> Network)
- Убедиться, что backend возвращает правильные `Access-Control-Allow-*` заголовки
- Проверить, что frontend URL добавлен в `config/cors.php`

### 2. 401 Unauthorized:
- Убедиться, что токен правильно передан в Authorization заголовке
- Проверить, что токен не истёк
- Убедиться, что используется формат: `Authorization: Bearer token_here`

### 3. 422 Unprocessable Entity:
- Это означает, что валидация данных не прошла
- Проверить правильность отправляемых данных
- Посмотреть ошибки в ответе JSON

### 4. 500 Internal Server Error:
- Проверить логи: `storage/logs/laravel.log`
- Убедиться, что .env переменные установлены правильно
- Проверить, что база данных мигрирована

## 📝 Переменные окружения для локального тестирования:

```env
APP_NAME=RVT-App
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost:8000

DB_CONNECTION=sqlite
DB_DATABASE=database/database.sqlite

FRONTEND_URL=http://localhost:5173

LOG_LEVEL=debug
```

## ✅ Все готово!

Если всё выше описанное работает без ошибок, то backend готов для развертывания на Railway и работи с Vercel фронтенда.
