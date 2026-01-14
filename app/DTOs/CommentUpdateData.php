<?php

namespace App\DTOs;

class CommentUpdateData
{
    public function __construct(
        public readonly ?string $text = null,
    ) {
    }

    public static function fromArray(array $data): self
    {
        return new self(
            text: $data['text'] ?? null,
        );
    }

    public function toArray(): array
    {
        return array_filter([
            'text' => $this->text,
        ], fn($value) => $value !== null);
    }
}
