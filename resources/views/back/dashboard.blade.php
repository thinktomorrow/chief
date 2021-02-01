@extends('chief::back._layouts.master')

@section('page-title')
    Dashboard
@stop

@section('topbar-right')

@stop

@section('content')
    <div class="row gutter-l stack-l">
        <div class="column-4 stack-xl">
            <span class="font-bold text-5xl text-grey-500 leading-none mb-4 block">Welkom op je dashboard, {{ Auth::user()->firstname }}</span class="font-bold text-5xl leading-none mb-4 block">
            <p>Don't try to follow trends. Create them.</p>
        </div>
        <div class="gutter column-8 right">
            <?= app(Thinktomorrow\Chief\Admin\Widgets\RenderWidgets::class)->render(\Thinktomorrow\Chief\Admin\Widgets\Widgets::fromArray(config('chief.widgets', []))->get()); ?>
        </div>
    </div>
@stop

@section('chief-footer')
    @include('chief::back._layouts._partials.chief-footer')
@stop
