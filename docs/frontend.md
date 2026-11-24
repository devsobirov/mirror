# Фронтенд

Страницы собираются на сервере через Blade, интерактив делается на Alpine.js. SPA и сборки фронта нет.

## Что реально подключено (`resources/views/admin/layouts/app.blade.php`)
| Ресурс | Откуда |
|---|---|
| Tabler CSS (на Bootstrap 5) | `public/build/assets/app.css`, готовый файл в git |
| Bootstrap 5.3 JS, IMask, autosize, axios | `public/build/assets/app.js`, готовый бандл в git |
| fslightbox | `public/build/assets/lightbox.js` |
| Dropzone | `public/build/assets/dropzone.{js,css}`, только формы с загрузкой |
| Alpine.js 3.12.1 | CDN jsDelivr, `defer` |
| ApexCharts | CDN, только `admin/admin-home.blade.php` |

`guest.blade.php` (логин) подключает тот же css, Alpine и app.js. Фон выбирается случайно из `public/assets/images/bg/{1..9}.jpg`.

**Vite, Tailwind и Sass не используются:**
- `vite.config.js`, `tailwind.config.js`, `postcss.config.js` и `resources/{js,sass,css}` остались от шаблона;
- `@vite` есть только в неиспользуемом `welcome.blade.php`;
- `public/build/manifest.json` устарел и ссылается на файлы, которых нет.

Исходников для пересборки `public/build/assets/*` в репозитории нет, эти файлы правят только вручную.

## Blade
- Страница начинается с `@extends('admin.layouts.app')` и заполняет `@section('content')`.
  Есть необязательные секции `custom_styles` и `custom_scripts`. Страницы auth используют `admin.layouts.guest`.
- Стеки `@stack('modals')` и `@stack('scripts')` объявлены, но ни одна страница в них не пушит.
- Модалки подключаются через `@include('finance.payment-modal', [...])`.
- Компоненты:
  - `<x-svg.имя class="icon">`: около 50 иконок Tabler в `resources/views/components/svg/`;
  - `<x-alert>`;
  - `<x-rating>`.
- Флеш-сообщения выводит `admin/layouts/includes/messages.blade.php`: ключи `success`, `msg` и `$errors`.

## Alpine
- Глобальные объекты объявлены в `admin/layouts/includes/_alpine-init.blade.php` на событие `alpine:init`:
  - `Alpine.store('messages')` хранит `errors` и `success` и умеет `handleErrors(res)` для AJAX;
  - `Alpine.data('alpineApp')` переключает тёмную тему и висит на `<html>`, тема сохраняется в localStorage;
  - `Alpine.data('passwordInput')`.
- Компонент страницы объявляется как `Alpine.data('Имя', () => ({...}))` внутри `@section('custom_scripts')`,
  например `OfferFormData` в `services/offer-form.blade.php`. Старые страницы используют глобальные функции-фабрики
  (`x-data="expenseModalData()"`) или inline-объекты.
- Модалки Bootstrap создаются напрямую: `new bootstrap.Modal(el)`.

## AJAX и данные
- Везде используется нативный `fetch`, axios в бандле есть, но не нужен.
- CSRF передаётся заголовком `'X-CSRF-TOKEN': '{{ csrf_token() }}'` прямо в запросе. Обычные формы используют `@csrf`.
- Данные из PHP в JS передаются через `@json(...)`. Для формы заказа это `CategoryWithServicesResource::collection`.
- Телефон в формах маскируется IMask по шаблону `(00) 000-00-00`, сервер потом оставляет только цифры.

## Язык и тексты
- Тексты интерфейса написаны прямо в шаблонах на узбекском (латиница). `__()` встречается только в auth-views от laravel/ui.
- Местами есть русский: JSON-сообщения `OffersController`, сообщения `SaveOfferRequest`, тултип темы.
  В middleware встречается узбекская кириллица.

## Навигация (`admin/layouts/navigation.blade.php`)
- Видно всем: Bosh menu (`home`), Buyurtmalar (`offers.index`), Moliya → Mijozlar / Qarzlar.
- Только админу (`@if isAdmin`): Boshqaruv → Xizmatlar tariflari, Xizmat turlari, Xodimlar, Printer sozlamalari.
- Ссылка на `/log-viewer` стоит вне проверки и видна всем.
- У админа шапка тёмная (`app.blade.php:18`).

## Печать
Чек — отдельная страница без layout, подробно описана в `docs/domain-orders.md`.
