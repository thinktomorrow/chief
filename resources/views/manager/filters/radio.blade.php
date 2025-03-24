@php
    $label = $label ?? null;
    $description = $description ?? null;
@endphp

<x-chief::form.input.group :rule="$id" inner-class="space-y-2">
    @if ($label)
        <x-chief::form.label unset class="h6 body-dark font-medium">{{ $label }}</x-chief::form.label>
    @endif

    @if ($description)
        <x-chief::form.description>{{ $description }}</x-chief::form.description>
    @endif

    <div class="space-y-2">
        @foreach ($options as $option => $optionLabel)
            <div class="flex items-start gap-2">
                <x-chief::form.input.radio
                    id="{{ $id . '-' . $option }}"
                    name="{{ $name }}"
                    value="{{ $option }}"
                    :checked="($option == ($value ?: $default))"
                />

                <x-chief::form.label for="{{ $id . '-' . $option }}" unset class="body body-dark leading-5">
                    {{ $optionLabel }}
                </x-chief::form.label>
            </div>
        @endforeach
    </div>
</x-chief::form.input.group>
