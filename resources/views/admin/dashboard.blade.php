@php
    $widgets = app(Thinktomorrow\Chief\Admin\Widgets\RenderWidgets::class)->render(
        \Thinktomorrow\Chief\Admin\Widgets\Widgets::fromArray(config('chief.widgets', []))->get(),
    );
@endphp

<x-chief::page.template title="Dashboard">
    <x-slot name="header">
        <x-chief::page.header :title="'Welkom op je dashboard, ' . ucfirst(Auth::user()->firstname)" />
    </x-slot>

    <div class="row-start-start gutter-3">
        <div class="w-full">
            <p class="text-lg text-grey-500">Don't try to follow trends. Create them.</p>
        </div>

        @if ($widgets)
            {!! $widgets !!}
        @endif
    </div>
</x-chief::page.template>
