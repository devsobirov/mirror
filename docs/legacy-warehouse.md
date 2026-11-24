# Наследие проекта-основы: склад и прочее

CRM построена поверх другого проекта: складского учёта для мастерской или автосервиса.
Склад (sklad) остался от него. Таблицы на проде пустые, в меню ссылок нет.

**Статус:** модуль заморожен, это техдолг. В перспективе его выпилят или переделают в платный модуль.
Пока что на него не должно быть доступа через сайт (задача в `.notes/todo.md`).
Правила работы с этими файлами лежат в `.claude/rules/legacy.md`.

## Что делает склад
Учёт товаров (goods) по складам: приход (income), расход (expense) с клиентом и фото, остатки, Excel-отчёты.
- `balances.type`: 1 приход, 4 расход, 7 отмена прихода, 8 отмена расхода (`Warehouse\Balance` константы).
- `balance_stats` хранит годовые остатки по товару и складу. Обновляет их `BaseWarehouseController::updateStat`.
- `warehouse_users(warehouse_id, user_id, action)` задаёт, на какой склад сотрудник может делать приход (1) и расход (2).
  Назначается в `admin.users`, трейт `HasWarehouses` у `User`.
- Расход создаёт запись в `clients` и сохраняет фото в `images` (`public/assets/images/clients/Y-m`).

## Карта файлов (для выпила)
| Слой | Файлы |
|---|---|
| Роуты | `routes/web.php:26-44` (`sklad.*`, доступ worker), `:75-89` (`admin.warehouses.*`, `admin.goods.*`, `admin.categories.*`, `admin.suppliers.*`) |
| Контроллеры | `app/Http/Controllers/Warehouse/*` (Base, Main, Income, Expense), `app/Http/Controllers/Admin/Warehouse/*` (Goods, Supplier, Warehouse) |
| Запросы | `app/Http/Requests/{GoodSaveRequest,CategorySaveRequest,SupplierSaveRequest,WarehouseSaveRequest,IncomeUpdateRequest,ExpenseUpdateRequest}.php` |
| Модели | `app/Models/Warehouse/*` (Balance, BalanceStat, Category, Good, Supplier, Warehouse) |
| Трейт | `app/Traits/HasWarehouses.php` (подключён в `User`) |
| Экспорты | `app/Exports/*`, `resources/views/exports/*` |
| Views | `resources/views/sklad/*`, `resources/views/admin/{goods,warehouse,suppliers}/*` |
| Миграции | `2024_11_03_093159_create_warehouses_table` (+ `warehouse_users`), `..._create_goods_table`, `..._create_balances_table`, `..._create_categories_table`, `..._create_suppliers_table`, `..._create_balance_stats_table` |
| Сидеры, фабрики | `GoodsTableSeeder`, `WarehouseTableSeeder`, `database/factories/GoodFactory.php` |
| Пакет | `maatwebsite/excel` нужен только складу |
| Ассеты | `public/build/assets/dropzone.*` (загрузка фото на складе и в форме услуги) |

## Пересечения с живыми модулями (при выпиле не сломать)
- `clients` и `App\Models\Client` использует и склад, и финансы. Таблицу оставить, убрать только связь `Balance::client`.
- `images` используется только складом.
- `HomeController` импортирует `App\Models\Warehouse\Balance` (строка 5).
- `UsersController` и `resources/views/admin/users/*` содержат назначение складов сотрудникам.
- `admin/services/form` использует dropzone.

## Прочие следы основы (мёртвый код)
- `database/seeders/CategoryWithServicesSeeder.php`: данные автосервиса.
- Неиспользуемые views:
  - `welcome.blade.php`, `home.blade.php`, `admin/home.blade.php`;
  - `auth/register`, `auth/verify`, `auth/profile`;
  - `components/calculator.blade.php`, `components/breadcrumbs.blade.php`;
  - `admin/layouts/includes/_editor-init.blade.php` (пустой).
- `App\Models\Finance\Client`: пустой подкласс.
- `RegisterController`, `VerificationController`: роуты выключены.
- Vite, Tailwind, Sass и `resources/{js,css,sass}`: страницы их не подключают (см. `docs/frontend.md`).
