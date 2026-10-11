# RSS

Generates and parses RSS 2.0 feeds.

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
use Rss\Generator;
use Rss\Model\Enclosure;
use Rss\Model\Item;

$generator = new Generator('example.com rss', 'http://example.com', 'some description');
$generator->addItem(
    new Item(
        title: 'one item',
        link: 'http://example.com',
        description: 'some description',
        date: new \DateTime('yesterday'),
        guid: 'post-1',
        enclosure: new Enclosure('http://example.com/ep1.mp3', 12216320, 'audio/mpeg'),
    )
);

echo $generator->saveXML(); // rss xml
// $generator->output(); // send rss to browser
```

Parse:
```php
<?php
use Rss\Parser;

$xml = '<?xml version="1.0" encoding="UTF-8"?>
<rss version="2.0">
    <channel>
        <title>example.com rss</title>
        <link>http://example.com</link>
        <description>some description</description>
        <item>
            <title>one item</title>
            <link>http://example.com/item/1</link>
            <description>some description</description>
            <pubDate>Wed, 08 Jan 2025 12:00:00 +0000</pubDate>
            <guid isPermaLink="false">post-1</guid>
            <enclosure url="http://example.com/ep1.mp3" length="12216320" type="audio/mpeg"/>
        </item>
    </channel>
</rss>';

$feed = (new Parser())->parse($xml);

echo $feed->title;                              // example.com rss
echo $feed->items[0]->title;                    // one item
echo $feed->items[0]->guid;                     // post-1
echo $feed->items[0]->date?->format('Y-m-d');   // 2025-01-08
echo $feed->items[0]->enclosure?->type;         // audio/mpeg
```
