@props([
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
    <div class="space-y-1">
        <div
            @class([
                'flex items-start justify-between gap-2',
                'max-lg:flex-col' => isset($minimal),
            ])
        >
            @if ($title)
                <div
                    @class([
                        'body body-dark text-sm font-medium leading-5',
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

        <div @class(['space-y-1', 'max-lg:hidden' => isset($minimal)])>
            @if (empty($slots))
                <p @class(['label label-xs', 'label-grey' => ! $exception, 'label-warning' => $exception])>
                    Gesloten
                </p>
            @else
                @foreach ($slots as $slot)
                    <p @class(['label label-xs', 'label-grey' => ! $exception, 'label-warning' => $exception])>
                        {{ $slot }}
                    </p>
                @endforeach
            @endif

            @if ($content)
                <p class="body text-xs text-grey-500">
                    {{ $content }}
                </p>
            @endif
        </div>
    </div>
@if ($attributes->has('href'))
    </a>
@else
    </div>
@endif
