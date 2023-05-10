<div class="space-y-1">
    @foreach($getOptions() as $value => $label)
        <label for="{{ $getElementId($locale ?? null).'_'.$value }}" class="with-radio">
            <input
                wire:model="{{ \Thinktomorrow\Chief\Forms\Livewire\LivewireAssist::formDataIdentifier($getName(),$locale ?? null) }}"
                type="radio"
                name="{{ $getName($locale ?? null) }}"
                value="{{ $value }}"
                id="{{ $getElementId($locale ?? null).'_'.$value }}"
                {{ in_array($value, (array) $getActiveValue($locale ?? null)) ? 'checked="checked"' : '' }}
            >

            <span class="body-base body-dark">{!! $label !!}</span>
        </label>
    @endforeach
</div>
