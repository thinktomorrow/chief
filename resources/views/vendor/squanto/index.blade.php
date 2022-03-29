@section('header')
    <div class="container-sm">
        @component('chief::layout._partials.header')
            @slot('title')
                Vertalingen
            @endslot

            @slot('breadcrumbs')
                <a href="{{ route('chief.back.dashboard') }}" class="link link-primary">
                    <x-chief-icon-label type="back">Dashboard</x-chief-icon-label>
                </a>
            @endslot
        @endcomponent
    </div>
@stop

<x-squanto::app-layout>
    <div class="container-sm">
        <div class="divide-y card divide-grey-100">
            @foreach($pages as $page)
                @php
                    $completionPercentage = $page->completionPercentage();
                @endphp

                <div @class([
                    'flex items-center justify-between space-x-4',
                    'pt-4' => !$loop->first,
                    'pb-4' => !$loop->last,
                ])>
                    <span class="space-x-2">
                        <span class="text-lg display-dark display-base">
                            {{ ucfirst($page->label()) }}
                        </span>

                        <span class="label label-grey label-xs">
                            {{ $completionPercentage }}%
                        </span>
                    </span>

                    <a href="{{ route('squanto.edit',$page->slug()) }}" class="flex-shrink-0 link link-primary">
                        <x-chief-icon-button type="edit"></x-chief-icon-button>
                    </a>
                </div>
            @endforeach
        </div>
    </div>
</x-squanto::app-layout>
