<div
    x-show="activeTab == '{{ $tabId }}'"
    data-tab-id="{{ $tabId }}"
    data-tab-label="{!! $tabLabel ?? strtoupper($tabId) !!}"
>
    {{ $slot }}
</div>
