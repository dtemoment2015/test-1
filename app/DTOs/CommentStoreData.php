<?php

namespace App\DTOs;

class CommentStoreData
{
    public function __construct(
        public readonly string $text,
        public readonly ?int $commentId = null,
    ) {
    }

    public static function fromArray(array $data): self
    {
        return new self(
            text: $data['text'],
            commentId: $data['comment_id'] ?? null,
        );
    }
}
