<?php
if(isset($attributes)){
    compact($attributes);
}
?>

<div class="{{ $class ?? '' }}">
    <div class="relative">

        @if(isset($editRequestUrl))
            <a data-sidebar-{{ $type ?? '' }}-edit href="{{ $editRequestUrl }}" class="absolute link link-black right-0 top-0">
                <x-link-label type="edit"></x-link-label>
            </a>
        @endif

        @if(isset($title))
            <h3 class="mr-8">{{ $title }}</h3>
        @endif

        {{ $slot }}
    </div>
</div>
