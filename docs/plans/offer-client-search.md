# План: выбор клиента из базы при создании заказа

Ветка: `offer-client-search` (от `main`).

## Проблема

При создании заказа (`offers.form`, а также форма копирования `offers.copy`) данные клиента
каждый раз вводятся руками: имя, телефон, второй телефон. При этом:

1. **Повторный ввод.** Постоянные клиенты (они же должники в «насия дафтар», таблица `clients`)
   уже есть в базе, но форма заказа о них ничего не знает. Менеджер заново набирает имя и номер,
   ошибается в написании, и один и тот же клиент в заказах выглядит по-разному.
2. **Заказ не связан с клиентом.** В таблице `offers` колонка `client_id` (nullable) существует с первой
   миграции и связь `Offer::client()` объявлена, но колонка никогда не заполняется: клиент хранится
   только строками `client_name`, `phone`, `phone2`. Поэтому нельзя надёжно посмотреть все заказы клиента,
   свести заказы с его долгами и т.п.

## Что делаем (рамки задачи)

- **Текущий ручной ввод клиента не меняем.** Поля имени, телефонов, комментария остаются как есть,
  заказ без выбора клиента из базы сохраняется ровно так же, как раньше (`client_id = null`).
- **Добавляем окно поиска клиента.** Рядом с заголовком «Mijoz haqida ma'lumot» кнопка
  «Bazadan tanlash». Она открывает модалку: вводится номер (или его часть), по нему ищутся клиенты
  в `clients` по `phone` и `phone2`. Найденные выводятся списком, клик по строке:
  - заполняет `client.name`, `client.phone_number`, `client.phone_number2` в форме;
  - запоминает `client.id`;
  - закрывает модалку.
  После выбора поля остаются редактируемыми; над полями видна плашка «Bazadagi mijoz #id» с кнопкой
  отвязки (сбрасывает только `client.id`, введённые поля не трогает).
- **В заказ сохраняется id клиента.** Payload `offers.save` расширяется необязательным `client.id`,
  значение пишется в существующую колонку `offers.client_id`.

Не делаем: автосоздание клиента из заказа, редактирование клиента из формы заказа,
вывод клиента в списке/модалке заказа, перенос `client_id` при копировании заказа (в копии клиент пустой, как и сейчас).

## Реализация

### Бэкенд
1. **Роут** `GET offers/clients/search` → `OffersController@searchClients`, имя `offers.clients.search`
   (группа `auth` + `worker`, рядом с остальными `offers.*`).
2. **FormRequest** `app/Http/Requests/Offers/SearchClientRequest.php`:
   - `authorize()`: `isWorker()`;
   - `prepareForValidation()`: `phone` → только цифры через `PhoneNormalizer::clearPhoneNumber()`,
     ведущий `998` отрезается, если ввели номер целиком;
   - `rules()`: `phone` обязателен, строка, минимум 3 цифры, максимум 9.
3. **Действие** `searchClients`: `Client` где `phone` или `phone2` `LIKE %цифры%`, максимум 10 записей,
   ответ `{success, message, clients: [{id, name, phone, phone2, phone_number, phone_number2}]}`.
4. **Сохранение заказа:**
   - `SaveOfferRequest::prepareForValidation` кладёт `client_id = client.id`;
   - правило `client_id`: `nullable|integer|exists:clients,id`;
   - `OfferService::save` пишет `client_id` в `Offer`.
5. **Миграция не нужна**: `offers.client_id` уже есть (nullable, без FK — FK не добавляем,
   чтобы не рисковать на прод-данных).

### Фронт (обе формы: `offer-form.blade.php` и `offer-copy-form.blade.php`, по правилу `offer-calc.md`)
1. Состояние Alpine: `client.id = null`, `clientSearch = {phone, results, isLoading, searched, error}`.
2. Модалка `#clientSearchModal` (Bootstrap, как калькулятор): поле телефона с маской IMask `(00) 000-00-00`,
   кнопка «Qidirish» (и Enter), список результатов, текст «Mijoz topilmadi», если пусто.
3. `searchClients()` — `fetch` GET на `offers.clients.search` с заголовками JSON.
4. `selectClient(c)` — заполняет поля (телефоны в формате маски `(99) 123-45-67`), ставит `client.id`.
5. `unlinkClient()` — `client.id = null`.
6. В payload `saveOffer()` добавляется `client.id`.

### Документация
- `docs/domain-orders.md`: payload `client.id`, поиск клиента в форме.
- `docs/domain-finance.md`: `offers.client_id` теперь заполняется при выборе клиента из базы.
- `docs/routing.md`: новый роут `offers.clients.search`.

### Тесты (`tests/Feature/OfferClientSearchTest.php`, sqlite)
- поиск по части номера находит клиента по `phone` и по `phone2`, лишних не возвращает;
- короткий запрос (< 3 цифр) — 422;
- гость редиректится на логин;
- сохранение заказа с `client.id` пишет `offers.client_id`; без него — `null`;
- несуществующий `client.id` — 422.

## Проверка вручную
1. `DemoSeeder`, вход воркером, «Yangi buyurtma», добавить услугу.
2. «Bazadan tanlash» → ввести часть номера демо-клиента → выбрать → поля заполнены, плашка с id.
3. Сохранить → в `offers.client_id` записан id. Заказ без выбора → `client_id` пустой.
4. То же в форме копирования. Суммы заказа не меняются (расчёт не трогаем).
