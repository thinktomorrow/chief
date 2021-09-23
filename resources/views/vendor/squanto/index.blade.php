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
        <div class="row">
            <div class="w-full">
                <div class="window window-white">
                    <div class="-m-8 divide-y divide-grey-100">
                        @foreach($pages as $page)
                            @php
                                $completionPercentage = $page->completionPercentage();
                            @endphp

                            <div class="px-8 py-4">
                                <div class="flex items-center justify-between space-x-4">
                                    <span class="space-x-2">
                                        <span class="text-lg font-semibold text-grey-900">
                                            {{ ucfirst($page->label()) }}
                                        </span>

                                        <span class="text-sm label label-info">
                                            {{ $completionPercentage }}%
                                        </span>
                                    </span>

                                    <a href="{{ route('squanto.edit',$page->slug()) }}" class="flex-shrink-0 link link-primary">
                                        <x-chief-icon-label type="edit"></x-chief-icon-label>
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-squanto::app-layout>
