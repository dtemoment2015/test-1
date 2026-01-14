<?php

namespace App\DTOs;

class NewsUpdateData
{
    public function __construct(
        public readonly ?string $title = null,
        public readonly ?string $description = null,
    ) {
    }

    public static function fromArray(array $data): self
    {
        return new self(
            title: $data['title'] ?? null,
            description: $data['description'] ?? null,
        );
    }

    public function toArray(): array
    {
        return array_filter([
            'title' => $this->title,
            'description' => $this->description,
        ], fn($value) => $value !== null);
    }
}
