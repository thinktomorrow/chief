@props([
    'tabId',
    'tabLabel' => null,
])

<div
    x-show="activeTab == '{{ $tabId }}'"
    data-tab-id="{{ $tabId }}"
    data-tab-label="{{ $tabLabel ? json_encode(is_string($tabLabel) ? $tabLabel : $tabLabel->toHtml()) : json_encode(strtoupper($tabId)) }}"
>
    {{ $slot }}
</div>
