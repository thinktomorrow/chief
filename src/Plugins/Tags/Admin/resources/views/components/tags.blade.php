@props([
    'tags' => [],
    'threshold' => 3,
    'size' => 'sm',
    'count' => count($tags)
])

@foreach ($tags as $tag)
    <a
        {!! $loop->iteration >= $threshold ? 'data-hidden-tag' : null !!}
        href="{{ route('chief.tags.edit', $tag->getTagId()) }}"
        title="Aanpassen"
        @class(['hidden animate-pop-in-tag' => $loop->iteration >= $threshold])
    >
        <x-chief-tags::tag :color="$tag->getColor()" :size="$size">
            {{ $tag->getLabel() }}
        </x-chief-tags::tag>
    </a>
@endforeach

@if ($count > $threshold)
    <button
        type="button"
        data-toggle-class="[data-hidden-tag]|[data-toggle-hidden-tags]"
        data-toggle-hidden-tags
    >
        <x-chief-tags::tag :size="$size">
            +{{ $count - ($threshold - 1) }} tags
        </x-chief-tags::tag>
    </button>
@endif
