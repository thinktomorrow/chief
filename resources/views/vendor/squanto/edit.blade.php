<x-squanto::app-layout>
    @section('header')
        <div class="sticky top-0 z-10 py-6 -my-6 bg-grey-100">
            <div class="container">
                @component('chief::layout._partials.header')
                    @slot('title')
                        {{ $page->label() }}
                    @endslot

                    @slot('breadcrumbs')
                        <a href="{{ route('squanto.index') }}" class="link link-primary">
                            <x-chief-icon-label type="back">Vaste teksten</x-chief-icon-label>
                        </a>
                    @endslot

                    <div class="space-x-4">
                        <button form="updateSquantoForm" type="submit" class="btn btn-primary">
                            Bewaar aanpassingen
                        </button>
                    </div>
                @endcomponent
            </div>
        </div>
    @stop

    <form id="updateSquantoForm" method="POST" action="{{ route('squanto.update', $page->slug()) }}" role="form">
        {{ csrf_field() }}

        <input type="hidden" name="_method" value="PUT">

        @php
            $collectedLines = collect($lines)->groupBy(function($lineViewModel) {
                return $lineViewModel->sectionKey();
            });
        @endphp

        <div class="container space-y-6">
            @foreach($collectedLines as $sectionKey => $groupedLines)
                <div class="space-y-6 card">
                    <p class="text-sm tracking-wider uppercase text-grey-500">
                        {{ ucfirst(str_replace('_', ' ', $sectionKey)) }}
                    </p>

                    @foreach($groupedLines as $lineViewModel)
                        @include('squanto::_field')
                    @endforeach
                </div>
            @endforeach
        </div>
    </form>
</x-squanto::app-layout>
