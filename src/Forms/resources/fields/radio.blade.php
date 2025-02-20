@php use Thinktomorrow\Chief\Forms\Livewire\LivewireFieldName; @endphp
<div class="space-y-1">

    @foreach($getOptions() as $option)
        @php
            $value = $option['value'];
            $label = $option['label'];
            $id = $getElementId($locale ?? null) . '_' . $value;
        @endphp

        <label for="{{ $id }}" class="flex items-start gap-2">
            <x-chief::input.radio
                wire:model="{{ LivewireFieldName::get($getName($locale ?? null)) }}"
                id="{{ $id }}"
                name="{{ $getName($locale ?? null) }}"
                value="{{ $value }}"
                :checked="in_array($value, (array) $getActiveValue($locale ?? null))"
            />

            <span class="body body-dark">{!! $label !!}</span>
        </label>
    @endforeach
</div>
