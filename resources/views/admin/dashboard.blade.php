<x-chief::template>
    <div class="container">
        <div class="row-start-start gutter-3">
            <div class="w-full">
                <div class="max-w-2xl space-y-4">
                    <h1 class="h1 display-dark">
                        Welkom op je dashboard, {{ ucfirst(Auth::user()->firstname) }}
                    </h1>

                    <p class="font-medium text-grey-500">
                        Don't try to follow trends. Create them.
                    </p>
                </div>
            </div>

            <div class="w-full lg:w-1/2">
                @php
                    $widgets = app(Thinktomorrow\Chief\Admin\Widgets\RenderWidgets::class)
                        ->render(\Thinktomorrow\Chief\Admin\Widgets\Widgets::fromArray(config('chief.widgets', []))
                        ->get())
                @endphp

                {!! $widgets !!}
            </div>
        </div>
    </div>
</x-chief-template>

{{-- @extends('chief::layout.master')

@section('page-title')
    Dashboard
@stop

@section('content')
    <div class="container">
        <div class="row-start-start gutter-3">
            <div class="w-full">
                <div class="max-w-2xl space-y-4">
                    <h1 class="h1 display-dark">
                        Welkom op je dashboard, {{ ucfirst(Auth::user()->firstname) }}
                    </h1>

                    <p class="font-medium text-grey-500">
                        Don't try to follow trends. Create them.
                    </p>
                </div>
            </div>

            <div class="w-full lg:w-1/2">
                @php
                    $widgets = app(Thinktomorrow\Chief\Admin\Widgets\RenderWidgets::class)
                        ->render(\Thinktomorrow\Chief\Admin\Widgets\Widgets::fromArray(config('chief.widgets', []))
                        ->get())
                @endphp

                {!! $widgets !!}
            </div>
        </div>
    </div>
@stop --}}
