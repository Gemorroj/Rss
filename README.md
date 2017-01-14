# RSS

[![Build Status](https://secure.travis-ci.org/Gemorroj/Rss.png?branch=master)](https://travis-ci.org/Gemorroj/Rss)

### Requirements:

- PHP >= 5.3


### Installation:

- Add to composer.json:

```json
{
    "require": {
        "gemorroj/rss": "dev-master"
    }
}
```
- install project:

```bash
$ composer update gemorroj/rss
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

echo $rss->output(); // rss xml
```
