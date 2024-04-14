<div class="p-5 m-auto max-w-7xl">
    @section('title')
        Managing tenants
    @endsection
    <div class="flex items-center justify-between border-b-2">
        <div class="font-bold uppercase">
            Managing tenants
        </div>
        <a wire:navigate href={{ route('central-view-tenants') }}
            class="flex items-center gap-2 px-4 py-4 text-white bg-red-500 rounded-md hover:bg-red-700">
            <x-bladewind::icon name="minus" />
            <span> Back to tenants</span>

        </a>
    </div>


    <div>
        <div class="pt-20 text-xl font-bold">
            Fill the form bellow appropriately to create a new tenant
        </div>
        <form class="grid grid-cols-1 gap-2 py-5 md:grid-cols-2 lg:grid-cols-3 md:gap-4"
            wire:submit.prevent="createTenant">

            <div>
                <x-bladewind::input class="text-black" label="Tenant URL Prefix" placeholder="e:g: truecode"
                    name="domain" show_placeholder_always="true" wire:model.live='domain' required="true" />
                @error('domain')
                    <span class="text-red-500">{{ $message }}</span>
                @enderror
            </div>
            <div>
                <x-bladewind::input label="Tenant name" placeholder="e:g: TrueCode" name="tenant_name"
                    wire:model.live='tenant_name' required="true" class="text-black" />
                @error('tenant_name')
                    <span class="text-red-500">{{ $message }}</span>
                @enderror
            </div>
            <div>
                <select wire:model="package_id" class="w-full p-2 text-black border border-gray-300 rounded-md"
                    required="true">
                    <option value="">Select a package</option>
                    @foreach ($packages as $package)
                        <option value="{{ $package->id }}">{{ $package->name }}</option>
                    @endforeach
                </select>
            </div>
            {{-- <x-bladewind::select name="package_id" data="{{ json_encode($packages) }}"  /> --}}

            <div class="col-span-3">
                <x-bladewind::button can_submit="true" class="">click me to submit</x-bladewind::button>
            </div>

        </form>
    </div>
</div>
