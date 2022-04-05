@props([
    'src' => null,
])

@if($src)
    <div {{ $attributes }} class="w-full h-32">
        <a href="{{ $src }}" title="Chief fragment preview image" target="_blank" rel="noopener">
            <img
                src="{{ $src }}"
                alt="Chief fragment preview image"
                class="object-contain w-full h-full rounded-lg bg-grey-100"
            >
        </a>
    </div>
@endif
