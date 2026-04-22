# Documentation:
I have added a controller (even though it is not adding anything) to show understanding of the Laravel flow.

# Components:
Livewire Component (IsbnLookup) - handling user input, validation, and UI state
Service Layer (GoogleBooksService) - Handles all interaction with the Google Books API. Responsible for caching, data transformation, and error handling
Data Transfer Object - Provides a consistent structure for book data across the application. Decouples external API structure from internal usage

Dataflow: User Input -> Livewire -> Service ->API -> DTO -> Livewire → Blade

The external API responses are normalised into DTOs, these are passed to LiveWire and the Blade renders the final output.

# Caching
To prevent/minimise duplicate API calls and reduce load on the API:
- I cache successful responses for an hour
- Empty results are cached for 10 minutes

I am caching the items as arrays to avoid issues with Laravel serialisation of objects and keep the cache storage format stable. When reading from cache, the DTO’s are reconstructed.

# The code gracefully handles multiple failure scenarios:
Invalid ISBN format => Validation error shown to user
API failure 500 => User friendly message
Rate limiting failure 429 => Returns specific error, would normally not show the user this, but for this purpose it shows the rate limit was considered.
Network issues => Caught via the try catch and handled with a message.

Livewire makes sure the errors are displayed without breaking the UI.

# Security:
- API key and url are stored in config/services.php.
- No sensitive data is exposed to other front end.
- Input is validated so no unnecessary calls to the API are made (numeric + dashes, length check)
- Caching implemented to reduce the risk of exhausting the API (rate limit, costs)

# Testing
I have added tests for:
- Fetching and displaying data,
- Handling empty responses
- Input validation
- API failure
- Rate limiting

The calls are mocked using Laravel’s Http::fake() so no real API calls are made during testing and to simulate the different API behaviours.

# Extensibility
You mentioned extensibility, the service layer can support more APIs and the DTO can be expanded without affecting the UI.
The caching strategy can be adjusted centrally and the Livewire component can be reused or extended when needed.

# I think the side focuses on
- Efficient API usage,
- Robust error handling,
- Strong testing,
- Clearly separating between UI and Business Logic
- Clean and maintainable




# Technical assessment: ISBN Book Lookup

The objective of this task assessment is to create a small Laravel project that allows a user to input a book's ISBN number and retrieve book details via the Google Books API.

## Requirements

The following requirements should be met for this task.

### Functional Requirements

- **Search Interface:** A single-page interface built with [Laravel Livewire](https://livewire.laravel.com/). While it doesn’t have to pretty, it should demonstrate a clear understanding of the TALL stack

- **Data Retrieval:** Fetch data from the [Google Books API](https://developers.google.com/books/docs/overview)

- **Performance:** Ensure the project is optimised for performance, and the system doesn’t produce redundant API requests.

- **Loading states:** Provide visual feedback to the user during transition states (e.g. loading)

### Technical Requirements

- **Architecture:**

    - You should demonstrate a strong understanding of controllers, handling data input and abstraction.

    - You should use Laravel’s facades and helpers where possible. Please develop in an extensible way.

- **Testing:** Include a test suite (Pest or PHPUnit). We are looking for:

    - Feature tests of the Livewire component

    - Mocking of the Google Books API integration

- **Configuration:** Endpoints and API keys should be persisted in a configuration file.

- **AI:** For the purposes of this task, please refrain from using AI.

## What are we looking for?

As the project’s focus is primarily backend, some of the key metrics we’ll be measuring on are:

- **Code organisation:** How you decouple the UI from the business logic

- **Security:** Consideration for the security of the implementation, consider API exhaustion, etc.

- **Error handling:** How you gracefully handle errors

- **Data consistency:** The use of Data Transfer objects to pass data between components

- **Test quality:** Your ability to test external dependencies and cover unhappy path scenarios

## Setup

Please fork this repository and submit a pull request on completion with detailed documentation on your approach and rationale for the choices you made.
