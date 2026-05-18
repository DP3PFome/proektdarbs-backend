# 🚀 Развертывание на Railway + Vercel

## 📋 Чек-лист перед развертыванием:

- [ ] Все локальные тесты прошли успешно
- [ ] .env файл содержит правильные production переменные
- [ ] APP_DEBUG=false в production
- [ ] Используется PostgreSQL для Render (не SQLite)
- [ ] SESSION_DOMAIN установлен как .vercel.app
- [ ] FRONTEND_URL указан правильно
- [ ] Код закоммичен и готов к push

## 🛤️ Railway Backend развертывание:

### Шаг 1: Подготовка репозитория
```bash
cd backend

# Убедиться, что все файлы коммичены
git status

# Добавить .env в .gitignore (если ещё не добавлен)
echo ".env" >> .gitignore
echo ".env.local" >> .gitignore

# Закоммитить всё
git add .
git commit -m "CORS и API fixes для Vercel frontend"
```

### Шаг 2: Развертывание на Railway
```bash
# Установить Railway CLI (если не установлен)
# https://railway.app/

# Вход в Railway
railway login

# Связать проект с Railway
railway link

# Установить production переменные
railway variables add APP_NAME="RVT-App"
railway variables add APP_ENV="production"
railway variables add APP_DEBUG="false"
railway variables add APP_URL="https://ekzamenrabota-production.up.railway.app"
railway variables add DB_CONNECTION="pgsql"
railway variables add DB_HOST="junction.proxy.rlwy.net"
railway variables add DB_PORT="11929"
railway variables add DB_DATABASE="railway"
railway variables add DB_USERNAME="postgres"
railway variables add DB_PASSWORD="gzGfhzHc9BH6HG2FT1Gbjz9cGhzjB3BG"
railway variables add FRONTEND_URL="https://ekzamen-rabota.vercel.app"
railway variables add SESSION_DRIVER="cookie"
railway variables add SESSION_DOMAIN=".vercel.app"

# Развернуть приложение
railway up

# Или если используется GitHub integration:
git push
```

### Шаг 3: Запуск миграций на Railway
```bash
# После успешного deployment, запустить миграции
railway run php artisan migrate --force
```

### Шаг 4: Проверить backend
```bash
# Убедиться, что backend работает
curl https://ekzamenrabota-production.up.railway.app/api/health
```

## 🎨 Vercel Frontend развертывание:

### Шаг 1: Переменные окружения на Vercel
Перейти в Vercel Project Settings -> Environment Variables

Добавить:
```
VITE_API_URL=https://ekzamenrabota-production.up.railway.app/api
```

### Шаг 2: Убедиться в конфиге Vite
В `frontend/vite.config.js`:
```javascript
export default defineConfig({
  plugins: [vue()],
  server: {
    port: 3000,
    proxy: {
      '/api': {
        target: 'https://ekzamenrabota-production.up.railway.app',
        changeOrigin: true
      }
    }
  }
})
```

### Шаг 3: Развертывание на Vercel
```bash
cd frontend

# Убедиться, что build работает локально
npm run build

# Коммитить и pushnуть
git add .
git commit -m "Frontend updates"
git push

# Vercel автоматически deployment
```

## 🔧 Отладка на production:

### 1. Проверить логи на Railway:
```bash
railway logs
```

### 2. Проверить CORS headers:
```bash
curl -i -X OPTIONS https://ekzamenrabota-production.up.railway.app/api/collections \
  -H "Origin: https://ekzamen-rabota.vercel.app" \
  -H "Access-Control-Request-Method: POST"
```

Должны видеть:
```
access-control-allow-origin: https://ekzamen-rabota.vercel.app
access-control-allow-methods: GET, POST, PUT, DELETE, OPTIONS, PATCH
access-control-allow-credentials: true
```

### 3. Проверить API с фронтенда:
Открыть DevTools -> Network tab и убедиться, что:
- ✓ Preflight (OPTIONS) запросы возвращают 200
- ✓ Основные запросы (POST, GET и т.д.) работают
- ✓ CORS ошибок нет в консоли

### 4. Проверить БД на Render:
```bash
# Убедиться, что миграции запущены
railway run php artisan migrate:status

# Если миграции не применены
railway run php artisan migrate --force
```

## 📱 Мониторинг:

### Railway:
- https://railway.app/dashboard

### Vercel:
- https://vercel.com/dashboard

### Logs и метрики:
```bash
# Railway логи
railway logs -f

# Vercel логи (через CLI)
vercel logs
```

## 🔐 Безопасность:

1. ✓ APP_DEBUG=false в production
2. ✓ APP_KEY установлен
3. ✓ CORS правильно ограничен
4. ✓ SESSION_DOMAIN установлен для security cookies
5. ✓ Credentials требуют authorization
6. ✓ HTTPS используется везде

## ✅ Успешное развертывание!

Если всё работает без ошибок:
1. Frontend на Vercel может общаться с Backend на Railway
2. CORS полностью конфигурирован
3. Аутентификация работает
4. Данные безопасно передаются

Готово к production! 🚀
