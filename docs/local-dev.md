# Локальная разработка

На хосте нет PHP и Composer, всё работает в общей docker-среде `/mnt/data/projects/devenv`:
PHP 8.3-fpm, nginx, MySQL 8.4, проекты смонтированы в `/projects`.
Среда изолирована от `~/admin` (отдельный docker-стек, который запускается ежедневно; его не трогать, его порты не занимать).
Сама среда не стартует, её поднимают вручную.

## Команды (из `/mnt/data/projects/devenv`)
```bash
./dev up                     # поднять php, nginx, mysql
./dev down                   # остановить; данные БД остаются в volume devenv_mysql-data
./dev ps | logs [php|nginx|mysql]
./dev mysql mirror           # консоль MySQL
```
Команды проекта (artisan, тесты, линтер, демо-сидер) перечислены в `CLAUDE.md` → «Команды».

## Доступ
- Сайт: http://mirror.localhost:8088. Если не открывается, добавить `127.0.0.1 mirror.localhost` в `/etc/hosts`.
- Демо-пользователи (создаёт `DemoSettingsSeeder`):

| Телефон | Пароль | Роль |
|---|---|---|
| 900000000 | admin123 | Admin |
| 900000001 | demo123 | Manager |
| 900000002 | demo123 | Worker |

- MySQL снаружи: `127.0.0.1:33061`, root / root.
  - `mirror`: рабочая демо-БД (её использует `.env`);
  - `mirror_orig`: нетронутая копия прода, только для чтения и сравнения.
- Бэкапы и прод-дамп лежат на уровень выше: `../backups/*.sql.gz`, `../xonqa-tech.uz_db_*.sql.gz`.

## Демо-данные
`DemoSeeder` (команда в `CLAUDE.md`) полностью пересоздаёт каталог, заказы, финансы и настройки чека. Реальных пользователей он не трогает,
в production отказывается работать.

## Если что-то не работает
- Ошибка 500: `./dev art mirror-workshop/public_html optimize:clear`, затем смотреть `storage/logs/laravel.log`.
- Debugbar выключен в `.env`.
