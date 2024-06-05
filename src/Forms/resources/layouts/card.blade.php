<div {{ $attributes->merge($getCustomAttributes())->class(['p-6 rounded-lg', $getLayoutType()->cardClass()]) }}>
    <div x-data="{ open: {{ $isCollapsed() ? 'false' : 'true' }} }" class="space-y-6">
        <div class="flex items-start justify-between">
            @include('chief-form::layouts._partials.header')

            @if ($isCollapsible())
                <button
                    type="button"
                    class="p-1 ml-auto -mt-0.5 rounded-lg hover:bg-black/5 text-grey-500 hover:text-black"
                    x-on:click="open = !open">
                    <svg x-show="open" class="size-4">
                        <use xlink:href="#icon-chevron-down"></use>
                    </svg>
                    <svg x-show="!open" class="size-4">
                        <use xlink:href="#icon-chevron-left"></use>
                    </svg>
                </button>
            @endif
        </div>

        @if (count($components = $getComponents()) > 0)
            <div x-show="open" x-transition class="space-y-6">
                @foreach ($components as $childComponent)
                    {{ $childComponent }}
                @endforeach
            </div>
        @endif
    </div>
</div>
