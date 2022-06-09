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
## Создание демо контента 
Открываем "Командная php-строка" в административном интерфейсе сайта и запускем следующий код 
```
require $_SERVER['DOCUMENT_ROOT'].'/createDemoContent.php';

FIS\CreateDemoContent::generete();
```

### Просмотр карты с элементами
```
#HTTP_ORIGIN#/offices_map/
```