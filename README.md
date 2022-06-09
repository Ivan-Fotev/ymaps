# fis ymaps

Этот репозиторий содержит механизм генерации демо контента и отображения созданного контента инфоблока на яндекс картах.

## Структура
```
.
├── createDemoContent.php
├── local
│   └── components
│       └── fis
│           └── ymaps
│               ├── class.php
│               ├── .description.php
│               ├── lang
│               │   └── ru
│               │       ├── class.php
│               │       ├── .description.php
│               │       └── .parameters.php
│               ├── .parameters.php
│               └── templates
│                   └── .default
│                       ├── script.js
│                       ├── style.css
│                       └── template.php
└── offices_map
├── index.php
└── .section.php
```

## Клонирование репозитория
```
git init .
git remote add -t \* -f origin https://github.com/Ivan-Fotev/ymaps.git
git checkout master
```
## Создание демо контента 
Открываем "Командная php-строка" в административном интерфейсе сайта и запускаем следующий код.
```
require $_SERVER['DOCUMENT_ROOT'].'/createDemoContent.php';

FIS\CreateDemoContent::generete();
```

### Просмотр карты с элементами
```
#HTTP_ORIGIN#/offices_map/
```

### Демо
```
http://gpn-ymap.0x000.ru/offices_map/
http://gpn-ymap.0x000.ru/s2/
http://gpn-ymap.0x000.ru/s3/
```