@php
    use Thinktomorrow\Chief\Forms\Fields\FieldName\LivewireFieldName;
@endphp

<div data-slot="control" class="space-y-2">
    @foreach ($getOptions() as $option)
        @php
            $value = $option['value'];
            $label = $option['label'];
            $id = $getElementId($locale ?? null) . '_' . $value;
        @endphp

        <label for="{{ $id }}" class="flex items-start gap-2">
            <x-chief::form.input.checkbox
                wire:model.change="{{ LivewireFieldName::get($getName($locale ?? null)) }}"
                id="{{ $id }}"
                name="{{ $getName($locale ?? null) . '[]' }}"
                value="{{ $value }}"
                :checked="in_array($value, (array) $getActiveValue($locale ?? null))"
                class="{{ $optedForToggleDisplay() ? 'appearance-none hidden' : null }}"
                :attributes="$attributes->merge($getCustomAttributes())"
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
