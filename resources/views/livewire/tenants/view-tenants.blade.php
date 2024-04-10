<div class="m-auto max-w-7xl p-5">
    @section('title')
        All tenants
    @endsection
    <div class="flex justify-between items-center">
        <div><x-bladewind::input label="Full name" /></div>
        <a wire:navigate href={{ route('central-manage-tenants') }}
            class="bg-blue-500 hover:bg-blue-700 px-4 py-4 flex gap-2 items-center rounded-md text-white">
            <x-bladewind::icon name="plus" />
            <span> Add tenant</span>

        </a>
    </div>


    <table class="table-auto min-w-full">
        <thead class="bg-blue-900 text-white h-10">
            <th>SubDomain</th>
            <th>DB Name</th>
            <th>Created On</th>
        </thead>
        <tbody>
            @foreach ($tenants as $tenant)
                <tr class="border-b">
                    <td class="text-center">
                        @php
                            $tenant_url = $tenant->id;
                            $central_url = env('CENTRAL_URL');
                            $central_url_prefix = env('CENTRAL_URL_PREFIX');
                            $tenant_url = $central_url_prefix . $tenant_url . '.' . $central_url;
                            // dd($tenant_url);
                        @endphp
                        <a href="{{ $tenant_url }}" target="_blank" class="text-blue-500"> {{ $tenant->id }}</a>
                    </td>
                    <td class=" text-center">
                        {{ $tenant->tenancy_db_name }}
                    </td>
                    <td class=" text-center">
                        {{ $tenant->created_at->diffForHumans() }}
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>


    {{-- <x-bladewind::table bg="yellow" striped="true" divider="thin" searchable="true"
        search_placeholder="search by ..." name="staff-table" button_label="add staff member"
        onclick="alert('add a staff')">

        <x-slot name="header">
            <th>SubDomain</th>
            <th>DB Name</th>
            <th>Created On</th>
        </x-slot>
        <div>
            @foreach ($tenants as $tenant)
                <tr>
                    <td class="text-right">
                        {{ $tenant->id }}
                    </td>
                    <td class=" text-right">
                        {{ $tenant->tenancy_db_name }}
                    </td>
                    <td class=" text-right">
                        {{ $tenant->created_at->diffForHumans() }}
                    </td>
                </tr>
            @endforeach

        </div>
    </x-bladewind::table> --}}
</div>
