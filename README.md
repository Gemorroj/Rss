# RSS

[![Continuous Integration](https://github.com/Gemorroj/Rss/workflows/Continuous%20Integration/badge.svg?branch=master)](https://github.com/Gemorroj/Rss/actions?query=workflow%3A%22Continuous+Integration%22)

### Requirements:

- PHP >= 8.0.2


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
    new \DateTime('yesterday')
);

echo $rss->saveXML(); // rss xml
// $rss->output(); // send rss to browser
```
