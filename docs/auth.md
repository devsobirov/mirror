# Аутентификация и роли

## Вход
- Используются контроллеры `laravel/ui`. Входят **по телефону**: `LoginController::username()` возвращает `'phone'`,
  в форме `resources/views/auth/login.blade.php` поле `name="phone"`.
- `LoginController::credentials()` оставляет в телефоне только цифры. В БД `users.phone` хранится как 9 цифр без `+998`
  (`string(20) unique`).
- После входа пользователь попадает на `/home`. Там `HomeController@index` отправляет админа на `adminHome`, остальных на `workerHome`.
- Регистрация и email verify выключены (`routes/web.php:105-110`). `RegisterController` остался в коде, но не используется.
- Сброс пароля по email включён (`ForgotPasswordController`, `ResetPasswordController` с `min:6`).
  `users.email` nullable, поэтому пользователю без email сброс недоступен. Пароли сотрудникам задаёт админ в `admin.users`.

## Роли (`app/Traits/HasRole.php`)
| Константа | `users.role` | `getRoleName()` | `isAdmin()` | `isWorker()` |
|---|---|---|---|---|
| `ADMIN` | 99 | Admin | да | да |
| `MANAGER` | 88 | Manager | нет | да |
| `WORKER` | 66 | Xodim | нет | да |
| — | NULL / другое | Maqomsiz | нет | нет |

`isWorker()` возвращает true для 66, 88 и админа. Отдельных проверок на MANAGER в коде нет: роль заведена, но права не проработаны, по факту **MANAGER = WORKER**. Пока так и оставляем.

## Middleware (`bootstrap/app.php`)
- `worker` → `RoleWorkerMiddleware`: `abort_if(!isWorker(), 403)`. Висит на всём приложении.
- `admin` → `RoleAdminMiddleware`: `abort_if(!isAdmin(), 403)`. Висит на группе `/admin`.
- FormRequest'ы дополнительно проверяют роль в `authorize()`: `isWorker()` для заказов и финансов, `isAdmin()` для каталога, пользователей и склада-админки.

## Матрица доступов (текущее состояние)
| Возможность | Worker / Manager | Admin | Где проверяется |
|---|---|---|---|
| Дашборд воркера: последние 10 заказов, долги на сегодня с суммами | да | — (редирект на админский) | `HomeController@workerHome` |
| Админ-дашборд: выручка, графики, статистика долгов | нет | да | группа `admin` |
| Список заказов, создание, копирование, печать | да | да | `worker` |
| Сумма заказов, скидки и итоги в списке заказов | нет | да | `OffersController:46`, `services/offers.blade.php:48` |
| Смена статуса заказа | да | да | `OffersController::updateStatus` |
| Изменение клиента, телефона и даты заказа | нет | да | `OffersController:101,111` |
| Удаление заказа | только черновик | любой | `OffersController:125` |
| Клиенты, долги, приём оплаты | да | да | `worker` + FormRequest |
| Статистика по долгам на странице долгов | нет | да | `DebtsController:30` |
| Каталог услуг, категории | нет | да | `admin` + FormRequest |
| Пользователи, настройки чека | нет | да | `admin` |
| `/log-viewer` | ссылка в меню видна всем | | `navigation.blade.php:66`, см. ниже |
| Склад `/sklad/*` | да, по URL (в меню нет) | да | `worker`; правка и удаление см. `docs/legacy-warehouse.md` |

## Log viewer
Пакет `opcodesio/log-viewer`, путь `/log-viewer`. Middleware `web`, `auth`, `AuthorizeLogViewer`, в конфиге стоит `require_auth_in_production => true`.
Gate `viewLogViewer` и `LogViewer::auth()` в `app/` не определены. По словам владельца, на проде логи доступны всем ролям (задача в `.notes/todo.md`).
