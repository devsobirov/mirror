---
paths:
  - "app/Http/Controllers/**/*.php"
  - "routes/**/*.php"
---
# Контроллеры и роуты

- Входные данные валидируются только через FormRequest (`app/Http/Requests/<Домен>/…`). Новых `$request->validate()` не добавлять.
- В контроллере берутся только `$request->validated()`; `$request->all()` и `except()` не использовать.
- Права проверяются middleware группы (`worker` / `admin`) и `authorize()` в FormRequest.
  Если внутри действия нужна точечная проверка, писать `auth()->user()->isAdmin()`, а не сравнивать `role` с числом.
- Новый роут кладётся в нужную группу `routes/web.php` и получает имя по схеме `группа.ресурс.действие`.
  Создание и изменение делаются одной ручкой: `POST …/save/{model?}`.
- AJAX-ответ имеет вид `response()->json(['success' => bool, 'message' => string, …])`.
  Обычная форма возвращает `redirect()->back()->with('success', '…')`. Ключ `error` пока не использовать: layout его не выводит.
- Если запись идёт в больше чем одну таблицу, логика выносится в `app/Services/*` (см. правила сервисов), а не пишется в контроллере.
- Телефоны нормализуются через `App\Helpers\PhoneNormalizer::clearPhoneNumber()`, новых приватных `clearPhone()` не заводить.
- Статусы и типы задаются через `app/Enums/*` (`OfferStatusEnum::Draft->value`), а не числами.
- Новые и изменённые ручки покрываются feature-тестом (см. правила тестов).
