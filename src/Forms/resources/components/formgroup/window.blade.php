@props(['label' => null])

<div class="flex flex-wrap justify-between w-full gap-y-1 gap-x-3">
    @if($label)
        <div class="w-48">
            <span class="font-medium display-base body-dark">
                {{ ucfirst($label) }}
            </span>
        </div>
    @endif

    <div class="w-128">
        {!! $slot !!}
    </div>
</div>
