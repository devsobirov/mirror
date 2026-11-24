# База данных

MySQL 8 (на проде и локально). Миграции лежат в `database/migrations/`. Тесты используют sqlite in-memory (`phpunit.xml`).
Часть кода работает только на MySQL: `DATE_FORMAT` и `YEAR` в `HomeController`, `SET FOREIGN_KEY_CHECKS` и `AUTO_INCREMENT` в демо-сидерах.

## Таблицы живых модулей
| Таблица | Ключевые колонки | Модель |
|---|---|---|
| `users` | `phone` string(20) unique, `role` int null, `email` null, `telegram_id` null | `App\Models\User` |
| `service_categories` | `title` unique | `Services\ServiceCategory` |
| `services` | `name`, `image` null, `category_id` idx, `active` bool, `is_sub_service` bool null, `unit` string, `price` int, `margin` float (%), `calculator_id` string null | `Services\Service` |
| `service_subservices` | `service_id`, `subservice_id`, unique pair | pivot `Service::subServices()` |
| `offers` | `cost` float, `status` int, `discount` int null, `client_name`, `phone` null, `phone2` null, `comment` null, `client_id` null (не используется), `manager_id` null | `Services\Offer` |
| `offer_items` | `offer_id` idx, `service_id` idx, `price`, `original_price`, `cost`, `item_cost` (float), `quantity` int, `x`, `y` float null (**см**), `dimension_amount` float default 1, `parent_item_id` null FK | `Services\OfferItem` |
| `print_settings` | `organization`, `phone1`, `phone2`, `type`, `thanks`. Одна строка, создаётся миграцией | `PrintSetting` |
| `clients` | `name`, `address`, `phone`, `phone2` null, без timestamps | `App\Models\Client` |
| `debts` | `client_id` idx, `amount` int, `payment_date` idx, `closed_at` null, `status` tinyint default 1, `caption` null, `offer_id` null, `rating` float null | `Finance\Debt` |
| `payments` | `client_id`, `debt_id` idx, `amount` int, `type` int, `left_debt_amount` int, `rating` int null | `Finance\Payment` |
| `images` | `filename`, `thumb_filename`, `rel_type`, `rel_id` (полиморфная связь вручную) | `Image` |

Склад (`warehouses`, `warehouse_users`, `goods`, `categories`, `suppliers`, `balances`, `balance_stats`) описан в `docs/legacy-warehouse.md`.
Остальные таблицы стандартные Laravel: `sessions`, `cache`, `jobs`, `password_reset_tokens`.

## Связи и целостность
- Foreign key в схеме ровно один: `offer_items.parent_item_id → offer_items.id ON DELETE SET NULL`.
  Остальные `*_id` — обычные `unsignedBigInteger`, а целостность держит только код.
- В моделях почти везде `$guarded = []` или `false`, `$fillable` есть только у `User`.
- `OfferItem` всегда грузит `subItems` рекурсивно через `$with`, а связь `service` тянет за собой `category`.
- SoftDeletes нигде не используется, удаление физическое.

## Деньги и числа
- Валюта везде UZS, копеек нет.
- `int`: `services.price`, `offers.discount`, `debts.amount`, все суммы в `payments`.
- `FLOAT` (одинарная точность): `offers.cost`, а в `offer_items` — `price`, `original_price`, `cost`, `item_cost`.
  В Laravel 11 `float('x', 10, 2)` принимает только precision, поэтому третий аргумент миграции игнорируется.
- `services.margin`: наценка в процентах. `Service::full_price = price * (1 + margin/100)`.
- `offer_items.x` и `y` хранятся в сантиметрах. `dimension_amount` — количество единиц услуги на одну штуку (м² или пог. м).
  Формулы расчёта описаны в `docs/domain-orders.md`.

## Enum'ы (`app/Enums`, int-backed, кроме калькулятора)
| Enum | Значения |
|---|---|
| `Offer\OfferStatusEnum` | Draft=1, Paid=5, Completed=7 (+ `getColor()`) |
| `Finance\DebtStatusEnum` | Pending=1, Paid=2, Cancelled=3 |
| `Finance\PaymentTypeEnum` | Cash=1, Card=2, CardTransfer=3, BankTransfer=4 |
| `Service\ServiceCalculatorTypeEnum` (string) | `square_meter`, `perimeter`. Отсутствие калькулятора = NULL |

Во всех enum'ах есть статические `list()` и `getName()` с подписями на узбекском. В моделях enum'ы не кастуются: в колонках лежат голые int и string.

## Миграции: текущая практика
- Файлы называются `YYYY_MM_DD_HHMMSS_create_X_table` / `add_..._to_X_table`. У последних трёх timestamp условный (`000001`).
- Изменения схемы делаются отдельными `add_*`/`change_*` миграциями. `down()` есть везде, кроме `2025_02_23_130012_change_phone_field_to_nullable_on_offers_table.php`.
- `print_settings` получает строку по умолчанию прямо в миграции.

## Сидеры
- `DatabaseSeeder` вызывает `UserTableSeeder` и `ServicesTableSeeder`. Повторный запуск упадёт на unique title.
- Старые сидеры не вызываются: `OfferSeeder`, `DebtSeeder`, `GoodsTableSeeder`, `WarehouseTableSeeder`, `CategoryWithServicesSeeder` (данные автосервиса от проекта-основы).
- Демо-данные создаёт `database/seeders/Demo/DemoSeeder`. Запуск и назначение описаны в `docs/local-dev.md`.
  - `DemoSettingsSeeder`: `print_settings` и демо-пользователи (upsert по телефону, реальные пользователи не трогаются).
  - `DemoCatalogSeeder`: очищает каталог (truncate), заливает его заново и генерирует SVG-плитки в `public/assets/images/services/demo/`.
  - `DemoOrdersSeeder`: очищает заказы и финансы (truncate) и создаёт около 75 дней заказов с #1001. Детерминирован (`mt_srand(20261124)`).
  - Защита от production есть только в `DemoSeeder`. Дочерние сидеры при прямом запуске её не проверяют.
- Фабрики: `GoodFactory`, `UserFactory`. `UserFactory` стандартный, без `phone`, а у `User` нет `HasFactory`, поэтому для тестов его нужно доработать.
