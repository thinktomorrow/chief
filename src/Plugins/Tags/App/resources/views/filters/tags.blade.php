@php
    $label = $label ?? null;
    $description = $description ?? null;
@endphp

<x-chief::input.group :rule="$id">
    @if ($label)
        <x-chief::input.label for="{{ $id }}" unset class="font-medium h6 body-dark">{{ $label }}</x-chief::input.label>
    @endif

    @if ($description)
        <x-chief::input.description>{{ $description }}</x-chief::input.description>
    @endif

    <div class="flex flex-wrap items-start gap-1">
        @foreach ($tags as $tag)
            <x-chief::input.label for="{{ $tag->getTagId() . '-' . $tag->getColor() }}" class="cursor-pointer">
                <x-chief::input.radio
                    id="{{ $tag->getTagId() . '-' . $tag->getColor() }}"
                    name="{{ $name }}"
                    value="{{ $tag->getTagId() }}"
                    :checked="in_array($tag->getTagId(), (array) $value)"
                    class="hidden peer"
                />

                <x-chief-tags::tag
                    :color="$tag->getColor()"
                    size="sm"
                    class="peer-checked:ring-primary-500 peer-checked:ring-1 peer-checked:shadow"
                >
                    {{ $tag->getLabel() }}
                </x-chief-tags::tag>
            </x-chief::input.label>
        @endforeach
    </div>
</x-chief::input.group>
