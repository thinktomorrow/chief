@extends('chief::back._layouts.master')

@section('page-title')
    Dashboard
@stop

@section('content')
    <div class="row gutter-l stack-xl">
        <div class="column-6">
            <h1>Welkom op je dashboard, {{ ucfirst(Auth::user()->firstname) }}</h1>
            <p class="text-lg font-medium text-grey-500">Don't try to follow trends. Create them.</p>
        </div>

        <div class="gutter column-6 right">
            @php
                app(Thinktomorrow\Chief\Admin\Widgets\RenderWidgets::class)->render(\Thinktomorrow\Chief\Admin\Widgets\Widgets::fromArray(config('chief.widgets', []))->get());
            @endphp
        </div>
    </div>
@stop

@section('chief-footer')
    @include('chief::back._layouts._partials.chief-footer')
@stop
