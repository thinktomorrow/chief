@php

    if($isCalendar) {
        $date = \Illuminate\Support\Carbon::parse($date);
        $exception = $timeTable->isException($date);
        $isToday = $date->isToday();
        $title = $date->format('d/m');
    } else{
        $date = null;
        $exception = null;
        $isToday = false;
        $title = $weekDays[$loop->index % 7];
    }

    if(!$dayModels->isEmpty()) {
        $date = \Illuminate\Support\Carbon::parse($date);
        $dayModel = $dayModels[$loop->index % 7];
    }

@endphp

<div @class([
                    'block sm:p-2 border-grey-100 w-full sm:w-32 grow shrink-0',
                    'sm:border-r' => $loop->iteration % 7 != 0,
                    'sm:border-b' => count($days) - $loop->index > 7,
                ])>
    <div {{ $attributes->class('space-y-1') }}>
        <div class="flex items-start justify-between gap-2">
            @if($title)
                <div @class([
                    'text-sm font-medium leading-5 body body-dark mt-1',
                    'text-primary-500' => $isToday,
                ])>
                    @if($isCalendar)
                        <span class="lg:hidden">{{ $weekDays[$loop->index % 7] }}</span>
                    @endif

                    {{ $title }}
                </div>
            @endif

            @if($exception)
                <x-chief::icon-button icon="icon-edit" color="warning" class="bg-orange-100 shadow-none hover:bg-orange-100">
                    <svg class="w-4 h-4"><use xlink:href="#icon-exclamation-circle"></use></svg>
                </x-chief::icon-button>
            @endif

            @if(!$isCalendar && isset($dayModel))
                <a href="{{ route('chief.timetable_days.edit', $dayModel->id) }}" title="Dag aanpassen"/>
                <x-chief::icon-button icon="icon-edit" color="grey" class="shadow-none text-grey-500">
                    <svg class="w-4 h-4"><use xlink:href="#icon-edit"></use></svg>
                </x-chief::icon-button>
                </a>
            @endif
        </div>

        <div @class(['space-y-1'])>
            @forelse((iterator_to_array($day->getIterator())) as $slot)
                <p @class(['label label-xs', 'label-grey' => !$exception, 'label-warning' => $exception])>
                    {{ $slot }}
                </p>
            @empty
                <p @class(['label label-xs', 'label-grey' => !$exception, 'label-warning' => $exception])>
                    Gesloten
                </p>
            @endforelse

            @if($day->getData())
                <p class="text-xs body text-grey-500">
                    {{ $day->getData() }}
                </p>
            @endif
        </div>
    </div>
</div>
