<x-chief-form::window
    data-fragments-window {{-- selector for async fetching via chief tabs --}}
    refresh-url="{{ route('chief::fragments.refresh-index', $context->id) }}"
    tags="fragments"
    class="card"
>
    <div class="relative -my-6">
        @include('chief-fragments::components.fragment-select', [
            'inOpenState' => count($fragments) < 1
        ])

        <div
            data-fragments-container
            data-sortable
            data-sortable-endpoint="{{ route('chief::fragments.reorder', $context->id) }}"
            data-sortable-is-sorting
            class="divide-y divide-grey-100"
        >

            @php
                $currentLocale = app()->getLocale();

                if($context->getLocales() > 0) {
                    app()->setLocale($context->getLocales()[0]);
                }
            @endphp

            @foreach($fragments as $fragment)
                @include('chief-fragments::index-item', [
                    'fragment' => $fragment,
                    'owner' => $owner,
                    'loop' => $loop,
                ])
            @endforeach

            @php
                app()->setLocale($currentLocale);
            @endphp
        </div>
    </div>
</x-chief-form::window>
