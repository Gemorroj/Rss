<?php

declare(strict_types=1);

namespace Rss\Tests;

use PHPUnit\Framework\TestCase;
use Rss\Rss;

class RssTest extends TestCase
{
    public function testRss(): void
    {
        $date = new \DateTime('2024-01-01 12:00:00');

        $obj = new Rss('title 1', 'https://example.com/1', 'description 1', $date);
        $obj->addItem('title 2', 'https://example.com/2', 'description 2', null, 'guid-2');

        $expectedXml = '<?xml version="1.0" encoding="UTF-8"?>'."\n".
            '<rss version="2.0">'.
                '<channel>'.
                    '<title>title 1</title><link>https://example.com/1</link><description>description 1</description><pubDate>'.$date->format(\DateTimeInterface::RSS).'</pubDate>'.
                        '<item><title>title 2</title><link>https://example.com/2</link><description>description 2</description><guid isPermaLink="false">guid-2</guid></item>'.
                '</channel>'.
            '</rss>';
        self::assertXmlStringEqualsXmlString($expectedXml, $obj->saveXML());
    }

    public function testEscaping(): void
    {
        $obj = new Rss('a & b < c', 'https://example.com/?a=1&b=2', 'x & y');
        $obj->addItem('t & t', 'https://example.com/2?a=1&b=2', 'd < e');

        $xml = $obj->saveXML();
        self::assertStringContainsString('a &amp; b &lt; c', $xml);
        self::assertStringContainsString('https://example.com/?a=1&amp;b=2', $xml);
        self::assertStringContainsString('x &amp; y', $xml);
        self::assertStringContainsString('t &amp; t', $xml);
        self::assertStringContainsString('d &lt; e', $xml);
    }
}
