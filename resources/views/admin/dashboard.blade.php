@php
    $widgets = app(Thinktomorrow\Chief\Admin\Widgets\RenderWidgets::class)
        ->render(\Thinktomorrow\Chief\Admin\Widgets\Widgets::fromArray(config('chief.widgets', []))
        ->get())
@endphp

<x-chief::template title="Dashboard">
    <x-slot name="hero">
        <x-chief::template.hero :title="'Welkom op je dashboard, ' . ucfirst(Auth::user()->firstname)"/>
    </x-slot>

    <div class="container">
        <div class="row-start-start gutter-3">
            <div class="w-full">
                <p class="font-medium text-grey-500">
                    Don't try to follow trends. Create them.
                </p>
            </div>

            @if ($widgets)
                {{-- TODO: widgets should be an array/collection instead of a string. That way it's possible to render them in a grid. --}}
                <div class="w-full">
                    {!! $widgets !!}
                </div>
            @endif
        </div>
    </div>
</x-chief-template>
