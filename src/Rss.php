<?php

declare(strict_types=1);

namespace Rss;

class Rss extends \DOMDocument
{
    protected string $charset = 'UTF-8';
    protected \DOMNode $channel;

    public function __construct(string $title, string $link, string $description, ?\DateTimeInterface $date = null)
    {
        parent::__construct('1.0', $this->charset);

        $date ??= new \DateTime();
        $root = $this->createElement('rss');
        $root->setAttribute('version', '2.0');
        $rss = $this->appendChild($root);

        $channel = $rss->appendChild($this->createElement('channel'));

        $channel->appendChild($this->createElementWithText('title', $title));
        $channel->appendChild($this->createElementWithText('link', $link));
        $channel->appendChild($this->createElementWithText('description', $description));
        $channel->appendChild($this->createElementWithText('pubDate', $date->format(\DateTimeInterface::RSS)));

        $this->channel = $channel;
    }

    public function addItem(Item $item): void
    {
        $itemElement = $this->createElement('item');
        $itemElement->appendChild($this->createElementWithText('title', $item->title));
        $itemElement->appendChild($this->createElementWithText('link', $item->link));
        $itemElement->appendChild($this->createElementWithText('description', $item->description));

        if (null !== $item->date) {
            $itemElement->appendChild($this->createElementWithText('pubDate', $item->date->format(\DateTimeInterface::RSS)));
        }

        if (null !== $item->guid) {
            $guidElement = $this->createElementWithText('guid', $item->guid);
            $guidElement->setAttribute('isPermaLink', 'false');
            $itemElement->appendChild($guidElement);
        }

        if (null !== $item->enclosure) {
            $enclosureElement = $this->createElement('enclosure');
            $enclosureElement->setAttribute('url', $item->enclosure->url);
            $enclosureElement->setAttribute('length', (string) $item->enclosure->length);
            $enclosureElement->setAttribute('type', $item->enclosure->type);
            $itemElement->appendChild($enclosureElement);
        }

        $this->channel->appendChild($itemElement);
    }

    private function createElementWithText(string $name, string $value): \DOMElement
    {
        $element = $this->createElement($name);
        $element->textContent = $value;

        return $element;
    }

    /**
     * Output rss.
     */
    public function output(): void
    {
        \header('Content-Type: application/rss+xml; charset='.$this->charset);
        echo $this->saveXML();
    }
}
