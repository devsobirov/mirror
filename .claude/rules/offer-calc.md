---
paths:
  - "resources/views/services/offer-form.blade.php"
  - "resources/views/services/offer-copy-form.blade.php"
  - "resources/views/prints/**"
  - "app/Services/OfferService.php"
  - "app/Http/Requests/Offers/**"
  - "app/Http/Resources/Offers/**"
  - "app/Http/Controllers/Service/OffersController.php"
  - "database/seeders/Demo/DemoOrdersSeeder.php"
---
# Расчёт заказа и чек

Описание расчёта — в `docs/domain-orders.md`.

- Формула расчёта существует в четырёх местах:
  - `offer-form.blade.php`;
  - `offer-copy-form.blade.php` (копия);
  - `DemoOrdersSeeder`;
  - вывод на чеке (`OfferItemResource`, `$totals` в `OffersController::getPrint`).
  Изменение в одном месте вносится во все.
- Если меняется payload `offers.save`, одновременно меняются обе формы, `SaveOfferRequest::rules()` и `OfferService::save`.
- После изменения проверить на эталоне: демо-заказ #1001 после `DemoSeeder` в форме, в модалке и на чеке совпадает с эталоном из `docs/domain-orders.md`.
- Изменение расчёта покрывается тестом с конкретными числами.
