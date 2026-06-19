@props ([
    'date' => null,
    'content' => null,
    'exception' => false,
    'slots' => [],
    'minimal',
])

@php
    $isToday = $date ? $date->isToday() : false;
    $title = $date ? $date->format('d') : null;
    $wrapperClasses = [
        'block border-grey-100 p-2',
        'w-[calc(100%/7)]' => ! $minimal,
        'w-full lg:w-[calc(100%/7)]' => $minimal,
        'border-r' => ! $loop->last,
        'border-b' => $count - $loop->index > 7,
    ];
@endphp

@if ($attributes->has('href'))
    <a
        href="{{ $attributes->get('href') }}"
        title="{{ \Thinktomorrow\Chief\Plugins\TimeTable\Domain\Values\Day::fromDateTime($date)->getLabel() }}"
        {{ $attributes->except('href')->class($wrapperClasses) }}
    >
@else
    <div {{ $attributes->class($wrapperClasses) }}>
@endif
<div class="space-y-1.5">
    <div
        @class ([
            'flex items-start justify-between gap-2',
            'max-lg:flex-col' => isset($minimal),
        ])
    >
        @if ($title)
            <div
                @class ([
                    'body text-grey-800 text-sm leading-5 font-medium',
                    'text-primary-500' => $isToday,
                    'max-lg:ml-auto' => isset($minimal),
                ])
            >
                {{ $title }}
            </div>
        @endif

        @if ($exception)
            <x-chief::icon.alert-circle class="size-5 shrink-0 text-orange-500" />
        @endif
    </div>

    <div @class (['flex flex-col items-start gap-1.5', 'max-lg:hidden' => isset($minimal)])>
        @if (empty($slots))
            <x-chief::badge :variant="$exception ? 'orange' : 'grey'">Gesloten</x-chief::badge>
        @else
            @foreach ($slots as $slot)
                <x-chief::badge :variant="$exception ? 'orange' : 'grey'">{{ $slot }}</x-chief::badge>
            @endforeach
        @endif

        @if ($content)
            <p class="body text-grey-500 text-xs">{{ $content }}</p>
        @endif
    </div>
</div>
@if ($attributes->has('href'))
    </a>
@else
    </div>
@endif
