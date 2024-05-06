<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>
        @hasSection('title')
            @yield('title') - {{ config('app.name') }}
        @else
            {{ config('app.name') }}
        @endif
    </title>
    <link href="{{ asset('vendor/bladewind/css/animate.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('vendor/bladewind/css/bladewind-ui.min.css') }}" rel="stylesheet" />
    <script src="{{ asset('vendor/bladewind/js/helpers.js') }}"></script>
    @vite('resources/css/app.css')
</head>

<body>
    <div>
        @if (session()->has('error'))
            <div class="px-4 py-2 text-white bg-red-500 rounded">
                {{ session('error') }}
            </div>
            @if (session()->has('success'))
               <div class="px-4 py-2 text-white bg-green-500 rounded">
                    {{ session('success') }}
                </div>
            @endif

        @endif

    </div>
    <div>
        <livewire:navbar />
    </div>
    {{ $slot }}
</body>

</html>
