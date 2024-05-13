<div class="py-5 m-auto max-w-7xl">
    <div>
        <div>
            <h1 class="text-xl font-bold">Welcome to the Truecode multi-vendor multi-tenant architecture </h1>
            <p class="py-5">
                This will be the central welcome page, for now click links below:
            </p>
        </div>

        <div class="flex flex-wrap gap-8 py-5 text-blue-500">
            <a href="{{ route('central-view-tenants') }}"> view tenants</a>
            <a href="{{ route('central-manage-tenants') }}"> Manage tenants</a>
        </div>

        <button
            class="inline-block px-4 py-3 text-sm font-semibold text-center text-white uppercase transition duration-200 ease-in-out rounded-md cursor-pointer bg-primary hover:bg-gray-900">
            Button
        </button>

        <!-- Open the modal using ID.showModal() method -->
        <button class="btn" onclick="my_modal_1.showModal()">open modal</button>
        <dialog id="my_modal_1" class="modal">
            <div class="bg-white modal-box">
                <h3 class="text-lg font-bold">Hello!</h3>
                <p class="py-4">Press ESC key or click the button below to close</p>
                <div class="modal-action">
                    <form method="dialog">
                        <!-- if there is a button in form, it will close the modal -->
                        <button class="btn">Close</button>
                    </form>
                </div>
            </div>
        </dialog>


        <!-- Open the modal using ID.showModal() method -->
        <button class="btn" onclick="my_modal_2.showModal()">open modal</button>
        <dialog id="my_modal_2" class="modal">
            <div class="bg-white modal-box">
                <h3 class="text-lg font-bold">Hello!</h3>
                <p class="py-4">Press ESC key or click outside to close</p>
            </div>
            <form method="dialog" class="modal-backdrop">
                <button>close</button>
            </form>
        </dialog>



    </div>
</div>
