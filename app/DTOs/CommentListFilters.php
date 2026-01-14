<?php

namespace App\DTOs;

class CommentListFilters
{
    public function __construct(
        public readonly ?string $cursor = null,
        public readonly int $limit = 5,
    ) {
    }

    public static function fromArray(array $data): self
    {
        return new self(
            cursor: $data['cursor'] ?? null,
            limit: min((int) ($data['limit'] ?? 5), 50), // По умолчанию 5, максимум 50
        );
    }
}
