<div class="space-y-1">
    @foreach ($getOptions() as $value => $label)
        @php
            $id = $getElementId($locale ?? null) . '_' . $value;
        @endphp

        <label for="{{ $id }}" class="flex items-start gap-2">
            <x-chief::input.checkbox
                id="{{ $id }}"
                name="{{ $getName($locale ?? null) . '[]' }}"
                value="{{ $value }}"
                :checked="in_array($value, (array) $getActiveValue($locale ?? null))"
                class="{{ $optedForToggleDisplay() ? 'appearance-none hidden' : null }}"
            />

            @if ($optedForToggleDisplay())
                <span class="form-input-toggle"></span>
            @endif

            @if ($label)
                <span @class(['body body-dark', 'mt-0.5' => $optedForToggleDisplay()])>
                    {!! $label !!}
                </span>
            @endif
        </label>
    @endforeach
</div>
