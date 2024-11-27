@php
    use Thinktomorrow\Chief\Admin\Nav\BreadCrumb;$title = ucfirst($page->label());
    $breadcrumb = new BreadCrumb('Vaste teksten', route('squanto.index'));
    $collectedLines = collect($lines)->groupBy(function($lineViewModel) {
        return $lineViewModel->sectionKey();
    });
@endphp


<x-chief::page.template :title="$title">
    @push('custom-styles')
        @include('squanto::_preventDuplicateSubmissions')
    @endpush

    <x-slot name="hero">
        <x-chief::page.hero :title="$title" :breadcrumbs="[$breadcrumb]">
            <button form="updateSquantoForm" type="submit" class="btn btn-primary">
                Bewaar aanpassingen
            </button>
        </x-chief::page.hero>
    </x-slot>

    <form id="updateSquantoForm" method="POST" action="{{ route('squanto.update', $page->slug()) }}" role="form">
        {{ csrf_field() }}

        <input type="hidden" name="_method" value="PUT">

        <x-chief::page.grid>
            @foreach($collectedLines as $sectionKey => $groupedLines)
                <div class="space-y-4 card">
                    <p class="text-sm tracking-wider uppercase text-grey-500">
                        {{ ucfirst(str_replace('_', ' ', $sectionKey)) }}
                    </p>

                    @foreach($groupedLines as $lineViewModel)
                        @include('squanto::_field')
                    @endforeach
                </div>
            @endforeach
        </x-chief::page.grid>
    </form>

    @include('chief::templates.page._partials.editor-script')
</x-chief::page.template>
