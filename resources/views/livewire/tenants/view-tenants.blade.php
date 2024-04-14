<div class="p-5 m-auto max-w-7xl">
    @section('title')
        All tenants
    @endsection
    <div class="flex items-center justify-between">
        <div><x-bladewind::input label="Full name" /></div>
       <div class="flex items-center gap-2">
        <a class="text-red-500" href={{ route('central-home') }}>HOME</a>
         <a wire:navigate href={{ route('central-manage-tenants') }}
            class="flex items-center gap-2 px-4 py-4 text-white bg-blue-500 rounded-md hover:bg-blue-700">
            <x-bladewind::icon name="plus" />
            <span> Add tenant</span>

        </a>
       </div>
    </div>


    <table class="min-w-full table-auto">
        <thead class="h-10 text-white bg-blue-900">
            <th>SubDomain</th>
            <th>DB Name</th>
            <th>Current Package</th>
            <th>Activated On</th>
            <th>Expires On</th>
            <th>Permissions</th>
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
                    <td class="text-center ">
                        {{ $tenant->tenant_name }}
                    </td>
                    <td class="text-center ">
                        {{ $tenant->package_name }}
                    </td>
                    <td class="text-center ">
                        {{ $tenant->date_activated }}
                    </td>
                    <td class="text-center ">
                        {{ $tenant->date_suspended }}
                    </td>
                    <td class="text-end">
                        <span wire:click="syncPermissions({{ $tenant->id }})" class="px-4 py-2 text-blue-500 rounded-md cursor-pointer hover:text-blue-700">
                            Sync Permissions
                        </span>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

</div>
