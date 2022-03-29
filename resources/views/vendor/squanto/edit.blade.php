<x-squanto::app-layout>
    @section('header')
        <div class="container 2xl:container-1/2">
            @component('chief::layout._partials.header')
                @slot('title')
                    {{ $page->slug() }}
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
                <div class="card">
                    <div class="row-start-start gutter-3">
                        <div class="w-full lg:w-1/4">
                            <span class="text-sm tracking-wider uppercase display-base body-dark">
                                {{ ucfirst(str_replace('_', ' ', $sectionKey)) }}
                            </span>
                        </div>

                        <div class="w-full lg:w-3/4">
                            <div class="space-y-6">
                                @foreach($groupedLines as $lineViewModel)
                                    @include('squanto::_field')
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </form>
</x-squanto::app-layout>
