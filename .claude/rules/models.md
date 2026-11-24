---
paths:
  - "app/Models/**/*.php"
  - "app/Enums/**/*.php"
  - "app/Traits/**/*.php"
---
# Модели, enum'ы, трейты

- Новая модель объявляет `$fillable` явно. В старых `$guarded = []` оставить как есть.
- Связанные классы импортируются через `use`: `Client::class` без импорта резолвится в текущий namespace (так сломан `Offer::client()`).
- Scope называется с префиксом `scope` (`scopeActive`); метод без префикса не работает как scope.
- В новых колонках даты кастуются в `casts()`, а enum-колонки — в соответствующий enum.
- Новые `$with` не добавлять: связи грузятся явно через `with()` в запросе.
- Клиент — это `App\Models\Client`. `App\Models\Finance\Client` не использовать.
- Для телефонов есть трейт `HasPhoneNumbers` (accessor'ы `phone_number`, `phone_number2`), второй форматтер не писать.
- В новом enum должны быть статические `list()` и `getName()` с подписями на узбекском, как в существующих.
- Роли проверяются только через методы `HasRole` (`isAdmin()`, `isWorker()`).
