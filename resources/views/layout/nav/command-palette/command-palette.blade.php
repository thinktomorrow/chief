<div id="command-palette" class="fixed inset-0 flex items-start justify-center hidden bg-black bg-opacity-25">
    <div class="mt-32 overflow-hidden bg-white shadow-lg min-w-xl rounded-xl">
        <div class="overflow-y-auto max-h-128">
            <input
                type="text"
                name="search"
                id="search"
                class="px-6 py-5 custom"
                placeholder="Type something ..."
                autofocus
                autocomplete="off"
            >

            <div id="result" class="border-t empty:hidden border-grey-100"></div>

            {{-- Results which are always available --}}
            {{-- TODO: these pages should also be shown based on the used search term, so should be in controller response --}}
            <div class="p-3 space-y-3">
                <div>
                    <div class="px-3 py-2">
                        <span class="text-sm text-grey-500">Overzichtspagina's</span>
                    </div>

                    <div>
                        @foreach (\Thinktomorrow\Chief\App\Http\Controllers\Back\CommandPalette\CommandPaletteController::getAllPageModelIndices() as $index)
                            <a
                                href="{{ $index['url'] }}"
                                title="{{ $index['label'] }}"
                                class="flex items-center justify-between px-3 py-2 rounded-lg focus:bg-primary-50 group"
                            >
                                <span class="link link-grey"> {{ $index['label'] }} </span>
                                <span class="hidden text-sm font-medium text-primary-500 group-focus:inline-block"> Ga naar overzichtspagina </span>
                            </a>
                        @endforeach
                    </div>
                </div>

                <div>
                    <div class="px-3 py-2">
                        <span class="text-sm text-grey-500">Chief</span>
                    </div>

                    <div>
                        @can('view-user')
                            <a
                                href="{{ route('chief.back.users.index') }}"
                                title="Admins"
                                class="flex items-center justify-between px-3 py-2 rounded-lg focus:bg-primary-50 group"
                            >
                                <span class="link link-grey"> Admins </span>
                                <span class="hidden text-sm font-medium text-primary-500 group-focus:inline-block"> Ga naar pagina </span>
                            </a>
                        @endcan
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
