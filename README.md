# PHP библиотека для работы с Safe Browsing API Яндекса

## Установка

`$ curl -s https://getcomposer.org/installer | php`

Добавить в composer.json:

```
"require": {
    "llprod/yasfbrowsingapi": "dev-master"
}
```

## Использование

```php
require 'vendor/autoload.php';

// Ключ, полученный на https://developer.tech.yandex.ru
$SfBrowsing = new YaSfBrowsing\YaSfBrowsingAPI('aaaaaaaa-aaaa-aaaa-aaaa-aaaaaaaaaaaa');

// URL, который вы хотите проверить
$find = $SfBrowsing->find('https://neon.today');
```

Результатом выполнения будет **NULL**, если сайт по мнению Яндекса безопасен или **объект класса Match** c описанием найденной угрозы

## Проверка на Adult

```php
require 'vendor/autoload.php';

// Ключ, полученный на https://developer.tech.yandex.ru
$SfBrowsing = new YaSfBrowsing\YaSfBrowsingAPI('aaaaaaaa-aaaa-aaaa-aaaa-aaaaaaaaaaaa');

// URL, который вы хотите проверить
$adult = $SfBrowsing->adult('https://adult.url');
```

Результат выполнения: **true** - сайт для взрослых, **false** - по мнению Яндекса, сайт не для взрослых
___

[Условия использования сервиса «Safe Browsing API Яндекса»](https://yandex.ru/legal/yandex_sb_api/)
