<?php

declare(strict_types=1);

namespace Rss\Model;

readonly class Item
{
    public function __construct(
        public ?string $title = null,
        public ?string $link = null,
        public ?string $description = null,
        public ?\DateTimeInterface $date = null,
        public ?string $guid = null,
        public ?Enclosure $enclosure = null,
    ) {
    }
}
