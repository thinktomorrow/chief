<div data-form data-form-tags="fragments" class="space-y-6">
    <p class="text-lg display-base display-dark">Voeg een nieuw fragment toe</p>

    <div class="row-start-stretch gutter-1.5">
        @forelse($fragments as $allowedFragment)
            <div class="w-full sm:w-1/2">
                <a
                    data-sidebar-trigger
                    href="{{ $allowedFragment['manager']->route('fragment-create', $owner) . (isset($order) ? '?order=' . $order : '') }}"
                    title="{{ ucfirst($allowedFragment['resource']->getLabel()) }}"
                    class="block p-3 space-y-3 transition-all duration-75 ease-in-out border rounded-lg border-grey-100 hover:shadow-card hover:border-primary-500"
                >
                    <div class="flex items-center gap-3">
                        <div class="flex items-center justify-center w-10 h-10 rounded-lg shrink-0 bg-grey-50 text-grey-500">
                            {{-- Todo: show fragment icon here --}}
                            @if($loop->index % 4)
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"> <path stroke-linecap="round" stroke-linejoin="round" d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zM16 13a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z" /> </svg>
                            @elseif($loop->index % 3)
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-type"><polyline points="4 7 4 4 20 4 20 7"/><line x1="9" y1="20" x2="15" y2="20"/><line x1="12" y1="4" x2="12" y2="20"/></svg>
                            @elseif($loop->index % 2)
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"> <path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" /> </svg>
                            @else
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"> <path stroke-linecap="round" stroke-linejoin="round" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13" /> </svg>
                            @endif
                        </div>

                        <p class="leading-tight display-base display-dark">
                            {{ ucfirst($allowedFragment['resource']->getLabel()) }}
                        </p>
                    </div>

                    {{-- Todo: show fragment description here --}}
                    <p class="text-sm body-base body-dark">
                        Lorem, ipsum dolor sit amet consectetur adipisicing elit. Illum incidunt optio ipsum deserunt! Nisi, adipisci corporis.
                    </p>
                </a>
            </div>
        @empty
            <p class="body-base body-dark">Geen fragmenten gevonden.</p>
        @endforelse
    </div>
</div>
