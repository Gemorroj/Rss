<?php

declare(strict_types=1);

namespace Rss;

use Rss\Model\Enclosure;
use Rss\Model\Feed;
use Rss\Model\Item;

class Parser
{
    public function parse(string $xml): Feed
    {
        $document = new \DOMDocument();
        $previous = \libxml_use_internal_errors(true);

        try {
            if (false === $document->loadXML($xml, \LIBXML_NONET | \LIBXML_NOCDATA)) {
                throw new \InvalidArgumentException('Unable to parse the provided XML.');
            }
        } finally {
            \libxml_clear_errors();
            \libxml_use_internal_errors($previous);
        }

        $channel = $document->getElementsByTagName('channel')->item(0);
        if (!$channel instanceof \DOMElement) {
            throw new \InvalidArgumentException('The provided XML is not a valid RSS feed: no <channel> element found.');
        }

        $items = [];
        foreach ($channel->childNodes as $child) {
            if ($child instanceof \DOMElement && 'item' === $child->localName) {
                $items[] = $this->parseItem($child);
            }
        }

        return new Feed(
            $this->text($channel, 'title'),
            $this->text($channel, 'link'),
            $this->text($channel, 'description'),
            $this->date($channel, 'pubDate'),
            $items,
        );
    }

    private function parseItem(\DOMElement $element): Item
    {
        return new Item(
            $this->text($element, 'title'),
            $this->text($element, 'link'),
            $this->text($element, 'description') ?? $this->contentEncoded($element),
            $this->date($element, 'pubDate'),
            $this->text($element, 'guid'),
            $this->parseEnclosure($element),
        );
    }

    private function parseEnclosure(\DOMElement $element): ?Enclosure
    {
        $enclosure = $this->element($element, 'enclosure');
        if (null === $enclosure) {
            return null;
        }

        return new Enclosure(
            $enclosure->getAttribute('url'),
            (int) $enclosure->getAttribute('length'),
            $enclosure->getAttribute('type'),
        );
    }

    private function text(\DOMElement $parent, string $name): ?string
    {
        $element = $this->element($parent, $name);
        if (null === $element) {
            return null;
        }

        return $this->value($element);
    }

    private function contentEncoded(\DOMElement $parent): ?string
    {
        foreach ($parent->childNodes as $child) {
            if ($child instanceof \DOMElement
                && 'encoded' === $child->localName
                && 'http://purl.org/rss/1.0/modules/content/' === $child->namespaceURI
            ) {
                return $this->value($child);
            }
        }

        return null;
    }

    private function date(\DOMElement $parent, string $name): ?\DateTimeInterface
    {
        $value = $this->text($parent, $name);
        if (null === $value) {
            return null;
        }

        try {
            return new \DateTimeImmutable($value);
        } catch (\Exception) {
            return null;
        }
    }

    private function element(\DOMElement $parent, string $name): ?\DOMElement
    {
        foreach ($parent->childNodes as $child) {
            if ($child instanceof \DOMElement && $name === $child->localName) {
                return $child;
            }
        }

        return null;
    }

    private function value(\DOMElement $element): ?string
    {
        $value = \trim($element->textContent);

        return '' === $value ? null : $value;
    }
}
