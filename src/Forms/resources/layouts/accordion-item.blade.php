<div
    data-accordion-item="{{ $getId() }}"
    {{ $attributes->merge($getCustomAttributes())->class('p-6 relative group shadow rounded-lg') }}
>
    <div
        data-accordion-item-show-if-closed="{{ $getId() }}"
        class="absolute inset-0 border rounded-lg bg-grey-50 group-hover:bg-grey-100 border-grey-100"
    ></div>

    <div
        data-accordion-item-show-if-open="{{ $getId() }}"
        class="absolute inset-0 bg-white border rounded-lg border-grey-100"
    ></div>

    <div class="relative">
        <div class="flex items-start justify-between gap-4">
            <div class="space-y-1">
                <div class="flex gap-2">
                    <span class="display-dark display-base">
                        {{ $getTitle() ?? 'Click here to toggle' }}
                    </span>

                    <span data-accordion-item-show-if-closed="{{ $getId() }}" class="label label-primary label-xs">
                        {{ count($getComponents()) }} verborgen velden
                    </span>
                </div>

                <p class="body-base text-grey-500">{!! $getDescription() !!}</p>
            </div>

            <div data-accordion-item-toggle="{{ $getId() }}" class="cursor-pointer text-grey-500">
                <div data-accordion-item-show-if-closed="{{ $getId() }}" class="hover:child-svg-scale-110">
                    <svg width="20" height="20"> <use xlink:href="#icon-chevron-down"></use> </svg>
                </div>

                <div data-accordion-item-show-if-open="{{ $getId() }}" class="hover:child-svg-scale-110">
                    <svg width="20" height="20"> <use xlink:href="#icon-chevron-up"></use> </svg>
                </div>
            </div>
        </div>

        <div data-accordion-item-content="{{ $getId() }}" @class([
            'mt-6 space-y-6',
            'hidden' => !$isVisible(),
        ])>
            @foreach ($getComponents() as $childComponent)
                {{ $childComponent }}
            @endforeach
        </div>
    </div>
</div>
