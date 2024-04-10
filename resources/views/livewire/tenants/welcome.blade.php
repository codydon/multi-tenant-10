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
    </div>
</div>
