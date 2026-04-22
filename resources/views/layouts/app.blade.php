<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <title>ISBN Lookup</title>

        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @livewireStyles
    </head>

    <body class="bg-gray-300">
    <div class="min-h-screen flex items-center justify-center p-6">
        <div class="w-full max-w-xl">
            @yield('content')
        </div>
    </div>

    @livewireScripts
    </body>
</html>
