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

    public function addItem(string $title, string $link, string $description, ?\DateTimeInterface $date = null, ?string $guid = null): void
    {
        $item = $this->createElement('item');
        $item->appendChild($this->createElementWithText('title', $title));
        $item->appendChild($this->createElementWithText('link', $link));
        $item->appendChild($this->createElementWithText('description', $description));

        if ($date) {
            $item->appendChild($this->createElementWithText('pubDate', $date->format(\DateTimeInterface::RSS)));
        }

        if ($guid) {
            $guidElement = $this->createElementWithText('guid', $guid);
            $guidElement->setAttribute('isPermaLink', 'false');
            $item->appendChild($guidElement);
        }

        $this->channel->appendChild($item);
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
