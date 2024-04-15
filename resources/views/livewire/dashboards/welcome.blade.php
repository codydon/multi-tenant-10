<div class="p-5 m-auto max-w-7xl">
    @section('title')
        Roles
    @endsection
    <div class="flex items-center justify-center">
        <div class="text-2xl font-bold uppercase">
            Roles
        </div>
    </div>


    <table class="min-w-full table-auto">
        <thead class="h-10 text-white bg-blue-900">
            <th>ID</th>
            <th>slug</th>
            <th>Name</th>
            <th>Created_at</th>
            <th>Created_at</th>
        </thead>
        <tbody>
            @foreach ($roles as $role)
                <tr class="border-b">
                    <td class="text-center ">
                        {{ $role->id }}
                    </td>
                    <td class="text-center ">
                        {{ $role->name }}
                    </td>
                    <td class="text-center ">
                        {{ $role->role_name }}
                    </td>
                    <td class="text-center ">
                        {{ $role->created_at }}
                    </td>
                    <td class="flex justify-center gap-2">
                        <a href="{{ route('administrative-permissions-allocator', ['role' => $role->id]) }}"
                            class="px-4 py-2 text-blue-500 rounded-md cursor-pointer hover:text-blue-700">
                            Manage permissions
                        </a>

                    </td>

                </tr>
            @endforeach
        </tbody>
    </table>

</div>
