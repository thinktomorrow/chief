@props([
    'timeTable',
    'days' => [],
    'wrap' => false,
    'withDates' => true,
    'withDateEdit' => false,
    'read' => true,
])

@php
    $date = date('M d, Y');
    $count = count($days);
    $weekDays = ['Ma', 'Di', 'Wo', 'Do', 'Vr', 'Za', 'Zo'];
    $weekDaysLong = ['Maandag', 'Dinsdag', 'Woensdag', 'Donderdag', 'Vrijdag', 'Zaterdag', 'Zondag'];
@endphp

<div @class([
    'border rounded-md border-grey-100',
    'max-lg:border-0 max-lg:rounded-none' => $wrap
])>
    @if(!$wrap)
        <div class="flex flex-wrap">
            @foreach($weekDays as $weekDay)
                <div @class([
                    'text-center text-sm h1-dark font-medium p-1 w-[calc(100%/7)] border-b border-grey-100',
                    'border-r' => !$loop->last,
                    'max-lg:hidden' => $wrap,
                ])>
                    <span>{{ $weekDay }}</span>
                </div>
            @endforeach
        </div>
    @endif

    <div @class(['row-start-stretch', 'max-lg:gutter-3' => $wrap])>
        @foreach($days as $date => $day)
            @php
                if ($withDates) {
                    $date = \Illuminate\Support\Carbon::parse($date);
                } else {
                    $date = null;
                }

                if ($read) {
                    $slots = (iterator_to_array($day->getIterator()));
                    $exception = $timeTable->isException($date);
                    $content = $day->getData();
                } else {
                    $slots = $day->getSlots()->getSlots();
                    $exception = false;
                    $content = $day->content;
                }

                $isToday = $date ? $date->isToday() : false;
                $title = $date ? $date->format('d/m') : $weekDaysLong[$loop->index];
            @endphp

            @if($withDateEdit)
                <a
                    href="{{ route('chief.timetable_days.edit', $day->id) }}"
                    title="{{ \Thinktomorrow\Chief\Plugins\TimeTable\Domain\Values\Day::fromDateTime($date)->getLabel() }}"
                    @class([
                        'block p-2 border-grey-100',
                        'w-[calc(100%/7)]' => !$wrap,
                        'max-lg:border-r-0 max-lg:p-0 w-full sm:w-1/2 lg:w-[calc(100%/7)]' => $wrap,
                        'border-r' => !$loop->last,
                        'border-b' => $count - $loop->index > 7,
                    ])
                >
            @else
                <div
                    @class([
                        'block p-2 border-grey-100',
                        'w-[calc(100%/7)]' => !$wrap,
                        'max-lg:border-r-0 max-lg:p-0 w-full sm:w-1/2 lg:w-[calc(100%/7)]' => $wrap,
                        'border-r' => $loop->iteration % 7 != 0,
                        'border-b' => $count - $loop->index > 7,
                    ])
                >
            @endif
                    <div {{ $attributes->class('space-y-1') }}>
                        <div class="flex items-start justify-between gap-2 max-lg:flex-col">
                            @if($title)
                                <div @class([
                                    'text-sm font-medium leading-5 body body-dark',
                                    'max-lg:ml-auto' => !$wrap,
                                    'text-primary-500' => $isToday,
                                ])>
                                    {{ $title }}
                                </div>
                            @endif

                            @if($exception)
                                <svg class="w-5 h-5 text-orange-500 shrink-0"><use xlink:href="#icon-exclamation-circle"></use></svg>
                            @endif
                        </div>

                        <div @class(['space-y-1', 'max-lg:hidden' => !$wrap])>
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
                </a>
            @else
                </div>
            @endif
        @endforeach
    </div>
</div>
