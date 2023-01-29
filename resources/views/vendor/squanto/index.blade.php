@section('header')
    <div class="container max-w-3xl">
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
    <div class="container max-w-3xl">
        <div class="divide-y card divide-grey-100">
            @foreach($pages as $page)
                @php
                    $completionPercentage = $page->completionPercentage();
                @endphp

                <div @class([
                    'flex items-center justify-between gap-4',
                    'pt-3' => !$loop->first,
                    'pb-3' => !$loop->last,
                ])>
                    <span class="space-x-1">
                        <a
                            href="{{ route('squanto.edit',$page->slug()) }}"
                            title="{{ ucfirst($page->label()) }}"
                            class="text-black h6"
                        >
                            {{ ucfirst($page->label()) }}
                        </a>

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
