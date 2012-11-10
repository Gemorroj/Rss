# Работа с RSS.

Пример:
```php
<?php
require 'Rss.php';

$rss = new Rss('example.com rss', 'http://example.com', 'some description');
$rss->addItem(
    'one item',
    'http://example.com',
    'some description',
    new DateTime('yesterday')
);

echo $rss->output();
```