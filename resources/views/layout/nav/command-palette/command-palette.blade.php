<div
    id="command-palette"
    class="fixed inset-0 flex items-start justify-center hidden"
>
    {{-- Background --}}
    <div class="absolute inset-0 bg-black bg-opacity-25 animate-fade-in"></div>

    <div class="relative mt-32 overflow-hidden bg-white shadow-lg min-w-xl rounded-xl pop" style="animation-duration: 100ms;">
        <div class="overflow-y-auto max-h-128">
            <input
                type="text"
                name="search"
                id="search"
                class="px-6 py-5 text-black custom"
                placeholder="Zoek naar pagina's, navigaties ..."
                autofocus
                autocomplete="off"
            >

            <div id="result" class="border-t empty:hidden border-grey-100"></div>
        </div>
    </div>
</div>
