@php
    $isDefaultSlotEmpty = ($slot == '');
    $hasDefaultTitle = $hasDefaultTitle ?? true;
@endphp

<div class="row-between-center gutter-1">
    @isset($breadcrumbs)
        <div class="w-full">
            {!! $breadcrumbs !!}
        </div>
    @endisset

    <div class="{{ $isDefaultSlotEmpty ? 'w-full' : 'w-full lg:w-1/2' }}">
        @if($hasDefaultTitle)
            <h1 class="text-grey-900">
                {{ ucfirst($title) }}
            </h1>
        @else
            {!! $title !!}
        @endif
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
