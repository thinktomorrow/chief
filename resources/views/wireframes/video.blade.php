@php
    $aspectRatio = $aspectRatio ?? '16/9';
    $aspectRatioArray = explode('/', $aspectRatio);

    $aspectRatioPadding = (int)$aspectRatioArray[1] / (int)$aspectRatioArray[0] * 100;
@endphp

<div 
    class="relative rounded-lg bg-grey-200 {{ $attributes->get('class') }}"
    style="padding-bottom: {{ $aspectRatioPadding }}%; {{ $attributes->get('style') }}"
>
    <div class="absolute inset-0 flex items-center justify-center">
        <svg width="24" height="24" class="text-grey-400"><use xlink:href="#icon-video" /></svg>
    </div>
</div>
