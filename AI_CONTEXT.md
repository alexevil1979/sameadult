# AIVidCatalog18 — AI Context

> Последнее обновление: 2026-02-16
> Проект: подписочная платформа каталога AI-генерированных 18+ видео (синтетический контент).
> Strictly fictional AI-generated content — no illegal/prohibited material.

---

## Стек технологий

| Компонент | Технология |
|---|---|
| Backend | Laravel 12 (PHP 8.2), MySQL 8, Apache |
| Auth | Sanctum (API tokens + session web) |
| Payments | NOWPayments (крипто-шлюз, HMAC-SHA512 webhook) |
| Frontend (web) | Blade + Tailwind CSS (CDN) |
| Frontend (mobile) | Flutter (Dart), Provider, Dio, Chewie |
| i18n | 3 языка: en, ru, es (JSON lang files) |
| Storage | Private disk (videos), public disk (thumbnails), signed URLs |

---

## Текущее состояние (что готово)

### Backend — Laravel

| Компонент | Кол-во | Статус |
|---|---|---|
| Модели (User, Plan, Video, Subscription, Payment) | 5 | ✅ Готово |
| Контроллеры (Landing, AgeGate, Auth, Video, Admin, API, Webhook, Locale, Plan) | 12 | ✅ Готово |
| Middleware (AgeVerification, Admin, EnsureSubscribed, SetLocale) | 4 | ✅ Готово |
| Миграции (users, plans, videos, subscriptions, payments, tokens, cache, jobs) | 8 | ✅ Готово |
| Seeders (Plans: Basic $9.99 / Premium $19.99, Admin user) | 3 | ✅ Готово |
| Policies (VideoPolicy) + Gates (admin, subscribed) | 1+2 | ✅ Готово |
| Services (NowPaymentsService: createInvoice, handleWebhook, HMAC verify) | 1 | ✅ Готово |
| Events/Listeners (SubscriptionActivated → SendSubscriptionConfirmation) | 1+1 | ✅ Готово |
| Providers (AuthServiceProvider, EventServiceProvider) | 2 | ✅ Готово |
| Routes (web: 29, api: 8) | 37 | ✅ Готово |
| Config (nowpayments.php, filesystems.php с disками videos/thumbnails) | 2 | ✅ Готово |
| .htaccess (HTTPS, block .env, security headers) | 1 | ✅ Готово |
| .env.example (MySQL, NOWPayments keys, sandbox) | 1 | ✅ Готово |

### Frontend — Blade Views

| View | Статус |
|---|---|
| layouts/app.blade.php (Tailwind, navbar, lang switcher, flash messages) | ✅ |
| landing.blade.php (Hero, stats, features x6, showcase, how-it-works, pricing, FAQ x4, CTA) | ✅ |
| age-gate.blade.php (checkbox + date picker, cookie 1 year) | ✅ |
| auth/login.blade.php, auth/register.blade.php | ✅ |
| videos/index.blade.php (grid, фильтры: category/premium/search, pagination) | ✅ |
| videos/show.blade.php (player для доступных, lock-overlay для premium) | ✅ |
| plans/index.blade.php (pricing cards, crypto note) | ✅ |
| payments/success.blade.php, payments/cancel.blade.php | ✅ |
| admin/dashboard.blade.php (stats, quick links) | ✅ |
| admin/videos/index.blade.php (table, approve/edit/delete) | ✅ |
| admin/videos/create.blade.php (multilang upload form) | ✅ |
| admin/videos/edit.blade.php (multilang edit form) | ✅ |
| **Итого** | **15 views** |

### Мультиязычность

| Файл | Ключей | Статус |
|---|---|---|
| lang/en.json | ~130 | ✅ |
| lang/ru.json | ~130 | ✅ |
| lang/es.json | ~130 | ✅ |

### API эндпоинты (для мобильного приложения)

| Метод | URL | Auth | Описание |
|---|---|---|---|
| GET | /api/ping | — | Health check |
| POST | /api/auth/register | — | Регистрация |
| POST | /api/auth/login | — | Логин → токен |
| POST | /api/auth/logout | Sanctum | Отзыв токена |
| GET | /api/auth/user | Sanctum | Профиль |
| GET | /api/videos | — | Каталог (paginated, filtered) |
| GET | /api/videos/{id} | — | Детали видео |
| GET | /api/videos/{id}/access | Sanctum | Signed stream URL |

### Flutter Mobile App (mobile/)

| Компонент | Файлов | Статус |
|---|---|---|
| Config (pubspec.yaml, api_config, theme) | 3 | ✅ |
| Models (User, Video+pagination, Plan) | 3 | ✅ |
| Services (ApiService/Dio, AuthService/Provider) | 2 | ✅ |
| Screens (AgeGate, Login, Register, Home, Catalog, VideoPlayer, Plans, Profile) | 8 | ✅ |
| Widgets (VideoCard) | 1 | ✅ |
| Localization (AppLocalizations — en/ru/es, ~35 ключей) | 1 | ✅ |
| **Итого** | **18 dart-файлов** | ✅ |

**Важно:** Flutter SDK не установлен на машине. Для сборки нужно:
1. Установить Flutter SDK
2. `cd mobile && flutter create --org com.aividcatalog18 . --platforms android`
3. `flutter pub get && flutter run`

---

## Что НЕ сделано (следующие шаги)

### Приоритет 1 — Запуск и тестирование
- [ ] Запустить MySQL (XAMPP), создать БД `aividcatalog18`
- [ ] `php artisan migrate --seed` — создать таблицы и начальные данные
- [ ] `php artisan serve` — проверить лендинг, age gate, auth, каталог, admin
- [ ] Загрузить тестовые видео через admin panel
- [ ] Настроить NOWPayments sandbox ключи и протестировать webhook

### Приоритет 2 — Production Readiness
- [ ] Vite build для Tailwind CSS (заменить CDN)
- [ ] Rate limiting (throttle middleware в bootstrap/app.php)
- [ ] Email шаблон подтверждения подписки (Mailable)
- [ ] HTTPS сертификат (Let's Encrypt)
- [ ] Сменить пароль admin (сейчас `admin12345`)
- [ ] `.env` production-значения

### Приоритет 3 — Расширение функциональности
- [ ] Unit/Feature тесты (PHPUnit)
- [ ] Социальная авторизация (Socialite — Google, GitHub)
- [ ] Watermark для видео (FFmpeg интеграция)
- [ ] Автоматическое истечение подписок (scheduled command)
- [ ] Пользовательские избранные/закладки
- [ ] Расширенная админ-панель (статистика, графики, управление юзерами)
- [ ] Push-уведомления в мобильном приложении
- [ ] PWA-поддержка для веб-версии
- [ ] Дополнительные языки (de, fr, ja, zh)
- [ ] S3/CDN для видео-файлов в продакшене

---

## Ключевые файлы для навигации

```
├── .env.example                    # Конфигурация среды
├── config/nowpayments.php          # NOWPayments config
├── config/filesystems.php          # Диски videos + thumbnails
├── bootstrap/app.php               # Middleware aliases, routes
├── routes/web.php                  # 29 web routes
├── routes/api.php                  # 8 API routes
├── app/
│   ├── Models/                     # User, Plan, Video, Subscription, Payment
│   ├── Http/Controllers/
│   │   ├── LandingController.php   # Промо-лендинг
│   │   ├── AgeGateController.php   # Верификация возраста
│   │   ├── VideoController.php     # Публичный каталог
│   │   ├── PlanController.php      # Тарифы + покупка
│   │   ├── WebhookController.php   # NOWPayments IPN
│   │   ├── Admin/VideoController.php # CRUD видео
│   │   ├── Api/VideoApiController.php
│   │   └── Api/AuthApiController.php
│   ├── Http/Middleware/            # AgeVerification, Admin, Subscribed, Locale
│   ├── Services/NowPaymentsService.php  # Крипто-платежи
│   ├── Policies/VideoPolicy.php
│   └── Events/ + Listeners/       # SubscriptionActivated
├── resources/views/
│   ├── landing.blade.php           # Главная промо-страница
│   ├── age-gate.blade.php          # 18+ проверка
│   ├── videos/                     # Каталог + плеер
│   ├── plans/                      # Тарифы
│   └── admin/                      # Админ-панель
├── lang/                           # en.json, ru.json, es.json (~130 ключей)
├── database/migrations/            # 8 миграций
├── database/seeders/               # Plans + Admin user
└── mobile/                         # Flutter Android app (18 dart files)
```

---

## Аккаунты для тестирования

| Роль | Email | Пароль | Примечание |
|---|---|---|---|
| Admin | admin@aividcatalog18.com | admin12345 | Создаётся через seeder. **СМЕНИТЬ в проде!** |

---

## Архитектурные решения

1. **Видео хранятся на приватном диске** (`storage/app/videos/`) — никогда не доступны напрямую. Отдаются только через `VideoController@stream` с проверкой авторизации.

2. **Мультиязычные поля (title, description)** хранятся как JSON в MySQL — `{"en": "...", "ru": "...", "es": "..."}`. Метод `localizedTitle()` на модели выбирает по текущей локали.

3. **Подписка денормализована** — `users.subscription_end` дублирует `subscriptions.ends_at` для быстрой проверки `hasActiveSubscription()` без JOIN.

4. **NOWPayments webhook** верифицируется через HMAC-SHA512 (ksort JSON → hash_hmac с IPN_SECRET). CSRF отключён для webhook-маршрута.

5. **Age gate** — cookie `age_verified` на 1 год. Middleware `AgeVerification` проверяет на всех маршрутах кроме `/age-gate`, `/api/*`.
