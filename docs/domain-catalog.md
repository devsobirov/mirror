# Каталог услуг

Каталог задаёт всё, что можно добавить в заказ: материалы, работы, доставку, фурнитуру.

## Файлы
| Что | Где |
|---|---|
| Контроллер (только админ) | `app/Http/Controllers/Admin/Service/ServiceController.php` |
| Запросы | `app/Http/Requests/Service/ServiceSaveRequest.php`, `CategorySaveRequest.php` |
| Модели | `app/Models/Services/Service.php`, `ServiceCategory.php` |
| Ресурсы для формы заказа | `app/Http/Resources/Services/ServiceResource.php`, `CategoryWithServicesResource.php` |
| Views | `resources/views/admin/services/{index,form,categories,category-form}.blade.php` |
| Демо-каталог | `database/seeders/Demo/DemoCatalogSeeder.php` (массив `catalog()`), это источник истины |

## Модель услуги
- Поля и типы описаны в `docs/database.md`.
- `price`: базовая цена в UZS за единицу `unit` (m², m, dona, reys, km, qavat…).
- `margin`: наценка в %. Клиент платит `full_price = price * (1 + margin/100)`, accessor добавлен в `$appends`.
- `calculator_id`: `square_meter`, `perimeter` или NULL. Задаёт, как форма заказа считает объём (`docs/domain-orders.md`).
- `active`: в форме заказа видны только активные услуги.
- `is_sub_service`: услугу можно прикрепить к другим как подуслугу. Флаг фильтрует только список выбора в админ-форме.
  Сама услуга при этом остаётся в каталоге формы заказа, если активна.
- `service_subservices`: какие подуслуги предлагаются для основной услуги. `serviceSave` синхронизирует их через
  `subServices()->sync(sub_services)` (`ServiceController.php:68`).
- Картинка загружается в `public/assets/images/services/` со случайным именем (`Service::IMAGE_PATH`, путь относительный).
  Старая картинка при замене не удаляется. Папка в `.gitignore`.
  Демо-плитки лежат в `public/assets/images/services/demo/*.svg` и генерируются сидером.

## Демо-каталог: как получен из прода
Прод-каталог (51 услуга, 10 категорий) нормализован: названия приведены к узбекской латинице, цены сохранены, дубли объединены.
Добавлены услуги по объявлениям OLX. Оригинал хранится в локальной БД `mirror_orig`.

Это тестовые услуги для демо. Термины прода трактованы примерно, и это не критично:
- «Yodviy/Yudiy» понято как бронзовая тонировка.
- «Pepiniy Zerkalo 140000» понято как зеркало 6 мм.
- «Tumoloq» понято как матовое.
- «Kalit qilish» понято как вырез под ручку или замок (поштучно).

Выброшены: Sensir (непонятно), лазер, Desp, Kamera, дубли PUX.

Добавлены новые позиции с ценами наугад:
- Mat oyna, Mebel oynasi;
- Evrokromka;
- Teshik, Kalit o'yig'i;
- Peskostruy naqsh, Gravirovka, Uzor;
- замер, монтаж, демонтаж, доставка, подъём на этаж, срочность;
- фурнитура.

Набор подуслуг для каждого стекла и зеркала (`GLASS_SUBS`): fatset, charxlash, evrokromka, peskostruy ramka,
plyonka, teshik, kalit o'yig'i, LED, yuvish, o'rnatish. У ромбика подуслуги свои: o'rnatish (rombik) и yelim.

## Снимок демо-каталога
| Kategoriya | ID | Nomi | Birlik | Narx | Ustama % | Formula | Sub |
|---|---|---|---|---|---|---|---|
| Oynalar | 1 | Oddiy oyna 4 mm | m² | 65000 | 10 | square_meter |  |
| Oynalar | 2 | Oddiy oyna 6 mm | m² | 110000 | 10 | square_meter |  |
| Oynalar | 3 | Oq xrustal oyna 4 mm | m² | 90000 | 10 | square_meter |  |
| Oynalar | 4 | Kulrang (grafit) oyna | m² | 90000 | 10 | square_meter |  |
| Oynalar | 5 | Bronza tonirovka oyna | m² | 90000 | 10 | square_meter |  |
| Oynalar | 6 | Naqshli oyna (ko'k gul) | m² | 85000 | 10 | square_meter |  |
| Oynalar | 7 | Mat oyna (satinato) | m² | 95000 | 10 | square_meter |  |
| Oynalar | 8 | Mebel oynasi (eshik/vitrina uchun) | m² | 100000 | 10 | square_meter |  |
| Ko'zgular | 9 | Ko'zgu kumush 4 mm | m² | 70000 | 10 | square_meter |  |
| Ko'zgular | 10 | Ko'zgu kumush 4 mm (premium) | m² | 75000 | 10 | square_meter |  |
| Ko'zgular | 11 | Ko'zgu kumush 6 mm | m² | 140000 | 10 | square_meter |  |
| Ko'zgular | 12 | Ko'zgu bronza | m² | 115000 | 10 | square_meter |  |
| Ko'zgular | 13 | Ko'zgu grafit (kulrang) | m² | 130000 | 10 | square_meter |  |
| Ko'zgular | 14 | Ko'zgu tillarang (zolotistka) | m² | 180000 | 10 | square_meter |  |
| Ko'zgular | 15 | Ko'zgu mat (tumanli) | m² | 85000 | 10 | square_meter |  |
| Rombik ko'zgular | 16 | Rombik ko'zgu kumush | m² | 350000 | 0 | square_meter |  |
| Rombik ko'zgular | 17 | Rombik ko'zgu bronza | m² | 400000 | 0 | square_meter |  |
| Rombik ko'zgular | 18 | Rombik ko'zgu tillarang | m² | 500000 | 0 | square_meter |  |
| Rombik ko'zgular | 19 | O'rnatish (rombik) | m² | 80000 | 0 | square_meter | sub |
| Rombik ko'zgular | 20 | Rombik uchun yelim | dona | 10000 | 0 | - | sub |
| Rombik ko'zgular | 21 | Baget ramka | m | 100000 | 0 | - |  |
| Qirra ishlov berish | 22 | Fatset charxlash | m | 4000 | 0 | perimeter | sub |
| Qirra ishlov berish | 23 | Qirrani charxlash (silliqlash) | m | 3500 | 0 | perimeter | sub |
| Qirra ishlov berish | 24 | Evrokromka charxlash | m | 5000 | 0 | perimeter | sub |
| Qirra ishlov berish | 25 | Teshik ochish | dona | 15000 | 0 | - | sub |
| Qirra ishlov berish | 26 | Kalit (tutqich) o'yig'i | dona | 20000 | 0 | - | sub |
| Bezak va dekor | 27 | Peskostruy ramka (chet bo'yicha) | m | 15000 | 0 | perimeter | sub |
| Bezak va dekor | 28 | Peskostruy naqsh (to'liq yuza) | m² | 60000 | 0 | square_meter | sub |
| Bezak va dekor | 29 | Gravirovka | dona | 50000 | 0 | - | sub |
| Bezak va dekor | 30 | Mebel oynasiga naqsh (uzor) | m² | 80000 | 0 | square_meter | sub |
| Bezak va dekor | 31 | Orqa plyonka (oboy) yopishtirish | m² | 40000 | 0 | square_meter | sub |
| Bezak va dekor | 32 | LED yoritish (ko'zgu chetiga) | m | 35000 | 0 | perimeter | sub |
| PVX kromka | 33 | PVX kromka 0.4×19 mm | m | 2500 | 0 | - |  |
| PVX kromka | 34 | PVX kromka 1×19 mm | m | 3500 | 0 | - |  |
| PVX kromka | 35 | PVX kromka 1×21 mm | m | 4000 | 0 | - |  |
| PVX kromka | 36 | PVX kromka 2×21 mm | m | 5500 | 0 | - |  |
| PVX kromka | 37 | PVX kromka 1×35 mm | m | 6500 | 0 | - |  |
| PVX kromka | 38 | PVX kromka 2×35 mm | m | 9000 | 0 | - |  |
| PVX kromka | 39 | PVX kromka Gold 1×35 mm | m | 9000 | 0 | - |  |
| PVX kromka | 40 | Kromkalash (mijoz materiali) | m | 2000 | 0 | - |  |
| Yetkazish va o'rnatish | 41 | O'lchov olish (usta chaqiruvi) | dona | 30000 | 0 | - |  |
| Yetkazish va o'rnatish | 42 | O'rnatish (oyna/ko'zgu) | m² | 40000 | 0 | square_meter | sub |
| Yetkazish va o'rnatish | 43 | Oyna yuvish (tozalash) | m | 1000 | 0 | perimeter | sub |
| Yetkazish va o'rnatish | 44 | Eski oynani demontaj qilish | m² | 20000 | 0 | square_meter |  |
| Yetkazish va o'rnatish | 45 | Yetkazib berish (shahar ichida) | reys | 50000 | 0 | - |  |
| Yetkazish va o'rnatish | 46 | Yetkazib berish (shahar tashqarisi) | km | 3000 | 0 | - |  |
| Yetkazish va o'rnatish | 47 | Qavatga ko'tarib chiqish | qavat | 10000 | 0 | - |  |
| Yetkazish va o'rnatish | 48 | Shoshilinch buyurtma (1 kunda) | dona | 50000 | 0 | - |  |
| Yetkazish va o'rnatish | 49 | Frezerda o'yish (Rover) | dona | 90000 | 0 | - |  |
| Furnitura | 50 | Ko'zgu mahkamlagichi (derjatel) | dona | 5000 | 0 | - |  |
| Furnitura | 51 | Silikon yelim (ko'zgu uchun) | dona | 40000 | 0 | - |  |
| Furnitura | 52 | Ikki tomonlama skotch | m | 3000 | 0 | - |  |
| Furnitura | 53 | Oyna tutqichi (ruchka) | dona | 25000 | 0 | - |  |
