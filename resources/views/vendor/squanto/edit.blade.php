@php
    $title = ucfirst($page->label());
    $collectedLines = collect($lines)->groupBy(function ($lineViewModel) {
        return $lineViewModel->sectionKey();
    });
@endphp

<x-chief::page.template :title="$title">
    @push('custom-styles')
        @include('squanto::_preventDuplicateSubmissions')
    @endpush

    <x-slot name="header">
        <x-chief::page.header
            :breadcrumbs="[
                ['label' => 'Vaste teksten', 'url' => route('squanto.index'), 'icon' => 'book-edit'],
                $title
            ]"
        >
            <x-slot name="actions">
                <x-chief::button form="updateSquantoForm" type="submit" variant="blue">
                    Bewaar aanpassingen
                </x-chief::button>
            </x-slot>
        </x-chief::page.header>
    </x-slot>

    <div class="relative">
        <form id="updateSquantoForm" method="POST" action="{{ route('squanto.update', $page->slug()) }}" role="form">
            {{ csrf_field() }}

            <input type="hidden" name="_method" value="PUT" />

            @foreach ($collectedLines as $sectionKey => $groupedLines)
                <x-chief::window :title="ucfirst(str_replace('_', ' ', $sectionKey))">
                    @foreach ($groupedLines as $lineViewModel)
                        <div @class(['mt-4 border-t border-grey-100 pt-4' => ! $loop->first])>
                            @include('squanto::_field')
                        </div>
                    @endforeach
                </x-chief::window>
            @endforeach

            <div data-slot="window" class="pointer-events-none sticky bottom-4 flex justify-center">
                <x-chief::button
                    form="updateSquantoForm"
                    type="submit"
                    variant="blue"
                    class="pointer-events-auto rounded-full px-4 shadow-lg"
                >
                    Bewaar aanpassingen
                </x-chief::button>
            </div>
        </form>
    </div>

    @include('chief::templates.page._partials.editor-script')
</x-chief::page.template>
