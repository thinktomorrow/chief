@props([
    'url' => null
])

<a
    href="{{ $url }}"
    title="Ga naar deze URL"
    class="inline font-normal underline break-all link link-grey"
    target="_blank"
    rel="noopener"
>
    @if($slot->isNotEmpty())
        {{ $slot }}
    @else
        {{ $url }}
    @endif
</a>
