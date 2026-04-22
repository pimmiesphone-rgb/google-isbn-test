<?php

namespace App\Data;

class BookData
{
    public function __construct(
        public ?string $title,
        public ?string $authors,
        public ?string $description,
        public ?string $publishedDate,
        public ?string $publisher,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            title: $data['title'] ?? null,
            authors: $data['authors'] ?? null,
            description: $data['description'] ?? null,
            publishedDate: $data['publishedDate'] ?? null,
            publisher: $data['publisher'] ?? null,
        );
    }

    public function toArray(): array
    {
        return [
            'title' => $this->title,
            'authors' => $this->authors,
            'description' => $this->description,
            'publishedDate' => $this->publishedDate,
            'publisher' => $this->publisher,
        ];
    }
}
