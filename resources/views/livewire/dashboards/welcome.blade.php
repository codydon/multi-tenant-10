<div class="p-5 m-auto max-w-7xl">
    @section('title')
        Welcome
    @endsection
    <div class="flex justify-between">
        {{-- <div class="text-2xl font-bold uppercase">
            Welcome
        </div> --}}

        <a href="{{ route('tenant-settings-roles') }}"
            class="px-4 py-2 text-blue-500 rounded-md cursor-pointer hover:text-blue-700">
            Roles
        </a>
    </div>

    <div class="flex flex-col items-center max-w-2xl gap-4 m-auto">
        <h2 class="text-xl font-bold md:text-3xl ">
            ARISE-AAS Events Scheduler
        </h2>
        <p class="text-center ">
            Lorem ipsum dolor sit amet consectetur, adipisicing elit. Animi nihil quis voluptate sunt id exercitationem
            quidem ipsum doloremque deserunt!
        </p>

        <p>
            Click on the buttons below to view the exciting side-events for each day!
        </p>
    </div>

    <div class="flex flex-wrap items-center p-4 justify-evenly">
        <div> <input type="search" placeholder="Search by name"
                class="w-[400px] text-center text-sm font-light px-8 py-2 border rounded-full"></div>
        <div>
            <div class="px-8 py-2 text-sm font-light text-center border rounded-full">
                @php
                    $today = now();
                    $oneMonthAgo = $today->subMonth();
                @endphp
                <input type="date" value="{{ $oneMonthAgo->format('Y-m-d') }}"> -
                <input type="date" value="{{ $today->format('Y-m-d') }}">
            </div>
        </div>
    </div>


</div>
