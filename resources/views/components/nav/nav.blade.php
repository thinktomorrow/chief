@props([
    'label' => null,
    'title' => null,
    'items' => collect(),
    'inline',
    'append',
    'prepend',
])

@if (! isset($inline) && $items->count() > 0)
    @php
        $icon = ($firstItem = $items->first()) && $firstItem->icon() ? $firstItem->icon() : '';

        $isActive = false;

        foreach ($items as $item) {
            if (isActiveUrl($item->url()) || isActiveUrl($item->url() . '/*')) {
                $isActive = true;
                $showOpenDropdown = true;
            }
        }
    @endphp

    @if ($title)
        <div data-toggle-classes="hidden" class="{{ $isCollapsedOnPageLoad ? 'hidden' : '' }} mb-2 mt-6 px-2">
            <span class="body text-xs text-grey-500">
                {{ ucfirst($title) }}
            </span>
        </div>
    @endif

    <x-chief::nav.item label="{{ $label }}" icon="{!! $icon !!}" collapsible {{ $attributes }}>
        @if (! isset($append))
            {{ $slot }}
        @endif

        @foreach ($items as $item)
            <x-chief::nav.item label="{{ ucfirst($item->label()) }}" url="{{ $item->url() }}" />
        @endforeach

        @if (isset($append))
            {{ $slot }}
        @endif
    </x-chief::nav.item>
@elseif ($items->count() > 0)
    @if ($title)
        <div data-toggle-classes="hidden" class="{{ $isCollapsedOnPageLoad ? 'hidden' : '' }} mb-2 mt-6 px-2">
            <span class="body text-xs text-grey-500">
                {{ ucfirst($title) }}
            </span>
        </div>
    @endif

    @if (! isset($append))
        {{ $slot }}
    @endif

    @foreach ($items as $item)
        <x-chief::nav.item
            label="{{ ucfirst($item->label()) }}"
            url="{{ $item->url() }}"
            icon="{!! $item->icon() !!}"
            collapsible
            {{ $attributes }}
        />
    @endforeach

    @if (isset($append))
        {{ $slot }}
    @endif
@endif
