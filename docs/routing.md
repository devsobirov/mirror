# Роутинг

Все роуты лежат в `routes/web.php`. API-роутов нет.

## Группы
| Строки | Middleware | Префикс / имя | Что внутри |
|---|---|---|---|
| 18–103 | `auth`, `worker` | — | всё приложение |
| 19–21 | | `home`, `worker.home` | `/` и `/home` → `HomeController@index` (редирект по роли); `/worker` |
| 23–24 | | `profile.show`, `profile.update` | `Admin\ProfileController` |
| 26–44 | | `sklad.*` | склад, см. `docs/legacy-warehouse.md` |
| 46–55 | | `offers.*` | `Service\OffersController`: `index`, `form`, `save`, `show`, `update-status`, `destroy`, `copy`, `get-print` |
| 57–64 | | `finance.*` | `debts.index`, `debts.create`, `debts.pay`, `clients.index`, `clients.show`, `clients.save` |
| 66–102 | + `auth`, `admin` | `/admin`, `admin.*` | дашборд и его AJAX (`chart.data`, `statistics.data`, `debts.data`), `users.*`, `services.*` (каталог), `print-settings.*`, склад-админка (`warehouses.*`, `goods.*`, `categories.*`, `suppliers.*`) |
| 105–110 | — | `Auth::routes` | `register` и `verify` выключены; login, logout, password reset и confirm включены |

Админская группа вложена в группу `auth`+`worker`, поэтому на неё действуют все три middleware.
`/log-viewer` регистрирует сам пакет (`config/log-viewer.php`).

## Соглашения
- Имя роута строится как `группа.ресурс.действие`: `finance.debts.pay`, `admin.services.categories.save`.
- Создание и изменение делает одна ручка: `POST .../save/{model?}` с необязательной route-model binding.
  Модель есть — это update, нет — create.
- REST-глаголы используются только в `offers` (`PUT update-status`, `DELETE /{offer}`) и в удалении на складе.
- Контроллеры импортируются в `use`, кроме `HomeController`: на него ссылаются полным именем.

## Тип ответа
| Ответ | Где |
|---|---|
| Blade view | все `index`, `form`, `show` страниц |
| `redirect()->back()->with('success', …)` | `*/save`, `finance.debts.create`, `finance.debts.pay`, `print-settings.save` |
| JSON | `offers.save` (если `wantsJson`): `{success, message, redirect, req_data}`; `offers.update-status`, `offers.destroy`; `offers.show` (`OfferResource`); `admin.chart.data`, `admin.statistics.data`, `admin.debts.data` |
| HTML-фрагмент для печати | `offers.get-print`. Его получают через `fetch` и открывают в `window.open` (`resources/views/services/offers.blade.php`) |
| XLSX | экспорты склада |
