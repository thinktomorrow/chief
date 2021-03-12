@php
    if(isset($attributes)) {
        compact($attributes);
    }
@endphp

<div class="{{ $class ?? '' }}">
    <div class="relative">
        @if(isset($title))
            <h3 class="mr-8">{{ $title }}</h3>
        @endif

        @if(isset($editRequestUrl))
            <a data-sidebar-{{ $type ?? '' }}-edit href="{{ $editRequestUrl }}" class="absolute right-0 top-0 link link-black">
                <x-link-label type="edit"></x-link-label>
            </a>
        @endif

        {{ $slot }}
    </div>
</div>
