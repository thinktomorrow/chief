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
        <div class="flex justify-between gap-3 px-2">
            <div class="flex gap-3 grow">
                @isset($icon)
                    @isset($url)
                        <a
                            href="{{ $url }}"
                            title="{!! $label !!}"
                            data-toggle-dropdown-ignore
                            class="py-1.5 shrink-0 [&>*]:w-6 [&>*]:h-6 [&>*]:text-grey-500 group-hover:[&>*]:text-grey-900"
                            {!! $blank ? 'target="_blank" rel="noopener"' : null !!}
                        >
                            {!! $icon !!}
                        </a>
                    @else
                        <div data-expand-navigation class="py-1.5 shrink-0 [&>*]:w-6 [&>*]:h-6 [&>*]:text-grey-500 group-hover:[&>*]:text-grey-900">
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
                        class="py-1.5 inline-block leading-6 font-medium text-sm text-grey-700 group-hover:text-grey-900 w-full lg:w-48 {{ $isCollapsedOnPageLoad && $collapsible ? 'hidden' : null }}"
                        {!! $blank ? 'target="_blank" rel="noopener"' : null !!}
                    >
                        {!! $label !!}
                    </a>
                @else
                    <span
                        data-toggle-classes="{{ $collapsible ? 'hidden' : null }}"
                        class="py-1.5 inline-block leading-6 font-medium text-sm text-grey-700 group-hover:text-grey-900 w-full lg:w-48 {{ $isCollapsedOnPageLoad && $collapsible ? 'hidden' : null }}"
                    >
                        {!! $label !!}
                    </span>
                @endisset
            </div>

            @if(!$slot->isEmpty())
                <div
                    data-toggle-classes="{{ $collapsible ? 'hidden' : null }}"
                    class="shrink-0 mt-2.5 {{ $isCollapsedOnPageLoad && $collapsible ? 'hidden' : null }}"
                >
                    <div class="flex items-center justify-center">
                        <span class="text-grey-700 hover:scale-105">
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
