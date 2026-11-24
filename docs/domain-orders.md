# Заказы (offers) и чек

Это главный модуль: менеджер собирает заказ из услуг каталога, система считает стоимость и печатает чек.

## Файлы
| Что | Где |
|---|---|
| Контроллер | `app/Http/Controllers/Service/OffersController.php` |
| Сохранение | `app/Services/OfferService.php`, `app/Http/Requests/Offers/SaveOfferRequest.php` |
| Модели | `app/Models/Services/Offer.php`, `OfferItem.php` |
| Ресурсы | `app/Http/Resources/Offers/OfferResource.php`, `OfferItemResource.php` |
| Список, модалка, печать | `resources/views/services/offers.blade.php`, `offer-modal.blade.php` |
| Форма создания | `resources/views/services/offer-form.blade.php` (Alpine `OfferFormData`) |
| Форма копирования | `resources/views/services/offer-copy-form.blade.php` (почти копия формы, плюс `initialData`) |
| Чек | `resources/views/prints/offer.blade.php` |

## Расчёт (только в JS формы)
Сервер ничего не пересчитывает, он сохраняет то, что прислала форма. Так задумано, пересчёт на сервере не нужен. Формулы в `offer-form.blade.php`:
`calcByItem()` (~684), `calc()` (~1061), `getItemTotal()` (~845), `getItemSum()` (~850), `getTotalSum()`, `getFinalTotal()`.

1. Пользователь выбирает основную услугу, и появляется строка с `quantity = 1`, `dimension_amount = 1`.
2. Если у услуги задан `calculator_id`, вводятся ширина `x` и высота `y` **в см** и количество:
   - `square_meter`: `dim = x * y * 0.0001` (м² на штуку);
   - `perimeter`: `dim = (x + y) * 2 * 0.01` (пог. м на штуку);
   - без калькулятора `dim = 1`, а объём вводится через `quantity` или через модалку «O'lcham» (`#manualInputModal`).
3. Подуслуги добавляются в той же модалке. Подуслуга наследует `x`, `y`, `quantity` родителя, а `dim` считает по своему калькулятору.
   Список доступных подуслуг берётся из `Service::subServices()` (см. `docs/domain-catalog.md`).
4. Стоимость строки: `item_cost = (custom_price || full_price) * dim * quantity`.
   Цену можно переопределить вручную: тогда `price` хранит новую цену, а `original_price` каталожную.
5. `cost` родителя равен `item_cost + Σ cost подуслуг`.
   `offers.cost` равен `Σ cost родителей − discount` (считает `OfferService::save`).

Эталон из демо (#1001): Ko'zgu kumush 4 mm, 83×145 см, 1 шт.
- 1.2035 м² × 77 000 = 92 669.5
- + Fatset 4.56 м × 4 000 = 18 240
- + Yuvish 4.56 м × 1 000 = 4 560
- итого строка = **115 470**

## Сохранение `POST offers.save`
Payload (`prepareOfferItems` / `saveOffer` в форме):
```
{ client: {name, phone_number, phone_number2, note}, status, discount, created_at: 'YYYY-MM-DD',
  offer_items: [{service_id, quantity, price, original_price, item_cost, cost, x, y, dimension_amount,
                 sub_items: [{…те же поля}]}] }
```
- `SaveOfferRequest::prepareForValidation` раскладывает `client.*` по полям `client_name`, `phone`, `phone2`, `comment`,
  оставляет в телефонах только цифры и дописывает к дате текущее время. Статус по умолчанию — Draft.
- `OfferService::save` проставляет `manager_id = auth()->id()`, создаёт `Offer`, затем позиции, затем подпозиции
  (`parent_item_id`, `offer_id`).
- Ответ: `{success, message, redirect, req_data}`. Если `success`, форма делает редирект на `redirect`.

## Прочие операции
- **Редактирования заказа нет, и это не баг.** Копирование — отдельная функция: `offers.copy` открывает форму копирования, где
  `initialData` содержит позиции и подпозиции, клиента пустого и сегодняшнюю дату. Сохранение создаёт новый заказ.
- `offers.update-status` (PUT, JSON): статус из `OfferStatusEnum`. Админ может также поменять клиента, телефоны и дату.
- `offers.destroy`: физическое удаление. Не-админ может удалить только черновик.
- `offers.index`: фильтры `search` (имя, телефоны), `date_from`, `date_to`, `status`; 25 на страницу; итоги видит только админ.
- `offers.show` возвращает `OfferResource` для модалки просмотра.

## Статусы (`OfferStatusEnum`)
| Статус | Значение |
|---|---|
| Draft | 1 |
| Paid | 5 |
| Completed | 7 |

Статистика и дашборды не учитывают черновики.

## Чек (`offers.get-print`)
- Чек — отдельная HTML-страница шириной 80 мм для термопринтера (`@page margin 2mm`, Arial Narrow 12px).
  Список заказов получает её через `fetch`, пишет в `window.open` и вызывает `print()`.
- Шапка и подвал берутся из `print_settings`, первая строка таблицы: `organization`, `phone1`, `phone2`, `thanks`.
  Редактируется в `admin.print-settings`. Поле `type` тоже редактируется, но на чеке не выводится: его задумывали, но не понадобилось, оставлено как есть.
- Позиции верхнего уровня проходят через `OfferItemResource`:
  - `memo` выглядит как `"{x} sm x {y} sm = {dim} {unit}"`;
  - `full_memo` добавляет строки подуслуг;
  - суммы форматируются через `number_format(…, 0, '.', ' ')`.
- `$totals` — суммарный объём по каждой услуге (`Σ dimension_amount * quantity`). Услуги с одной позицией и `quantity == 1` пропускаются.
- Внизу чека выводятся Jami (`cost + discount`), Chegirma и To'lovga (`cost`).
