<div>
    <div class="container m-auto content card nk-block nk-block-lg">
        <div class="">
            <div class="pt-10 text-center">
                {{-- <hr /> --}}
                <h4 class="p-2 text-2xl font-bold text-center uppercase">Permissions Allocator</h4>
                <div class="flex flex-wrap items-center justify-between gap-2">
                    <div>
                        <p> Use the form below to allocate permissions to the roles. You have the following permissions
                            for
                            this role
                            <span class="font-bold underline uppercase text-danger ">
                                @if (strpos($roleData->name, '_') !== false)
                                    {{ substr(strstr($roleData->name, '_'), 1) }}
                                @else
                                    {{ $roleData->name }}
                                @endif
                            </span>
                        </p>
                    </div>
                    <a href="{{ route('welcome') }}" class="px-4 py-2 text-white bg-red-500 rounded">
                        Back to Roles</a>
                </div>
            </div>


        </div>
        <div class="p-3 card card-preview">
            <div class="card-inner">
                <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-3 ">
                    @foreach ($groups['groups'] as $group)
                        <div class="border rounded-md">
                            <div class="card card-preview">
                                <div class="p-2 card-inner">
                                    <div class="col-sm-12">
                                        <h4 class="font-bold">{{ $group['group_name'] }}</h4>
                                        <p class="text-sm italic">{{ $group['description'] }}</p>
                                        <hr />
                                    </div>
                                    <div class="p-2">
                                        @forelse($groups['permissions'] as $index => $permission)
                                            @switch($group['id'] === $permission['permissionGroupID'])
                                                @case($permission['permissionGroupID'])
                                                    <div class="form-group">
                                                        <div class="custom-control custom-checkbox" wire:click="togglePermission('{{ $permission['id'] }}')">
                                                            <input type="checkbox" class="bg-blue-500"
                                                                id="{{ $permission['id'] }}" name="{{ $permission['id'] }}"

                                                                value="{{ $permission['id'] }}" autocomplete="off"
                                                                @if (in_array($permission['id'], $permissions)) checked @endif>
                                                            <label class="custom-control-label" for="{{ $permission['id'] }}"
                                                                @if (in_array($permission['id'], $permissions)) style="color: green" @endif>
                                                                {{ $permission['id'] }} -
                                                                {{ $permission['permission_name'] }}
                                                                </i></label>
                                                        </div>
                                                    </div>
                                                @break
                                            @endswitch
                                            @empty
                                                <div class="text-center form-group">No permissions found for this group
                                                </div>
                                            @endforelse
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
            <div class="pb-20">
                <div class="flex justify-center gap-4 ">
                    <button class="px-4 py-2 text-white bg-blue-500 rounded" style="display: block"
                        wire:click.prevent="syncPermissions()">Update
                        Permissions for
                        {{ $roleData['name'] }}</button>
                    <a href="{{ route('welcome') }}" class="px-4 py-2 text-white bg-red-500 rounded">
                        Back to Roles</a>
                </div>
            </div>

        </div>
    </div>
