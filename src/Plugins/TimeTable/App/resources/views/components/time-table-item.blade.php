@php
    if ($isCalendar) {
        $date = \Illuminate\Support\Carbon::parse($date);
        $exception = $timeTable->isException($date);
        $isToday = $date->isToday();
        $title = $date->format('d/m');
    } else {
        $date = null;
        $exception = null;
        $isToday = false;
        $title = $weekDays[$loop->index % 7];
    }

    if (! $dayModels->isEmpty()) {
        $date = \Illuminate\Support\Carbon::parse($date);
        $dayModel = $dayModels[$loop->index % 7];
    }
@endphp

<div
    @class([
        'border-grey-100 block w-full shrink-0 grow sm:w-32 sm:p-2',
        'sm:border-r' => $loop->iteration % 7 != 0,
        'sm:border-b' => count($days) - $loop->index > 7,
    ])
>
    <div {{ $attributes->class('space-y-1') }}>
        <div class="flex items-start justify-between gap-2">
            @if ($title)
                <div
                    @class([
                        'body body-dark mt-1 text-sm leading-5 font-medium',
                        'text-primary-500' => $isToday,
                    ])
                >
                    @if ($isCalendar)
                        <span class="lg:hidden">{{ $weekDays[$loop->index % 7] }}</span>
                    @endif

                    {{ $title }}
                </div>
            @endif

            @if ($exception)
                <x-chief::icon.alert-circle class="m-1.5 size-5 text-orange-500" />
            @endif

            @if (! $isCalendar && isset($dayModel))
                <x-chief::button
                    href="{{ route('chief.timetable_days.edit', $dayModel->id) }}"
                    title="Dag aanpassen"
                    variant="grey"
                    size="sm"
                    class="shrink-0"
                >
                    <x-chief::icon.quill-write />
                </x-chief::button>
            @endif
        </div>

        <div @class(['space-y-1'])>
            @forelse (iterator_to_array($day->getIterator()) as $slot)
                <p @class(['label label-xs', 'label-grey' => ! $exception, 'label-warning' => $exception])>
                    {{ $slot }}
                </p>
            @empty
                <p @class(['label label-xs', 'label-grey' => ! $exception, 'label-warning' => $exception])>Gesloten</p>
            @endforelse

            @if ($day->getData())
                <p class="body text-grey-500 text-xs">
                    {{ $day->getData() }}
                </p>
            @endif
        </div>
    </div>
</div>
