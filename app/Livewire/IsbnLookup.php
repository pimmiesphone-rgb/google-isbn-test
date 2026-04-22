<?php

namespace App\Livewire;

use App\Services\GoogleBooksService;
use Illuminate\Validation\ValidationException;
use Livewire\Component;

class IsbnLookup extends Component
{
    public $isbn = '';
    public $books = null;
    public $error = null;
    public $loading = false;

    protected $rules = [
        'isbn' => ['required', 'regex:/^[0-9-]{10,17}$/']
    ];

    public function lookup(GoogleBooksService $service)
    {
        //reset
        $this->reset(['books', 'error']);
        $this->loading = true;

        //validate the input
        $this->validate();

        //make sure it is either 10 or 13 characters
        if (!in_array(strlen(str_replace('-', '', $this->isbn)), [10, 13])) {
            $this->error = 'This is not a valid ISBN number. Please try again.';
            $this->loading = false;
            return;
        }

        //start getting the book details
        try {
            $this->books = null;
            $result = $service->getBookByIsbn($this->isbn);
            if ($result === null) {
                $this->error = 'Something went wrong. Please try again later.';
            } elseif ($result->isEmpty()) {
                $this->error = 'No book(s) found for this ISBN.';
            } else {
                $this->books = collect($result)
                    ->map(fn($books) => $books->toArray())
                    ->toArray();
            }
        } catch (\Throwable $e) {
            //error handling for any throws from the service. Highlighted the ratelimit as it happens a lot.
            if($e->getCode() === 429){
                $this->error = 'Rate limit exceeded. Please try again later.';
            } else {
                $this->error = 'Something went wrong. Please try again later.';
            }
        }

        $this->loading = false;
    }

    public function render()
    {
        return view('livewire.isbn-lookup');
    }

}
