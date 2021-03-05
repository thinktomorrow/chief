
@extends('chief::back._layouts.master')

@section('page-title')
    @adminLabel('page_title')
@endsection



@component('chief::back._layouts._partials.header')
    @slot('title')
        @adminLabel('page_title')
    @endslot

    <h1>@adminLabel('page_title')</h1>



{{--    <div class="inline-group-s">--}}
{{--        @if($manager->can('create'))--}}
{{--            <a href="{{ $manager->route('create') }}" class="btn btn-secondary inline-flex items-center">--}}
{{--                <span class="mr-2"><svg width="18" height="18"><use xlink:href="#add"/></svg></span>--}}
{{--                <span>Voeg een {{ $manager->details()->singular }} toe</span>--}}
{{--            </a>--}}
{{--        @endif--}}
{{--    </div>--}}
@endcomponent

@section('content')

    @adminCan('edit')
        <a href="@adminRoute('edit', 1)">Bewerk deze pagina</a>
    @elseAdminCan
        djdjdjdjdjdjdjdkjfkd
    @endAdminCan

    @include('chief::back.managers._partials._rowitems', array_merge(['managers' => $managers], $manager->indexViewData()))

{{--    <div class="row gutter-l stack">--}}
{{--        <div class="column-9">--}}
{{--            <div class="row gutter-s">--}}

{{--                @if($managers->isEmpty() && !$manager->filters()->anyApplied())--}}

{{--                    @if($manager->can('create'))--}}
{{--                        <div class="stack">--}}
{{--                            <a href="{{ $manager->route('create') }}" class="btn btn-primary inline-flex items-center">--}}
{{--                                <span class="mr-2"><svg width="18" height="18"><use xlink:href="#zap"/></svg></span>--}}
{{--                                <span>Tijd om een {{ $manager->details()->singular }} toe te voegen</span>--}}
{{--                            </a>--}}
{{--                        </div>--}}
{{--                    @else--}}
{{--                        <p>Nog geen {{ $manager->details()->singular }} toegevoegd. Je hebt echter niet de nodige rechten om er een toe te voegen.</p>--}}
{{--                    @endif--}}

{{--                @else--}}

{{--                    @if($managers->isEmpty())--}}

{{--                        <div class="stack">--}}
{{--                            <p>Geen resultaten gevonden voor uw huidige filtering.--}}
{{--                                <br><a href="{{ $manager->route('index') }}">Bekijk alle resultaten</a>--}}
{{--                            </p>--}}
{{--                        </div>--}}

{{--                    @else--}}

{{--                        @include($manager->indexView(), array_merge([--}}
{{--                            'managers' => $managers,--}}
{{--                        ], $manager->indexViewData()))--}}

{{--                    @endif--}}

{{--                @endif--}}

{{--            </div>--}}
{{--            @if($managers instanceof Illuminate\Contracts\Pagination\Paginator)--}}
{{--                {{ $managers->links('chief::managers.pagination') }}--}}
{{--            @endif--}}

{{--        </div>--}}

{{--        <div class="column-3">--}}

{{--            @if( $manager::filters()->any() )--}}
{{--                <div class="mb-8">--}}
{{--                    <h3>Filtering</h3>--}}
{{--                    <form method="GET">--}}
{{--                        {!! $manager::filters()->render() !!}--}}
{{--                        <div class="stack-xs">--}}
{{--                            <button class="btn btn-primary squished-xs" type="submit">Filter</button>--}}
{{--                        </div>--}}
{{--                    </form>--}}
{{--                </div>--}}
{{--            @endif--}}

{{--            {!! $manager::sections()->sidebar !!}--}}

{{--            @if($manager->isAssistedBy('archive') && $archiveAssistant = $manager->assistant('archive'))--}}
{{--                @if( ! $archiveAssistant->findAllArchived()->isEmpty())--}}
{{--                    <div class="stack-s">--}}
{{--                        <a href="{{ $archiveAssistant->route('index') }}">Bekijk de gearchiveerde items</a>--}}
{{--                    </div>--}}
{{--                @endif--}}
{{--            @endif--}}

{{--        </div>--}}
{{--    </div>--}}

@stop
