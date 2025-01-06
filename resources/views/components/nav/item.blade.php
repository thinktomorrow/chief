@php
    // Only nav items with this attribute are collapsible. This tag is meant for top level nav items.
    $collapsible = $attributes->has('collapsible');
    // Nav items with this attribute will be open on page load.
    $open = $attributes->has('open');
    $blank = $attributes->has('blank');
    $dropdownIdentifier = uniqid();
@endphp

<div class="group relative">
    <div data-toggle-dropdown="{{ $dropdownIdentifier }}" class="cursor-pointer rounded-md hover:bg-grey-100">
        <div class="flex justify-between gap-3 px-2">
            <div class="flex grow gap-2">
                @isset($icon)
                    @isset($url)
                        <a
                            href="{{ $url }}"
                            title="{!! $label !!}"
                            data-toggle-dropdown-ignore
                            class="shrink-0 py-1.5 [&>*]:h-6 [&>*]:w-6 [&>*]:text-grey-500 group-hover:[&>*]:text-grey-900"
                            {!! $blank ? 'target="_blank" rel="noopener"' : null !!}
                        >
                            {!! $icon !!}
                        </a>
                    @else
                        <div
                            data-expand-navigation
                            class="shrink-0 py-1.5 [&>*]:h-6 [&>*]:w-6 [&>*]:text-grey-500 group-hover:[&>*]:text-grey-900"
                        >
                            {!! $icon !!}
                        </div>
                    @endisset
                @endisset

                @isset($url)
                    <a
                        href="{{ $url }}"
                        title="{!! $label !!}"
                        data-toggle-dropdown-ignore
                        data-toggle-classes="{{ $collapsible ? 'hidden' : null }}"
                        class="{{ $isCollapsedOnPageLoad && $collapsible ? 'hidden' : null }} inline-block w-full py-1.5 text-sm font-medium leading-6 text-grey-700 group-hover:text-grey-900 lg:w-36"
                        {!! $blank ? 'target="_blank" rel="noopener"' : null !!}
                    >
                        {!! $label !!}
                    </a>
                @else
                    <span
                        data-toggle-classes="{{ $collapsible ? 'hidden' : null }}"
                        class="{{ $isCollapsedOnPageLoad && $collapsible ? 'hidden' : null }} inline-block w-full py-1.5 text-sm font-medium leading-6 text-grey-700 group-hover:text-grey-900 lg:w-36"
                    >
                        {!! $label !!}
                    </span>
                @endisset
            </div>

            @if (! $slot->isEmpty())
                <div
                    data-toggle-classes="{{ $collapsible ? 'hidden' : null }}"
                    class="{{ $isCollapsedOnPageLoad && $collapsible ? 'hidden' : null }} mt-2.5 shrink-0"
                >
                    <div class="flex items-center justify-center">
                        <span class="text-grey-700 hover:scale-105">
                            <svg class="h-4 w-4"><use xlink:href="#icon-chevron-down"></use></svg>
                        </span>
                    </div>
                </div>
            @endif
        </div>
    </div>

    @if (! $slot->isEmpty())
        <div
            data-dropdown="{{ $dropdownIdentifier }}"
            class="{{ $open && ! $isCollapsedOnPageLoad ?: 'hidden' }} ml-8"
        >
            {!! $slot !!}
        </div>
    @endif
</div>
