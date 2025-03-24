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
                ['label' => 'Dashboard', 'url' => route('chief.back.dashboard'), 'icon' => 'home'],
                ['label' => 'Vaste teksten', 'url' => route('squanto.index')],
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

    <form id="updateSquantoForm" method="POST" action="{{ route('squanto.update', $page->slug()) }}" role="form">
        {{ csrf_field() }}

        <input type="hidden" name="_method" value="PUT" />

        <div class="space-y-6">
            @foreach ($collectedLines as $sectionKey => $groupedLines)
                <x-chief::window class="card">
                    <div class="space-y-4">
                        <p class="text-sm uppercase tracking-wider text-grey-500">
                            {{ ucfirst(str_replace('_', ' ', $sectionKey)) }}
                        </p>

                        @foreach ($groupedLines as $lineViewModel)
                            @include('squanto::_field')
                        @endforeach
                    </div>
                </x-chief::window>
            @endforeach
        </div>
    </form>

    @include('chief::templates.page._partials.editor-script')
</x-chief::page.template>
