<div data-slot="control" class="space-y-2">
    @foreach ($getOptions() as $option)
        @php
            $value = $option['value'];
            $label = $option['label'];
            $id = \Illuminate\Support\Str::random();
        @endphp

        <label wire:key="{{ $id }}" for="{{ $id }}" class="flex items-start gap-2">
            <x-chief::form.input.checkbox
                id="{{ $id }}"
                name="{{ $getName($locale ?? null) . '[]' }}"
                value="{{ $value }}"
                :checked="in_array($value, (array) $getActiveValue($locale ?? null))"
                :attributes="$attributes
                    ->merge($getCustomAttributes())
                    ->merge([$getWireModelType() => $getWireModelValue($locale ?? null)])"
            />

            @if ($label)
                <span @class(['body body-dark leading-5'])>
                    {!! $label !!}
                </span>
            @endif
        </label>
    @endforeach
</div>
