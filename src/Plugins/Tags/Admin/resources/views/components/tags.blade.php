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
        @class([
            'inline-flex items-center gap-x-1.5 rounded-md px-2 py-1 font-medium body-dark ring-1 ring-inset ring-grey-200 hover:ring-grey-300',
            'hidden animate-pop-in-tag' => $loop->iteration >= $threshold,
            'text-xs' => $size === 'xs',
            'text-sm' => $size === 'sm',
        ])
    >
        <svg class="w-2 h-2" style="fill:{{ $tag->getColor() }};" viewBox="0 0 6 6" aria-hidden="true">
            <circle cx="3" cy="3" r="3" />
        </svg>

        {{ $tag->getLabel() }}
    </a>
@endforeach

@if ($count > $threshold)
    <button
        type="button"
        data-toggle-class="[data-hidden-tag]|[data-toggle-hidden-tags]"
        data-toggle-hidden-tags
        class="rounded-full px-2.5 py-1 ring-1 ring-inset ring-grey-200 hover:bg-grey-50"
    >
        <div @class(['font-medium body-dark', 'text-xs' => $size === 'xs', 'text-sm' => $size === 'sm'])>
            +{{ $count - ($threshold - 1) }} tags
        </div>
    </button>
@endif
