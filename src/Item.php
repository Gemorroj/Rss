<?php

declare(strict_types=1);

namespace Rss;

readonly class Item
{
    public function __construct(
        public string $title,
        public string $link,
        public string $description,
        public ?\DateTimeInterface $date = null,
        public ?string $guid = null,
        public ?Enclosure $enclosure = null,
    ) {
    }
}
