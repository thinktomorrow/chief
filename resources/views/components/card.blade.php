@php
    if(isset($attributes)) {
        compact($attributes);
    }
@endphp

<div class="{{ $class ?? '' }}">
    <div class="relative">
        @if(isset($editRequestUrl))
            <a data-sidebar-{{ $type ?? '' }}-edit href="{{ $editRequestUrl }}" class="absolute right-0 top-0 link link-black">
                <x-link-label type="edit"></x-link-label>
            </a>
        @endif

        <div class="space-y-6">
            @if(isset($title) && $title)
                <h3 class="leading-none mr-8">{{ $title }}</h3>
            @endif

            @if($slot)
                <div>
                    {{ $slot }}
                </div>
            @endif
        </div>
    </div>
</div>
