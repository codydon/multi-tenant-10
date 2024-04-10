<div class="m-auto max-w-7xl py-5">
    <div>
        <div>
            <h1>Welcome to the Tenants Area</h1>
            <p>
                This is a simple example of a Livewire component.
            </p>
        </div>
        {{ $name }}
        <div>

            <input class="border" placeholder="name" type="text" wire:model.live="name">
            @error('name')
                <span class="text-red-500">{{ $message }}</span>
            @enderror
        </div>

        <div class="flex gap-8 flex-wrap py-5 text-blue-500">
            <a href="{{route('central-view-tenants')}}"> view tenants</a>
            <a href="{{route('central-manage-tenants')}}"> Manage tenants</a>
        </div>



    </div>
</div>
