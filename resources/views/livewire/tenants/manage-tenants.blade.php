<div class="m-auto max-w-7xl p-5">
    @section('title')
        Managing tenants
    @endsection
    <div class="flex justify-between items-center border-b-2">
        <div class="uppercase font-bold">
            Managing tenants
        </div>
        <a wire:navigate href={{ route('central-view-tenants') }}
            class="bg-red-500 hover:bg-red-700 px-4 py-4 flex gap-2 items-center rounded-md text-white">
            <x-bladewind::icon name="minus" />
            <span> Back to tenants</span>

        </a>
    </div>


    <div>
        <div class="pt-20 font-bold text-xl">
            Fill the form bellow appropriately to create a new tenant
        </div>
        <form class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-2 md:gap-4 py-5" wire:submit.prevent="createTenant">

            <div>
                <x-bladewind::input class="text-black" label="Tenant URL Prefix" placeholder="e:g: truecode" name="domain"
                    show_placeholder_always="true" wire:model.live='domain' required="true" />
                @error('domain')
                    <span class="text-red-500">{{ $message }}</span>
                @enderror
            </div>
            <div>
                <x-bladewind::input label="Tenant name" placeholder="e:g: TrueCode" name="tenant_name"
                    wire:model.live='tenant_name' required="true"  class="text-black" />
                @error('tenant_name')
                    <span class="text-red-500">{{ $message }}</span>
                @enderror
            </div>

            <div class="col-span-3">
                <x-bladewind::button can_submit="true" class="">click me to submit</x-bladewind::button>
            </div>

        </form>
    </div>
</div>
