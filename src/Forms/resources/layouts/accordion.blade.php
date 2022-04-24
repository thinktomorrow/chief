<div
    data-accordion
    {{ $attributes->merge($getCustomAttributes())->class('bg-grey-50 rounded-lg p-6 border border-grey-100') }}
>
    <div data-accordion-toggle="{{ $getId() }}" class="flex items-center justify-between">
        @include('chief-form::layouts._partials.header')

        <div class="cursor-pointer body-dark">
            <svg width="18" height="18"><use xlink:href="#icon-chevron-down"></use></svg>
        </div>
    </div>

    <div data-accordion-content="{{ $getId() }}" class="hidden mt-6 space-y-6">
        @foreach($getComponents() as $childComponent)
            {{ $childComponent }}
        @endforeach
    </div>
</div>
