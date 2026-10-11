# RSS

Generates RSS 2.0 feeds.

[![Continuous Integration](https://github.com/Gemorroj/Rss/workflows/Continuous%20Integration/badge.svg)](https://github.com/Gemorroj/Rss/actions?query=workflow%3A%22Continuous+Integration%22)

### Requirements:

- PHP >= 8.2


### Installation:
```bash
composer require gemorroj/rss
```


Example:
```php
<?php
use Rss\Enclosure;
use Rss\Item;
use Rss\Rss;

$rss = new Rss('example.com rss', 'http://example.com', 'some description');
$rss->addItem(
    new Item(
        title: 'one item',
        link: 'http://example.com',
        description: 'some description',
        date: new \DateTime('yesterday'),
        guid: 'post-1',
        enclosure: new Enclosure('http://example.com/ep1.mp3', 12216320, 'audio/mpeg'),
    )
);

echo $rss->saveXML(); // rss xml
// $rss->output(); // send rss to browser
```
