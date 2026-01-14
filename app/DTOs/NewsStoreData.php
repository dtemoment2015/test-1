<?php

namespace App\DTOs;

class NewsStoreData
{
    public function __construct(
        public readonly string $title,
        public readonly string $description,
    ) {
    }

    public static function fromArray(array $data): self
    {
        return new self(
            title: $data['title'],
            description: $data['description'],
        );
    }
}
