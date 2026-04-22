<div class="max-w-xl mx-auto p-4 bg-gray-800 rounded">
    <h1 class="text-2xl font-bold mb-4 text-gray-200">ISBN Lookup</h1>
    <form wire:submit.prevent="lookup">
        <input
            type="text"
            wire:model="isbn"
            placeholder="Enter ISBN"
            class="w-full border p-2 rounded"
        >
        <div class="mt-4 mb-4 flex justify-end">
        <button type="submit"
            class="bg-sky-400 text-sky-800 px-4 py-2 rounded float-right"
        >
            Search
        </button>
        </div>
    </form>

    <div wire:loading class="mt-4 text-orange-400">
        <i class="fas fa-spinner fa-spin"></i> Loading...
    </div>

    @if($error)
        <div class="mt-4 bg-red-300 text-red-700 px-2 py-2 rounded">
            {{ $error }}
        </div>
    @endif

    @error('isbn')
        <div class="mt-4 bg-red-300 text-red-700 px-2 py-2 rounded">
            {{ $message }}
        </div>
    @enderror

    @if($books)
        @foreach($books as $displayBook)
            <div class="mt-4 p-4 border rounded bg-gray-200">
                <h2 class="text-xl font-bold">{{ $displayBook['title'] ?? '' }}</h2>
                <p><strong>Publisher:</strong> {{ $displayBook['publisher'] ?? '-' }}</p>
                <p><strong>Published:</strong> {{ $displayBook['publishedDate'] ?? '-' }}</p>
                <p><strong>Authors:</strong> {{ $displayBook['authors'] ?? '-' }}</p>
                <p class="mt-2 text-sm">{{ $displayBook['description'] ?? '-' }}</p>
            </div>
        @endforeach
    @endif
</div>
