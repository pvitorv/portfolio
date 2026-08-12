<?php

namespace App\Support;

class LinkHubItem
{
    public function __construct(
        public readonly string $title,
        public readonly string $href,
        public readonly ?string $description = null,
        public readonly string $source = 'custom',
        public readonly bool $editable = false,
        public readonly ?string $meta = null,
    ) {}
}
