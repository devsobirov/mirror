---
paths:
  - "database/migrations/**/*.php"
---
# Миграции

- Существующие миграции не редактировать, на проде они уже применены. Любое изменение схемы оформляется новой миграцией.
- Имя файла: `YYYY_MM_DD_HHMMSS_create_<table>_table.php` / `add_<fields>_to_<table>_table.php` с реальным timestamp.
- Деньги хранятся в UZS целыми числами (`unsignedBigInteger` / `integer`). `float` для денег не использовать.
  Дробные размеры и коэффициенты хранятся как `decimal(p, s)`.
- Новые связи объявляются через `foreignId(...)->constrained()` с явным `cascadeOnDelete()` / `nullOnDelete()` / `restrictOnDelete()`.
- `down()` реализуется всегда.
- Миграция должна проходить и на MySQL 8 (прод), и на sqlite (тесты). Сырой MySQL-SQL не писать.
- После добавления проверить: `migrate` на локальной БД и прогон тестов.
