<?php

declare(strict_types=1);

namespace Rss\Tests;

use PHPUnit\Framework\TestCase;
use Rss\Generator;
use Rss\Model\Enclosure;
use Rss\Model\Item;

class GeneratorTest extends TestCase
{
    public function testGenerator(): void
    {
        $date = new \DateTime('2024-01-01 12:00:00');

        $generator = new Generator('title 1', 'https://example.com/1', 'description 1', $date);
        $generator->addItem(new Item('title 2', 'https://example.com/2', 'description 2', null, 'guid-2'));

        $expectedXml = '<?xml version="1.0" encoding="UTF-8"?>'."\n".
            '<rss version="2.0">'.
                '<channel>'.
                    '<title>title 1</title><link>https://example.com/1</link><description>description 1</description><pubDate>'.$date->format(\DateTimeInterface::RSS).'</pubDate>'.
                        '<item><title>title 2</title><link>https://example.com/2</link><description>description 2</description><guid isPermaLink="false">guid-2</guid></item>'.
                '</channel>'.
            '</rss>';
        self::assertXmlStringEqualsXmlString($expectedXml, $generator->saveXML());
    }

    public function testEscaping(): void
    {
        $generator = new Generator('a & b < c', 'https://example.com/?a=1&b=2', 'x & y');
        $generator->addItem(new Item('t & t', 'https://example.com/2?a=1&b=2', 'd < e'));

        $xml = $generator->saveXML();
        self::assertStringContainsString('a &amp; b &lt; c', $xml);
        self::assertStringContainsString('https://example.com/?a=1&amp;b=2', $xml);
        self::assertStringContainsString('x &amp; y', $xml);
        self::assertStringContainsString('t &amp; t', $xml);
        self::assertStringContainsString('d &lt; e', $xml);
    }

    public function testEnclosure(): void
    {
        $generator = new Generator('title', 'https://example.com', 'description');
        $generator->addItem(new Item('title 1', 'https://example.com/1', 'description 1', null, null, new Enclosure('https://example.com/ep1.mp3', 12216320, 'audio/mpeg')));

        self::assertStringContainsString(
            '<enclosure url="https://example.com/ep1.mp3" length="12216320" type="audio/mpeg"/>',
            $generator->saveXML()
        );
    }
}
