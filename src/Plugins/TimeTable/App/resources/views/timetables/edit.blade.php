@php
    $breadcrumb = new \Thinktomorrow\Chief\Admin\Nav\BreadCrumb('Terug naar overzicht', route('chief.timetables.index'));
    $timeTable = (new \Thinktomorrow\Chief\Plugins\TimeTable\App\TimeTableFactory())->createWithoutExceptions($model, app()->getLocale());
@endphp

<x-chief::page.template :title="$model->label">
    <x-slot name="header">
        <x-chief::page.header
            :breadcrumbs="[
                ['label' => 'Dashboard', 'url' => route('chief.back.dashboard'), 'icon' => 'home'],
                ['label' => 'Weekschema', 'url' => route('chief.timetables.index'), 'icon' => 'calendar'],
                $model->label,
            ]"
        />
    </x-slot>

    <x-chief::window>
        <form id="timeTableEditForm" action="{{ route('chief.timetables.update', $model->id) }}" method="POST">
            @csrf
            @method('PUT')

            @foreach ($fields as $field)
                {!! $field->render() !!}
            @endforeach

            <x-chief::button data-slot="form-group" type="submit" variant="blue">Bewaar naam</x-chief::button>
        </form>
    </x-chief::window>

    <x-chief::window title="Weekschema">
        {{-- TODO: this shouldn't display the current week, but rather a week with all default day values --}}
        <x-chief-timetable::time-table
            :time-table="$timeTable"
            :days="$timeTable->forCurrentWeek()"
            :is-calendar="false"
            :day-models="$model->days"
        />
    </x-chief::window>

    <x-slot name="sidebar">
        <x-chief::window title="Uitzonderingen">
            <x-slot name="actions">
                <x-chief::button
                    href="{{ route('chief.timetable_dates.create', $model->id) }}"
                    variant="grey"
                    size="sm"
                >
                    <x-chief::icon.plus-sign />
                </x-chief::button>
            </x-slot>

            <div>
                @forelse ($model->exceptions as $date)
                    @php
                        $title = \Thinktomorrow\Chief\Plugins\TimeTable\Domain\Values\Day::fromDateTime($date->date)->getLabel() . ' ' . $date->date->format('d/m/Y');
                    @endphp

                    <div
                        @class([
                            'w-full space-y-1',
                            'pt-3' => ! $loop->first,
                            'border-b border-grey-100 pb-4' => ! $loop->last,
                        ])
                    >
                        <div class="flex items-start justify-between gap-2">
                            @if ($title)
                                <div class="body body-dark mt-1 text-sm font-medium leading-5">
                                    {{ $title }}
                                </div>
                            @endif

                            <x-chief::button
                                href="{{ route('chief.timetable_dates.edit', [$model->id, $date->id]) }}"
                                variant="grey"
                                size="sm"
                            >
                                <x-chief::icon.quill-write />
                            </x-chief::button>
                        </div>

                        <div class="flex flex-col items-start gap-1">
                            @forelse ($date->getSlots()->getSlots() as $slot)
                                <p class="label label-xs label-grey">
                                    {{ $slot->getAsString() }}
                                </p>
                            @empty
                                <p class="label label-xs label-grey">Gesloten</p>
                            @endforelse

                            @if ($date->content)
                                <p class="body text-xs text-grey-500">
                                    {{ $date->content }}
                                </p>
                            @endif
                        </div>
                    </div>
                @empty
                    <p class="body body-dark">Er zijn momenteel geen uitzonderingen.</p>
                @endforelse
            </div>
        </x-chief::window>
    </x-slot>
</x-chief::page.template>
