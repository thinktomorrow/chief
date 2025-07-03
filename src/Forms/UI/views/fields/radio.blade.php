@php
    $modelBindingType = $getWireModelType() == 'defer' ? 'wire:model' : 'wire:model.' . $getWireModelType();
    $modelBinding = [$modelBindingType => Thinktomorrow\Chief\Forms\Fields\FieldName\LivewireFieldName::get($getName($locale ?? null))];
@endphp

<div data-slot="control" class="space-y-2">
    @foreach ($getOptions() as $option)
        @php
            $value = $option['value'];
            $label = $option['label'];
            $id = \Illuminate\Support\Str::random();
        @endphp

        <label for="{{ $id }}" class="flex items-start gap-2">
            <x-chief::form.input.radio
                id="{{ $id }}"
                name="{{ $getName($locale ?? null) }}"
                value="{{ $value }}"
                :checked="in_array($value, (array) $getActiveValue($locale ?? null))"
                :attributes="$attributes
                    ->merge($getCustomAttributes())
                    ->merge($modelBinding)"
            />

            <span class="body body-dark leading-5">{!! $label !!}</span>
        </label>
    @endforeach
</div>
