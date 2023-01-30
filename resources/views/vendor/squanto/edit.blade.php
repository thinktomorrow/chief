@php
    $title = ucfirst($page->label());
    $breadcrumb = new \Thinktomorrow\Chief\Admin\Nav\Breadcrumb('Vaste teksten', route('squanto.index'));
    $collectedLines = collect($lines)->groupBy(function($lineViewModel) {
        return $lineViewModel->sectionKey();
    });
@endphp


<x-chief::page.template :title="$title">
    @push('custom-styles')
        <link rel="stylesheet" href="{{ asset('assets/back/css/vendor/redactor.css') }}">
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

    @push('custom-scripts-after-vue')
        <script src="{{ asset('/assets/back/js/vendor/redactor.js') }}"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                if(document.querySelectorAll('.redactor-editor').length > 0) {
                    $R('.redactor-editor', {
                        buttons: ['html', 'format', 'bold', 'italic', 'sup', 'sub', 'strikethrough', 'lists', 'link']
                    });
                }
            });
            console.log('teeeeeest');
        </script>
    @endpush
</x-chief::page.template>
