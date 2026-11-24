# Архитектура

Монолит Laravel 11 с серверным рендерингом (Blade) и Alpine.js на страницах. API, очередей и событий нет.
Баги и техдолг собраны в `.notes/todo.md`, сюда они не дублируются.

## Поток запроса
```
routes/web.php → middleware (auth, worker | admin) → FormRequest (authorize + prepareForValidation + rules)
  → Controller → [App\Services\*] → Eloquent Model → Blade view | JSON | API Resource
```
- Роуты и группы описаны в `docs/routing.md`, роли и доступы в `docs/auth.md`.
- Бизнес-логика лежит в основном в контроллерах. Сервисов два:
  - `app/Services/OfferService.php` сохраняет заказ с позициями;
  - `app/Services/Finance/DebtService.php` считает статус и рейтинг долга (статические методы).
- В `app/` нет ни одного `DB::transaction`.
- Ресурсы (`app/Http/Resources/*`) используются только для заказов: JSON для модалки, данные чека и формы.

## Структура `app/`
| Папка | Что внутри |
|---|---|
| `Enums/` | `Offer\OfferStatusEnum`, `Finance\DebtStatusEnum`, `Finance\PaymentTypeEnum`, `Service\ServiceCalculatorTypeEnum`. Подробно в `docs/database.md` |
| `Exports/` | Excel-отчёты склада (maatwebsite/excel) |
| `Helpers/PhoneNormalizer.php` | `clearPhoneNumber()` оставляет только цифры, `formatPhoneNumber()` форматирует в `+998 (xx) xxx-xx-xx` |
| `Http/Controllers/` | `Service/OffersController` (заказы), `Admin/*` (каталог, пользователи, профиль, склад-админка), `Finance/*`, `Warehouse/*` (склад), `Auth/*` (laravel/ui), `HomeController` (дашборды), `PrintSettingController` |
| `Http/Middleware/` | `RoleAdminMiddleware` (алиас `admin`), `RoleWorkerMiddleware` (алиас `worker`) |
| `Http/Requests/` | FormRequest'ы. Подпапки `Offers/`, `Finance/`, `Service/`, `Users/`; в корне лежат запросы склада |
| `Http/Resources/` | `Offers/OfferResource`, `Offers/OfferItemResource`, `Services/ServiceResource`, `Services/CategoryWithServicesResource` |
| `Models/` | `User`, `Client`, `Image`, `PrintSetting`, `Services/*`, `Finance/*`, `Warehouse/*` |
| `Services/` | см. выше |
| `Traits/` | `HasRole`, `HasPhoneNumbers` (accessor'ы `phone_number`, `phone_number2`), `HasWarehouses` |

## Bootstrap и конфигурация
- `bootstrap/app.php`: роуты web, console и `/up`; алиасы middleware `admin` и `worker`; обработка исключений по умолчанию.
- `bootstrap/providers.php`: только `AppServiceProvider`.
- `AppServiceProvider::boot`: `setlocale(LC_TIME, 'uz_UZ')`, `Carbon::setLocale('ru')`, `Paginator::useBootstrapFive()`.
- `config/app.php`: locale `en` (папка `lang/` есть только для `en`), timezone берётся из `APP_TIMEZONE` (`Asia/Tashkent`).
- `routes/console.php`: только стандартная команда `inspire`. Планировщик не используется.
- Пакеты: `laravel/ui` (auth-контроллеры), `maatwebsite/excel` (только склад), `opcodesio/log-viewer` (`/log-viewer`).
  В dev-зависимостях: `barryvdh/laravel-debugbar` (локально выключен), `barryvdh/laravel-ide-helper` (`_ide_helper*.php` в git), `laravel/pint`.

## Модули
| Модуль | Статус | Документ |
|---|---|---|
| Заказы и чек | живой, основной | `docs/domain-orders.md` |
| Каталог услуг | живой | `docs/domain-catalog.md` |
| Финансы (клиенты, долги, оплаты) | живой | `docs/domain-finance.md` |
| Дашборды (`HomeController`) | живой | `docs/routing.md`, `docs/auth.md` |
| Склад (sklad) | наследие проекта-основы, заморожен | `docs/legacy-warehouse.md` |
