{{-- TODO: Keep top level nav item open if a child nav item links to the current page --}}
{{-- TODO: Active state --}}
@php
    // Only nav items with this attribute are collapsible. This tag is meant for top level nav items.
    $collapsible = $attributes->has('collapsible');
    // Nav items with this attribute will be open on page load.
    $open = $attributes->has('open');
    $blank = $attributes->has('blank');
    $dropdownIdentifier = uniqid();
@endphp

<div class="relative">
    <div data-toggle-dropdown="{{ $dropdownIdentifier }}" class="rounded-lg cursor-pointer hover:bg-grey-50">
        <div class="flex justify-between">
            <div class="flex grow">
                @isset($icon)
                    @isset($url)
                        <a
                            href="{{ $url }}"
                            title="{!! $label !!}"
                            data-toggle-dropdown-ignore
                            class="p-2 shrink-0 [&>*]:w-6 [&>*]:h-6 [&>*]:text-grey-800"
                            {!! $blank ? 'target="_blank" rel="noopener"' : null !!}
                        >
                            {!! $icon !!}
                        </a>
                    @else
                        <div data-expand-navigation class="p-2 shrink-0 [&>*]:w-6 [&>*]:h-6 [&>*]:text-grey-800">
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
                        class="px-3 py-2 inline-block font-medium text-grey-800 w-full lg:w-40 {{ $isCollapsedOnPageLoad && $collapsible ? 'hidden' : null }}"
                        {!! $blank ? 'target="_blank" rel="noopener"' : null !!}
                    >
                        {!! $label !!}
                    </a>
                @else
                    <span
                        data-toggle-classes="{{ $collapsible ? 'hidden' : null }}"
                        class="px-3 py-2 inline-block font-medium text-grey-800 w-full lg:w-40 {{ $isCollapsedOnPageLoad && $collapsible ? 'hidden' : null }}"
                    >
                        {!! $label !!}
                    </span>
                @endisset
            </div>

            @if(!$slot->isEmpty())
                <div
                    data-toggle-classes="{{ $collapsible ? 'hidden' : null }}"
                    class="shrink-0 p-2 {{ $isCollapsedOnPageLoad && $collapsible ? 'hidden' : null }}"
                >
                    <span class="inline-flex items-center justify-center w-6 h-6">
                        <span class="p-1 rounded-lg hover:bg-grey-200 text-grey-800">
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
            class="ml-11 {{ $open && !$isCollapsedOnPageLoad ?: 'hidden' }}"
        >
            {!! $slot !!}
        </div>
    @endif
</div>
