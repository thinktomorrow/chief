@props([
    'label' => null,
    'title' => null,
    'items' => collect(),
    'inline',
    'append',
    'prepend',
])

@php
    foreach($items as $item) {
        $item->detectActive();
    }
@endphp

@if (! isset($inline) && $items->count() > 0)
    @php
        $icon = ($firstItem = $items->first()) && $firstItem->icon() ? $firstItem->icon() : '';
        $isGroupActive = false;

        foreach($items as $item) {
            if($item->isActive()) {
                $isGroupActive = true;
            }
        }
    @endphp

    @if ($title)
        <div class="mb-2 mt-6 px-2">
            <span class="body text-xs text-grey-500">
                {{ ucfirst($title) }}
            </span>
        </div>
    @endif

    <x-chief::nav.item label="{{ $label }}" icon="{!! $icon !!}"
                       {{ $attributes->merge(['open' => $isGroupActive]) }}
                       url="{{ $items->first()?->url() }}">
        @if (! isset($append))
            {{ $slot }}
        @endif

        @foreach ($items as $item)
            <x-chief::nav.item label="{{ ucfirst($item->label()) }}" url="{{ $item->url() }}"
                               :is-active="$item->isActive()" />
        @endforeach

        @if (isset($append))
            {{ $slot }}
        @endif
    </x-chief::nav.item>
@elseif ($items->count() > 0)
    @if ($title)
        <div class="mb-2 mt-6 px-2">
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
            :is-active="$item->isActive()"
            {{ $attributes }}
        />
    @endforeach

    @if (isset($append))
        {{ $slot }}
    @endif
@endif
