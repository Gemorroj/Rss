<?php

declare(strict_types=1);

namespace Rss\Tests;

use PHPUnit\Framework\TestCase;
use Rss\Generator;
use Rss\Model\Enclosure;
use Rss\Model\Item;
use Rss\Parser;

class ParserTest extends TestCase
{
    public function testParsesGeneratedFeed(): void
    {
        $date = new \DateTimeImmutable('2024-01-01 12:00:00');

        $generator = new Generator('title 1', 'https://example.com/1', 'description 1', $date);
        $generator->addItem(new Item(
            'title 2',
            'https://example.com/2',
            'description 2',
            $date,
            'guid-2',
            new Enclosure('https://example.com/ep.mp3', 123, 'audio/mpeg'),
        ));

        $feed = (new Parser())->parse($generator->saveXML());

        self::assertSame('title 1', $feed->title);
        self::assertSame('https://example.com/1', $feed->link);
        self::assertSame('description 1', $feed->description);
        self::assertSame($date->format(\DateTimeInterface::RSS), $feed->date?->format(\DateTimeInterface::RSS));
        self::assertCount(1, $feed->items);

        $item = $feed->items[0];
        self::assertSame('title 2', $item->title);
        self::assertSame('guid-2', $item->guid);
        self::assertSame(123, $item->enclosure?->length);
        self::assertSame('audio/mpeg', $item->enclosure?->type);
    }

    public function testParsesForeignFeed(): void
    {
        $xml = '<?xml version="1.0" encoding="UTF-8"?>'
            .'<rss version="2.0" xmlns:content="http://purl.org/rss/1.0/modules/content/">'
            .'<channel>'
            .'<title>Foreign &amp; Feed</title>'
            .'<link>https://foreign.example.com</link>'
            .'<description>desc</description>'
            .'<pubDate>Mon, 06 Jan 2025 09:15:00 +0300</pubDate>'
            .'<item>'
            .'<title>No description item</title>'
            .'<content:encoded><![CDATA[<p>html body</p>]]></content:encoded>'
            .'<guid isPermaLink="true">https://foreign.example.com/1</guid>'
            .'</item>'
            .'</channel>'
            .'</rss>';

        $feed = (new Parser())->parse($xml);

        self::assertSame('Foreign & Feed', $feed->title);
        self::assertSame('2025-01-06 09:15:00', $feed->date?->format('Y-m-d H:i:s'));
        self::assertCount(1, $feed->items);

        $item = $feed->items[0];
        self::assertSame('No description item', $item->title);
        self::assertNull($item->link);
        self::assertSame('<p>html body</p>', $item->description);
        self::assertSame('https://foreign.example.com/1', $item->guid);
        self::assertNull($item->enclosure);
    }

    public function testInvalidXml(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        (new Parser())->parse('<rss><channel>');
    }

    public function testMissingChannel(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        (new Parser())->parse('<?xml version="1.0"?><rss version="2.0"></rss>');
    }

    public function testExternalEntityIsNotResolved(): void
    {
        $xml = '<?xml version="1.0"?>'
            .'<!DOCTYPE rss [<!ENTITY xxe SYSTEM "http://127.0.0.1:1/secret">]>'
            .'<rss version="2.0"><channel><title>&xxe;</title><description>d</description></channel></rss>';

        $feed = (new Parser())->parse($xml);

        self::assertNull($feed->title);
    }
}
