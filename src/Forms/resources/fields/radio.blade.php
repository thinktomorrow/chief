<div class="space-y-1">
    @foreach($getOptions() as $value => $label)
        @php
            $id = $getElementId($locale ?? null) . '_' . $value;
        @endphp

        <label for="{{ $id }}" class="flex items-start gap-2">
            <x-chief::input.radio
                wire:model.lazy="{{ \Thinktomorrow\Chief\Forms\Livewire\LivewireAssist::formDataIdentifier($getName(),$locale ?? null) }}"
                id="{{ $id }}"
                name="{{ $getName($locale ?? null) }}"
                value="{{ $value }}"
                :checked="in_array($value, (array) $getActiveValue($locale ?? null))"
            />

            <span class="body body-dark">{!! $label !!}</span>
        </label>
    @endforeach
</div>
