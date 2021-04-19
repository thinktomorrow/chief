@extends('chief::back._layouts.master')

@section('page-title')
    Dashboard
@stop

@section('content')
    <div class="container my">
        <div class="row gutter-6">
            <div class="w-full lg:w-1/2 space-y-6 prose prose-dark">
                <h1 class="text-5xl">Welkom op je dashboard, {{ ucfirst(Auth::user()->firstname) }}</h1>

                <p class="text-lg font-medium">
                    <span class="text-grey-500">Don't try to follow trends. Create them.</span>
                </p>
            </div>

            <div class="w-full lg:w-1/2">
                {!! app(Thinktomorrow\Chief\Admin\Widgets\RenderWidgets::class)
                        ->render(\Thinktomorrow\Chief\Admin\Widgets\Widgets::fromArray(config('chief.widgets', []))
                        ->get())  !!}
            </div>
        </div>
    </div>
@stop
