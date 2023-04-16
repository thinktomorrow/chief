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

<div class="relative group">
    <div data-toggle-dropdown="{{ $dropdownIdentifier }}" class="rounded-md cursor-pointer hover:bg-grey-100">
        <div class="flex justify-between gap-3 px-3">
            <div class="flex gap-3 grow">
                @isset($icon)
                    @isset($url)
                        <a
                            href="{{ $url }}"
                            title="{!! $label !!}"
                            data-toggle-dropdown-ignore
                            class="py-2 shrink-0 [&>*]:w-6 [&>*]:h-6 [&>*]:body-dark group-hover:[&>*]:text-grey-950"
                            {!! $blank ? 'target="_blank" rel="noopener"' : null !!}
                        >
                            {!! $icon !!}
                        </a>
                    @else
                        <div data-expand-navigation class="py-2 shrink-0 [&>*]:w-6 [&>*]:h-6 [&>*]:body-dark">
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
                        class="py-2 inline-block font-medium body-dark group-hover:text-grey-950 w-full lg:w-40 {{ $isCollapsedOnPageLoad && $collapsible ? 'hidden' : null }}"
                        {!! $blank ? 'target="_blank" rel="noopener"' : null !!}
                    >
                        {!! $label !!}
                    </a>
                @else
                    <span
                        data-toggle-classes="{{ $collapsible ? 'hidden' : null }}"
                        class="py-2 inline-block font-medium body-dark group-hover:text-grey-950 w-full lg:w-40 {{ $isCollapsedOnPageLoad && $collapsible ? 'hidden' : null }}"
                    >
                        {!! $label !!}
                    </span>
                @endisset
            </div>

            @if(!$slot->isEmpty())
                <div
                    data-toggle-classes="{{ $collapsible ? 'hidden' : null }}"
                    class="shrink-0 mt-3 {{ $isCollapsedOnPageLoad && $collapsible ? 'hidden' : null }}"
                >
                    <div class="flex items-center justify-center">
                        <span class="body-dark hover:scale-105">
                            <svg class="w-4 h-4"><use xlink:href="#icon-chevron-down"></use></svg>
                        </span>
                    </div>
                </div>
            @endif
        </div>
    </div>

    @if(!$slot->isEmpty())
        <div
            data-dropdown="{{ $dropdownIdentifier }}"
            class="ml-9 {{ $open && !$isCollapsedOnPageLoad ?: 'hidden' }}"
        >
            {!! $slot !!}
        </div>
    @endif
</div>
