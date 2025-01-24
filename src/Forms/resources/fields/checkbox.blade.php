@php
    use Thinktomorrow\Chief\Forms\Fields\FieldName\LivewireFieldName;
@endphp

<div class="space-y-1">
    @foreach ($getOptions() as $option)
        @php
            $value = $option['value'];
            $label = $option['label'];
            $id = $getElementId($locale ?? null) . '_' . $value;
        @endphp

        <label for="{{ $id }}" class="flex items-start gap-2">
            <x-chief::input.checkbox
                    wire:model="{{ LivewireFieldName::get($getName($locale ?? null)) }}"
                    id="{{ $id }}"
                    name="{{ $getName($locale ?? null) . '[]' }}"
                    value="{{ $value }}"
                    :checked="in_array($value, (array) $getActiveValue($locale ?? null))"
                    class="{{ $optedForToggleDisplay() ? 'appearance-none hidden' : null }}"
            />

            @if ($optedForToggleDisplay())
                <span class="form-input-toggle shrink-0"></span>
            @endif

            @if ($label)
                <span @class(['body body-dark leading-5', 'mt-1' => $optedForToggleDisplay()])>
                    {!! $label !!}
                </span>
            @endif
        </label>
    @endforeach
</div>
