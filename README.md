# RSS

[![Build Status](https://secure.travis-ci.org/Gemorroj/Rss.png?branch=master)](https://travis-ci.org/Gemorroj/Rss)

Requirements:

- PHP >= 5.2


Example:
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