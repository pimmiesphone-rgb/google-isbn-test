<?php

namespace App\Services;

use App\Data\BookData;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class GoogleBooksService
{
    public function getBookByIsbn(string $isbn): ?Collection
    {
        //using a cache here to limit the calls to the API (in case using it is expensive).
        $isbn = $this->normalizeIsbn($isbn);
        $key = "book_isbn_{$isbn}";

        //return the cache if we have it for this key
        if (Cache::has($key)) {
            return collect(Cache::get($key))
                ->map(fn($item) => BookData::fromArray($item));
        }

        //get the response from the API
        //only retry on a server error, don't waste on rate limits etc.
        $response = Http::timeout(5)
            ->retry(2, 200, function ($exception, $request) {
                return $exception instanceof RequestException
                    && $exception->response->serverError();
            })
            ->get(config('services.google_books.url'), [
            'q' => "isbn:{$isbn}",
            'key' => config('services.google_books.key'),
        ]);

        //handle the errors - although they are thrown to the Livewire
        if ($response->tooManyRequests() || !$response->successful()) {
            return null;
        }

        //take the response and handle it
        $data = $response->json();

        // short cache for "nothing found"
        if (empty($data['items'])) {
            Cache::put($key, [], 600);
            return collect();
        }

        //use the DTO to structure the return
        $returnBooks = collect($data['items'])
            ->map(fn ($books) => $this->mapToDto($books));

        //cache the output and return it to livewire.
        Cache::put($key, $returnBooks->map->toArray(), 3600);
        return $returnBooks;
    }

    private function mapToDto(array $book): BookData
    {
        //structure the return using DTO - always the same, reliable output.
        return new BookData(
            title: $book['volumeInfo']['title'] ?? null,
            authors: isset($book['volumeInfo']['authors'])
                ? implode(', ', $book['volumeInfo']['authors'])
                : null,
            description: $book['volumeInfo']['description'] ?? null,
            publishedDate: $book['volumeInfo']['publishedDate'] ?? null,
            publisher: $book['volumeInfo']['publisher'] ?? null
        );
    }

    private function normalizeIsbn(string $isbn): string
    {
        //normalise the isbn, leaving only numbers
        return preg_replace('/[^0-9X]/', '', $isbn);
    }

}
