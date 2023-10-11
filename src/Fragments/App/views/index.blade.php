<x-chief-form::window
    data-fragments-window {{-- selector for async fetching via chief tabs --}}
:refresh-url="$manager->route('fragments-show', $owner)"
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
            data-sortable-endpoint="@adminRoute('fragments-reorder', $owner)"
            data-sortable-is-sorting
            class="divide-y divide-grey-100"
        >

            @php
                $currentLocale = app()->getLocale();
                app()->setLocale($locale);
            @endphp

            @foreach($fragments as $fragment)
                @include('chief-fragments::index-item', [
                    'fragment' => $fragment,
                    'owner' => $owner,
                    'ownerManager' => $manager,
                    'loop' => $loop,
                ])
            @endforeach

            @php
                app()->setLocale($currentLocale);
            @endphp
        </div>
    </div>
</x-chief-form::window>
