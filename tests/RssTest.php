<?php
namespace Rss\Tests;

use Rss\Rss;

// backward compatibility for php 5.5 and low (with phpunit < v.6)
if (!\class_exists('\PHPUnit\Framework\TestCase') && \class_exists('\PHPUnit_Framework_TestCase')) {
    \class_alias('\PHPUnit_Framework_TestCase', '\PHPUnit\Framework\TestCase');
}

class RssTest extends \PHPUnit\Framework\TestCase
{
    public function testRss()
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
