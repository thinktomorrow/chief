@php
    // Only nav items with this attribute are collapsible. This tag is meant for top level nav items.
    $collapsible = $attributes->has('collapsible');
    // Nav items with this attribute will be open on page load.
    $open = $attributes->has('open');
    $dropdownIdentifier = uniqid();
@endphp

<div class="relative">
    <div data-toggle-dropdown="{{ $dropdownIdentifier }}" class="rounded-lg cursor-pointer hover:bg-grey-50">
        <div class="flex justify-between">
            <div class="flex flex-grow space-x-1">
                @isset($icon)
                    @isset($url)
                        <a
                            href="{{ $url }}"
                            title="{!! $label !!}"
                            data-toggle-dropdown-ignore
                            class="flex-shrink-0 p-2 children:w-6 children:h-6 children:text-grey-700"
                        >
                            {!! $icon !!}
                        </a>
                    @else
                        <div class="flex-shrink-0 p-2 children:w-6 children:h-6 children:text-grey-700">
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
                        class="px-3 py-2 inline-block font-semibold text-black w-full lg:w-40 {{ $isCollapsedOnPageLoad && $collapsible ? 'hidden' : null }}"
                    >
                        {!! $label !!}
                    </a>
                @else
                    <span
                        data-toggle-classes="{{ $collapsible ? 'hidden' : null }}"
                        class="px-3 py-2 inline-block font-semibold text-black w-full lg:w-40 {{ $isCollapsedOnPageLoad && $collapsible ? 'hidden' : null }}"
                    >
                        {!! $label !!}
                    </span>
                @endisset
            </div>

            @if(!$slot->isEmpty())
                <div
                    data-toggle-classes="{{ $collapsible ? 'hidden' : null }}"
                    class="flex-shrink-0 p-2 {{ $isCollapsedOnPageLoad && $collapsible ? 'hidden' : null }}"
                >
                    <span class="inline-flex items-center justify-center w-6 h-6">
                        <span class="p-1 rounded-lg hover:bg-grey-200 text-grey-700">
                            <svg width="16" height="16"><use xlink:href="#icon-chevron-down"></use></svg>
                        </span>
                    </span>
                </div>
            @endif
        </div>
    </div>

    @if(!$slot->isEmpty())
        <div
            data-dropdown="{{ $dropdownIdentifier }}"
            data-toggle-classes="{{ 'collapsed-dropdown' }}"
            class="ml-11 {{ $isCollapsedOnPageLoad ? 'collapsed-dropdown' : null }} {{ $open && !$isCollapsedOnPageLoad ?: 'hidden' }}"
        >
            {!! $slot !!}
        </div>
    @endif
</div>
