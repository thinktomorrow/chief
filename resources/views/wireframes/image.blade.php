@php
    switch($size ?? null) {
        case 'xs':
            $heightClass = 'h-8'; $maxHeightClass = 'max-h-8'; break;
        case 'sm':
            $heightClass = 'h-16'; $maxHeightClass = 'max-h-16'; break;
        case 'md':
            $heightClass = 'h-24'; $maxHeightClass = 'max-h-24'; break;
        case 'lg':
            $heightClass = 'h-32'; $maxHeightClass = 'max-h-32'; break;
        case 'xl':
            $heightClass = 'h-48'; $maxHeightClass = 'max-h-48'; break;
        case '2xl':
            $heightClass = 'h-64'; $maxHeightClass = 'max-h-64'; break;
        default:
            $heightClass = 'h-24'; $maxHeightClass = 'max-h-24'; break;
    }
@endphp

@unless($slot->isEmpty())
    @if(isset($type) && $type == 'background')
        <div
            class="w-full bg-center bg-no-repeat bg-cover rounded-lg {{ $heightClass }} {{ $attributes->get('class') }}"
            style="background-image: url('{{ $slot }}'); {{ $attributes->get('style') }}"
        ></div>
    @elseif(isset($type) && $type == 'custom')
        <div style="{{ $attributes->get('style') }}" class="{{ $attributes->get('class') }}">
            {{ $slot }}
        </div>
    @else
        <img
            src="{{ $slot }}"
            alt="Chief wireframe image"
            class="rounded-lg {{ $maxHeightClass }} {{ $attributes->get('class') }}"
            style="{{ $attributes->get('style') }}"
        >
    @endif
@else
    <div
        class="w-full rounded-lg bg-grey-200 flex items-center justify-center {{ $heightClass }} {{ $attributes->get('class') }}"
        style="{{ $attributes->get('style') }}"
    >
        <svg width="24" height="24" class="text-grey-400"><use xlink:href="#icon-image" /></svg>
    </div>
@endunless
