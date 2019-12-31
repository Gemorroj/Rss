<?php
namespace Rss\Tests;

use PHPUnit\Framework\TestCase;
use Rss\Rss;

class RssTest extends TestCase
{
    public function testRss(): void
    {
        $obj = new Rss('title 1', 'https://example.com/1', 'description 1');
        $obj->addItem('title 2', 'https://example.com/2', 'description 2');

        $expectedXml = '<?xml version="1.0" encoding="UTF-8"?>' . "\n" .
            '<rss version="2.0">' .
                '<channel>' .
                    '<title>title 1</title><link>https://example.com/1</link><description>description 1</description><pubDate>' . (new \DateTime())->format(\DateTime::RSS) . '</pubDate>' .
                        '<item><title>title 2</title><link>https://example.com/2</link><description>description 2</description></item>' .
                '</channel>' .
            '</rss>';
        self::assertXmlStringEqualsXmlString($expectedXml, $obj->saveXML());
    }
}
