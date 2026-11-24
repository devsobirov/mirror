# Финансы: клиенты, долги (насия), оплаты

Модуль ведёт долги клиентов: кто сколько должен, к какой дате, сколько и как заплатил. В меню он называется «Moliya».

## Файлы
| Что | Где |
|---|---|
| Контроллеры | `app/Http/Controllers/Finance/ClientsController.php`, `DebtsController.php` |
| Запросы | `app/Http/Requests/ClientSaveRequest.php`, `Finance/DebtCreateRequest.php`, `Finance/PaymentCreateRequest.php` |
| Сервис | `app/Services/Finance/DebtService.php` (статические методы) |
| Модели | `app/Models/Client.php`, `Finance/Debt.php`, `Finance/Payment.php` |
| Views | `resources/views/finance/{clients,client,debts}.blade.php` + модалки `client-modal`, `debt-modal`, `payment-modal` |

## Клиенты
- Таблица `clients`, модель `App\Models\Client`. Пустой подкласс `App\Models\Finance\Client` нигде не используется.
- Финансовый клиент не связан с заказом: в `offers` клиент хранится строкой `client_name` и телефонами,
  а `offers.client_id` не заполняется.
- `ClientSaveRequest` нормализует телефоны через `PhoneNormalizer` и проверяет уникальность каждого номера сразу по `phone` и `phone2`.
- `clients.index` считает долг как `withSum(debts.amount) − withSum(payments.amount)`.

## Долг
- Создание: `finance.debts.create` → `Debt::create($request->validated())`.
  Поля: `created_at`, `payment_date` (срок), `amount`, `client_id`, `caption`.
  Статус берётся из default колонки, то есть Pending.
- Статусы (`DebtStatusEnum`): Pending=1, Paid=2, Cancelled=3. Cancelled в коде нигде не выставляется.
- Accessors `Debt`:
  - `paid_amount` = Σ payments.amount;
  - `left_amount` = amount − paid_amount;
  - `status_name`.
  Они читают связь `payments` и подгружают её лениво.
- Scopes: `byStatus`, `active`, `byPaymentDateFrom`/`To`, `byClient`.
- `finance.debts.index`: фильтр статуса по умолчанию Pending. Блок статистики видит только админ.

## Оплата
`finance.debts.pay/{debt}`:
1. `PaymentCreateRequest::prepareForValidation` подставляет `debt_id` и `left_debt_amount = max(0, left_amount − amount)`.
   Это снимок остатка на момент оплаты, в коде он не читается.
2. Правила: `amount` не меньше 1000 и не больше остатка долга, `client_id` обязателен.
   `type` берётся из `PaymentTypeEnum`: Cash=1, Card=2, CardTransfer=3, BankTransfer=4.
   `rating` — оценка платёжной дисциплины.
3. Создаётся `Payment::create(...)`, затем вызываются `DebtService::updateDebtStatus`
   (Paid и `closed_at = now()` при полной оплате) и `updateDebtRating` (средняя оценка оплат > 0 записывается в `debts.rating`).

## Дашборды
- Админ-дашборд показывает статистику долгов и график «долги/оплаты» по неделям, месяцам и годам
  (`HomeController::getDebtsData`, `admin.debts.data`).
- Дашборд воркера показывает долги со сроком на сегодня и сумму, полученную по ним.
