@php
    $isDefaultSlotEmpty = ($slot == '');
@endphp

<div class="row-between-center gutter-2">
    @isset($breadcrumbs)
        <div class="w-full">
            {!! $breadcrumbs !!}
        </div>
    @endisset

    <div class="{{ $isDefaultSlotEmpty ? 'w-full' : 'w-full lg:w-1/2' }}">
        {!! $title !!}
    </div>

    @if(!$isDefaultSlotEmpty)
        <div class="flex items-center justify-end flex-shrink-0 w-full lg:w-1/2">
            {{ $slot }}
        </div>
    @endif

    @isset($extra)
        <div class="w-full">
            {{ $extra ??  '' }}
        </div>
    @endisset
</div>
