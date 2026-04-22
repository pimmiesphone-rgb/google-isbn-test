<?php

use Livewire\Livewire;
use Illuminate\Support\Facades\Http;
test('It fetches and displays the book data from the API', function () {
    //Not using the API to test, so set up a book that will be used to return data, check it returns data as expected
    Http::fake([
        'googleapis.com/*' => Http::response([
            'items' => [
                [
                    'volumeInfo' => [
                        'title' => 'My Laravel Live',
                        'authors' => ['Pim Papendrecht'],
                        'description' => 'A book about all things Laravel.',
                        'publishedDate' => '2026',
                    ]
                ]
            ]
        ], 200)
    ]);

    Livewire::test(\App\Livewire\IsbnLookup::class)
        ->set('isbn', '1212121212121')
        ->call('lookup')
        ->assertSee('My Laravel Live')
        ->assertSee('Pim Papendrecht');
});

test('It shows a message when there are no books found', function () {
    //make sure the code handles the return of no books for a valid number
    Http::fake([
        'googleapis.com/*' => Http::response([
            'items' => []
        ], 200)
    ]);

    Livewire::test(\App\Livewire\IsbnLookup::class)
        ->set('isbn', '0000000000')
        ->call('lookup')
        ->assertSee('No book(s) found');
});

test('It validates the isbn input - only numbers and dashes', function () {
    //Tests the the validation.
    Livewire::test(\App\Livewire\IsbnLookup::class)
        ->set('isbn', 'invalid$$$')
        ->call('lookup')
        ->assertHasErrors(['isbn']);
});

test('It handles an API failure gracefully', function () {
    //Check whether the code handles the API failure properly.
    Http::fake([
        'googleapis.com/*' => Http::response([], 500)
    ]);

    Livewire::test(\App\Livewire\IsbnLookup::class)
        ->set('isbn', '1212121212122')
        ->call('lookup')
        ->assertSee('Something went wrong');
});

test('It handles the API rate limiting', function () {
    //check it handles the API rate limiting response
    Http::fake([
        'googleapis.com/*' => Http::response([], 429)
    ]);

    Livewire::test(\App\Livewire\IsbnLookup::class)
        ->set('isbn', '1212121212121')
        ->call('lookup')
        ->assertSee('Rate limit exceeded.');
});
