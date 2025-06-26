@php
    use Thinktomorrow\Chief\Forms\Fields\FieldName\LivewireFieldName;
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
                wire:model="{{ LivewireFieldName::get($getName($locale ?? null)) }}"
                id="{{ $id }}"
                name="{{ $getName($locale ?? null) }}"
                value="{{ $value }}"
                :checked="in_array($value, (array) $getActiveValue($locale ?? null))"
                :attributes="$attributes->merge($getCustomAttributes())"
            />

            <span class="body body-dark leading-5">{!! $label !!}</span>
        </label>
    @endforeach
</div>
