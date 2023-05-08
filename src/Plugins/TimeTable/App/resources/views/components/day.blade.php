@props([
    'date' => null,
    'content' => null,
    'exception' => false,
    'slots' => [],
    'minimal'
])

@php
    $isToday = $date ? $date->isToday() : false;
    $title = $date ? $date->format('d') : null;
@endphp

@if($attributes->has('href'))
    <a
        href="{{ route('chief.timetable_days.edit', $day->id) }}"
        title="{{ \Thinktomorrow\Chief\Plugins\TimeTable\Domain\Values\Day::fromDateTime($date)->getLabel() }}"

    @class([
        'block p-2 border-grey-100',
        'w-[calc(100%/7)]' => !$minimal,
        'w-full lg:w-[calc(100%/7)]' => $minimal,
        'border-r' => !$loop->last,
        'border-b' => $count - $loop->index > 7,
    ])
    >
@else
    <div
        @class([
            'block p-2 border-grey-100',
            'w-[calc(100%/7)]' => !$minimal,
            'w-full lg:w-[calc(100%/7)]' => $minimal,
            'border-r' => !$loop->last,
            'border-b' => $count - $loop->index > 7,
        ])
    >
@endif

<div {{ $attributes->class('space-y-1') }}>
    <div @class([
        'flex items-start justify-between gap-2',
        'max-lg:flex-col' => isset($minimal),
    ])>
        @if($title)
            <div @class([
                'text-sm font-medium leading-5 body body-dark',
                'max-lg:ml-auto' => isset($minimal),
            ])>
                {{ $title }}
            </div>
        @endif

        @if($exception)
            <svg class="w-5 h-5 text-orange-500 shrink-0"><use xlink:href="#icon-exclamation-circle"></use></svg>
        @endif
    </div>

    <div @class(['space-y-1', 'max-lg:hidden' => isset($minimal)])>
        @if(empty($slots))
            <p @class(['label label-xs', 'label-grey' => !$exception, 'label-warning' => $exception])>
                Gesloten
            </p>
        @else
            @foreach($slots as $slot)
                <p @class(['label label-xs', 'label-grey' => !$exception, 'label-warning' => $exception])>
                    {{ $slot }}
                </p>
            @endforeach
        @endif

        @if($content)
            <p class="text-xs body text-grey-500">
                {{ $content }}
            </p>
        @endif
    </div>
</div>

@if($attributes->has('href'))
    <a>
@else
    <div>
@endif
