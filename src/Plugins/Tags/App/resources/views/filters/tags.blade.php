@php
    $label = $label ?? null;
    $description = $description ?? null;
@endphp

@if (count($tags) > 0)
    <x-chief::input.group :rule="$id">
        @if ($label)
            <x-chief::form.label for="{{ $id }}" unset class="h6 body-dark font-medium">
                {{ $label }}
            </x-chief::form.label>
        @endif

        @if ($description)
            <x-chief::form.description>{{ $description }}</x-chief::form.description>
        @endif

        <div class="flex flex-wrap items-start gap-1">
            @foreach ($tags as $tag)
                <x-chief::form.label for="{{ $tag->getTagId() . '-' . $tag->getColor() }}" class="cursor-pointer">
                    <x-chief::input.radio
                        id="{{ $tag->getTagId() . '-' . $tag->getColor() }}"
                        name="{{ $name }}"
                        value="{{ $tag->getTagId() }}"
                        :checked="in_array($tag->getTagId(), (array) $value)"
                        class="peer hidden"
                    />

                    <x-chief-tags::tag
                        :color="$tag->getColor()"
                        size="sm"
                        class="peer-checked:shadow peer-checked:ring-1 peer-checked:ring-primary-500"
                    >
                        {{ $tag->getLabel() }}
                    </x-chief-tags::tag>
                </x-chief::form.label>
            @endforeach
        </div>
    </x-chief::input.group>
@endif
