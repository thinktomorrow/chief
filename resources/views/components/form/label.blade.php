@props([
    'required',
    'translatable',
    'unset',
])

@if ($slot->isNotEmpty())
    <label
        {{ $attributes->merge(['data-slot' => 'label'])->class(['inline-flex items-start gap-1.5 select-none' => ! isset($unset)]) }}
    >
        <span data-slot="label-text" @class(['form-input-label' => ! isset($unset)])>
            {{ $slot }}
        </span>

        @if (isset($required) && $required)
            <x-chief::badge size="xs" variant="orange" class="my-0.5">Verplicht</x-chief::badge>
        @endif

        @if (isset($translatable) && $translatable)
            <span title="Dit veld is invulbaar per site" class="my-0.5">
                <x-chief::icon.locales class="text-grey-400 size-5" />
            </span>
        @endif
    </label>
@endif
