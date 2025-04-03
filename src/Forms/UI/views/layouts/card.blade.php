<div
    data-slot="form-group"
    {{ $attributes->merge($getCustomAttributes())->class(['rounded-lg p-6', $getLayoutVariant()->cardClass()]) }}
>
    <div x-data="{ open: {{ $isCollapsed() ? 'false' : 'true' }} }" class="space-y-6">
        <div class="flex items-start justify-between">
            @include('chief-form::layouts._partials.header')

            @if ($isCollapsible())
                <button
                    type="button"
                    class="-mt-0.5 ml-auto rounded-lg p-1 text-grey-500 hover:bg-black/5 hover:text-black"
                    x-on:click="open = !open"
                >
                    <svg x-show="open" class="size-4">
                        <use xlink:href="#icon-chevron-down"></use>
                    </svg>
                    <svg x-show="!open" class="size-4">
                        <use xlink:href="#icon-chevron-left"></use>
                    </svg>
                </button>
            @endif
        </div>

        @if (count($getComponents()) > 0)
            <div x-show="open" x-transition class="space-y-6">
                @foreach ($getComponents() as $_component)
                    {{ $_component }}
                @endforeach
            </div>
        @endif
    </div>
</div>
