@php
    $label = $label ?? null;
    $description = $description ?? null;
@endphp

<x-chief::input.group :rule="$id" inner-class="space-y-2">
    @if ($label)
        <x-chief::input.label>{{ $label }}</x-chief::input.label>
    @endif

    @if ($description)
        <x-chief::input.description>{{ $description }}</x-chief::input.description>
    @endif

    <div class="space-y-1">
        @foreach($options as $option => $optionLabel)
            <div class="flex items-start gap-2">
                <x-chief::input.checkbox
                    id="{{ $id . '-' . $option }}"
                    name="{{ $name }}"
                    value="{{ $option }}"
                    :checked="($option == ($value ?: $default))"
                />

                <x-chief::input.label for="{{ $id . '-' . $option }}">
                    {{ $optionLabel }}
                </x-chief::input.label>
            </div>
        @endforeach
    </div>
</x-chief::input.group>
