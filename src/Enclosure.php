<?php

declare(strict_types=1);

namespace Rss;

readonly class Enclosure
{
    public function __construct(
        public string $url,
        public int $length,
        public string $type,
    ) {
    }
}
