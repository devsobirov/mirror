---
paths:
  - "app/Http/Controllers/Warehouse/**"
  - "app/Http/Controllers/Admin/Warehouse/**"
  - "app/Models/Warehouse/**"
  - "app/Exports/**"
  - "app/Traits/HasWarehouses.php"
  - "resources/views/sklad/**"
  - "resources/views/exports/**"
  - "resources/views/admin/goods/**"
  - "resources/views/admin/warehouse/**"
  - "resources/views/admin/suppliers/**"
---
# Склад (legacy, заморожен)

Подробности и карта файлов — в `docs/legacy-warehouse.md`.

- Новый функционал в склад не добавлять. Допустимы только исправления безопасности и закрытие доступа.
- Живые модули (заказы, каталог, финансы) не должны получать новых зависимостей от складских классов.
- Если правка затрагивает общие сущности (`clients`, `User`, `UsersController`), отметить это в `docs/legacy-warehouse.md`.
