@if ($hasLink())
    <a href="{{ $getLink() }}" title="{{ $getDescription() }}">
        @if ($isVisible())
            <x-chief-table-new::button color="primary" :icon-left="$getPrependIcon()" :icon-right="$getAppendIcon()">
                {{ $getLabel() }}
            </x-chief-table-new::button>
        @elseif ($isHidden())
            <x-chief::dialog.dropdown.item :icon-left="$getPrependIcon()" :icon-right="$getAppendIcon()">
                {{ $getLabel() }}
            </x-chief::dialog.dropdown.item>
        @endif
    </a>
@else
    <button wire:click="applyAction('{{ $getKey() }}')" title="{{ $getDescription() }}">
        @if ($isVisible())
            <x-chief-table-new::button color="primary" :icon-left="$getPrependIcon()" :icon-right="$getAppendIcon()">
                {{ $getLabel() }}
            </x-chief-table-new::button>
        @elseif ($isHidden())
            <x-chief::dialog.dropdown.item :icon-left="$getPrependIcon()" :icon-right="$getAppendIcon()">
                {{ $getLabel() }}
            </x-chief::dialog.dropdown.item>
        @endif
    </button>
@endif
