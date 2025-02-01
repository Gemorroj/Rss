<?php

declare(strict_types=1);

namespace Rss;

class Rss extends \DOMDocument
{
    protected string $charset = 'UTF-8';
    protected \DOMNode $channel;

    public function __construct(string $title, string $link, string $description)
    {
        parent::__construct('1.0', $this->charset);

        $date = new \DateTime();
        $root = $this->createElement('rss');
        $root->setAttribute('version', '2.0');
        $rss = $this->appendChild($root);

        $channel = $rss->appendChild($this->createElement('channel'));

        $channel->appendChild($this->createElement('title', $title));
        $channel->appendChild($this->createElement('link', $link));
        $channel->appendChild($this->createElement('description', $description));
        $channel->appendChild($this->createElement('pubDate', $date->format(\DateTimeInterface::RSS)));

        $this->channel = $channel;
    }

    public function addItem(string $title, string $link, string $description, ?\DateTimeInterface $date = null): void
    {
        $item = $this->createElement('item');
        $item->appendChild($this->createElement('title', $title));
        $item->appendChild($this->createElement('link', $link));
        $item->appendChild($this->createElement('description', $description));

        if ($date) {
            $item->appendChild($this->createElement('pubDate', $date->format(\DateTimeInterface::RSS)));
        }

        $this->channel->appendChild($item);
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
