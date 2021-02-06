# RSS

[![Build Status](https://secure.travis-ci.org/Gemorroj/Rss.png?branch=master)](https://travis-ci.org/Gemorroj/Rss)

### Requirements:

- PHP >= 7.3


### Installation:
```bash
composer require gemorroj/rss
```


Example:
```php
<?php
use Rss\Rss;

$rss = new Rss('example.com rss', 'http://example.com', 'some description');
$rss->addItem(
    'one item',
    'http://example.com',
    'some description',
    new DateTime('yesterday')
);

echo $rss->saveXML(); // rss xml
// $rss->output(); // send rss to browser
```
