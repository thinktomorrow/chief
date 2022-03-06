@php
    $collapsible = $attributes->has('collapsible');
    $open = $attributes->has('open');
    $dropdownIdentifier = uniqid();
@endphp

<div class="relative">
    <div data-toggle-dropdown="{{ $dropdownIdentifier }}" class="rounded-lg cursor-pointer hover:bg-primary-50">
        <div class="flex justify-between">
            <div class="flex space-x-1">
                @isset($icon)
                    @isset($url)
                        <a
                            href="{{ $url }}"
                            title="{!! $label !!}"
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
                        data-toggle-classes="{{ $collapsible ? 'hidden' : null }}"
                        class="px-3 py-2 link link-black {{ $isCollapsedOnPageLoad ? 'hidden' : null }}"
                    >
                        {!! $label !!}
                    </a>
                @else
                    <span
                        data-toggle-classes="{{ $collapsible ? 'hidden' : null }}"
                        class="px-3 py-2 link link-black {{ $isCollapsedOnPageLoad ? 'hidden' : null }}"
                    >
                        {!! $label !!}
                    </span>
                @endisset
            </div>

            @if(!$slot->isEmpty())
                <div
                    data-toggle-classes="{{ $collapsible ? 'hidden' : null }}"
                    class="flex-shrink-0 p-2 {{ $isCollapsedOnPageLoad ? 'hidden' : null }}"
                >
                    <span class="inline-flex items-center justify-center w-6 h-6">
                        <svg width="18" height="18" class="text-grey-700"><use xlink:href="#icon-chevron-down"></use></svg>
                    </span>
                </div>
            @endif
        </div>
    </div>

    @if(!$slot->isEmpty())
        @php
            $dropdownToggleClasses = 'absolute left-6 top-0 bg-white rounded-lg shadow-window p-2 min-w-48';
        @endphp

        <div
            data-dropdown="{{ $dropdownIdentifier }}"
            data-toggle-classes="{{ $dropdownToggleClasses }}"
            class="ml-11 {{ $open ? '' : 'hidden' }} {{ $isCollapsedOnPageLoad ? $dropdownToggleClasses : null }}"
        >
            {!! $slot !!}
        </div>
    @endif
</div>
