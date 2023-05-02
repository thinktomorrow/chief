@props([
    'title' => isset($date) ? $date->format('d') : null,
    'isToday' => isset($date) ? $date->isToday() : false,
    'content' => null,
    'exception' => false,
    'slots' => [],
    'inTimeTable'
])

<div {{ $attributes->class('space-y-1') }}>
    <div @class([
        'flex items-start justify-between gap-2',
        'max-lg:flex-col' => isset($inTimeTable),
    ])>
        @if($title)
            <div @class([
                'text-sm font-medium leading-5 body body-dark',
                'max-lg:ml-auto' => isset($inTimeTable),
            ])>
                {{ $title }}
            </div>
        @endif

        @if($exception)
            <svg class="w-5 h-5 text-orange-500 shrink-0"><use xlink:href="#icon-exclamation-circle"></use></svg>
        @endif
    </div>

    <div @class(['space-y-1', 'max-lg:hidden' => isset($inTimeTable)])>
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
            <p @class(['label label-xs', 'label-grey' => !$exception, 'label-warning' => $exception])>
                {{ $content }}
            </p>
        @endif
    </div>
</div>
