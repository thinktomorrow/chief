@props([
    'required',
    'unset',
])

@if ($slot->isNotEmpty())
    <label
        {{ $attributes->merge(['data-slot' => 'label'])->class(['inline-flex items-start gap-1 select-none' => ! isset($unset)]) }}
    >
        <span @class(['form-input-label' => ! isset($unset)])>
            {{ $slot }}
        </span>

        @if (isset($required) && $required)
            <x-chief::badge size="xs" variant="orange" class="my-0.5">Verplicht</x-chief::badge>
        @endif
    </label>
@endif
