@props([
    'required',
    'unset',
])

@if ($slot->isNotEmpty())
    <label
        {{ $attributes->merge(['data-slot' => 'label'])->class(['form-input-label select-none' => ! isset($unset)]) }}
    >
        {{ $slot }}

        @if (isset($required) && $required)
            <span class="text-secondary-400">*</span>
        @endif
    </label>
@endif
