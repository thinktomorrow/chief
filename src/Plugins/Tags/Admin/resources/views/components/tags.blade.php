@props([
    'tags' => [],
    'threshold' => 3,
    'size' => 'sm',
])

@php
    $count = count($tags);

    if($count > $threshold) {
        $tags = $tags->slice(0, $threshold - 1);
    }
@endphp

<div {{ $attributes->class(['flex flex-wrap justify-start', 'gap-1' => $size === 'xs', 'gap-2' => $size === 'sm']) }}>
    @foreach ($tags as $tag)
        <a
            href="{{ route('chief.tags.edit', $tag->getTagId()) }}"
            title="Aanpassen"
            class="relative inline-flex items-center rounded-full px-2.5 py-1 ring-1 ring-inset ring-grey-200 hover:bg-grey-50"
        >
            <div class="absolute flex items-center justify-center flex-shrink-0">
                <span
                    class="w-2.5 h-2.5 rounded-full"
                    style="background-color:{{ $tag->getColor() }};"
                    aria-hidden="true"
                ></span>
            </div>

            <div @class(['ml-4 font-medium body-dark', 'text-xs' => $size === 'xs', 'text-sm' => $size === 'sm'])>
                {{ $tag->getLabel() }}
            </div>
        </a>
    @endforeach

    {{-- TODO: clicking on this tag should show all 'hidden' tags --}}
    @if ($count > $threshold)
        <span class="rounded-full px-2.5 py-1 ring-1 ring-inset ring-grey-200 hover:bg-grey-50">
            <div @class(['font-medium body-dark', 'text-xs' => $size === 'xs', 'text-sm' => $size === 'sm'])>
                +{{ $count - ($threshold - 1) }} tags
            </div>
        </span>
    @endif
</div>
