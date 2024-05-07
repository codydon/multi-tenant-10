<div
    class="flex flex-wrap items-center justify-between px-3 py-2 m-auto mt-5 bg-gray-100 border border-gray-100 rounded-full max-w-7xl">
    <a href="/home">
        <img src="/blue.png" alt="logo" class="h-12">
    </a>
    <div class="flex flex-wrap items-center gap-2 md:gap-4">
        @foreach ($links as $link)
            <a href="{{ $link['route'] }}" class="px-2 py-2 font-semibold rounded-md cursor-pointer hover:text-blue-500">
                {{ $link['name'] }}
            </a>
        @endforeach
        <a href="{{ 'welcome' }}"
            class="px-5 py-3 text-white bg-blue-500 rounded-full cursor-pointer hover:bg-blue-600">
            Sign in
        </a>
    </div>
</div>
