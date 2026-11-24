---
paths:
  - "resources/views/**/*.blade.php"
  - "public/build/**"
---
# Blade и фронт

- Страница: `@extends('admin.layouts.app')`, `@section('content')`, скрипты в `@section('custom_scripts')`.
- Alpine-компонент страницы объявляется как `Alpine.data('Имя', () => ({…}))` внутри `custom_scripts`.
  Глобальные вещи (store, тема) живут только в `admin/layouts/includes/_alpine-init.blade.php`.
- AJAX делается через `fetch` с заголовками `Content-Type: application/json`, `Accept: application/json`, `X-CSRF-TOKEN: {{ csrf_token() }}`.
  Ошибки валидации выводятся через `$store.messages.handleErrors(...)` или по месту.
- Данные из PHP в JS передаются только через `@json(...)` (лучше в виде API Resource), а не склейкой строк.
- Иконки берутся из `<x-svg.*>`, классы — из Tabler/Bootstrap 5. Tailwind-классы и `@vite` не использовать.
- Новые библиотеки не подключать без необходимости. Если всё же нужна, это CDN с фиксированной версией.
- `public/build/assets/*` — готовые файлы без исходников. Не пересобирать и не перезаписывать целиком.
- Тексты интерфейса пишутся на узбекском (латиница).
- Пункты меню для админа оборачиваются в `@if(auth()->user()->isAdmin())` в `admin/layouts/navigation.blade.php`.
