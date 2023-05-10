<textarea {{ $attributes->merge($getCustomAttributes())->merge([
        'cols' => '5',
        'rows' => '5',
        'style' => 'resize: vertical',
        'v-pre' => 'v-pre',
    ])->class([
        'w-full',
    ]) }}
    wire:model="{{ \Thinktomorrow\Chief\Forms\Livewire\LivewireAssist::formDataIdentifier($getName(),$locale ?? null) }}"
    name="{{ $getName($locale ?? null) }}"
    id="{{ $getElementId($locale ?? null) }}"
>{{ $getActiveValue($locale ?? null) }}</textarea>
