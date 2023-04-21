@php
    $label = $label ?? null;
    $description = $description ?? null;
@endphp

<x-chief::input.group :rule="$id">
    @if ($label)
        <x-chief::input.label for="{{ $id }}">{{ $label }}</x-chief::input.label>
    @endif

    @if ($description)
        <x-chief::input.description>{{ $description }}</x-chief::input.description>
    @endif

    <div class="flex flex-wrap items-start gap-1">
        @foreach ($options as $color => $label)
            <x-chief::input.label for="{{ $id . '-' . $color }}" class="cursor-pointer">
                <x-chief::input.radio
                    id="{{ $id . '-' . $color }}"
                    name="{{ $name }}"
                    value="{{ $label }}"
                    class="hidden peer"
                />

                <x-chief-tags::tag
                    :color="$color"
                    size="sm"
                    class="peer-checked:ring-1 peer-checked:ring-primary-500"
                >
                    {{ $label }}
                </x-chief-tags::tag>
            </x-chief::input.label>
        @endforeach
    </div>

    <chief-multiselect
        id="{{ $id }}"
        name="{{ $name }}"
        :options='@json($options)'
        selected='@json($value ?: $default)'
        :multiple='@json($multiple)'
        @if($isGrouped)
            grouplabel="group"
            groupvalues="values"
            labelkey="label"
            valuekey="id"
        @endif
    />
</x-chief::input.group>
