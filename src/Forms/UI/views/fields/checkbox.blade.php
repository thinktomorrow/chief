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
                class="{{ $optedForToggleDisplay() ? 'appearance-none hidden' : null }}"
                :attributes="$attributes
                    ->merge($getCustomAttributes())
                    ->merge([$getWireModelType() => $getWireModelValue($locale ?? null)])"
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
