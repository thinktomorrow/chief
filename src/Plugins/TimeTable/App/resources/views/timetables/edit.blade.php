@php
    $breadcrumb = new \Thinktomorrow\Chief\Admin\Nav\BreadCrumb('Terug naar overzicht', route('chief.timetables.index'));
    $timeTable = (new \Thinktomorrow\Chief\Plugins\TimeTable\App\TimeTableFactory())->createWithoutExceptions($model, app()->getLocale());
@endphp

<x-chief::page.template title="Schema aanpassen">
    <x-slot name="hero">
        <x-chief::page.hero :title="$model->label" :breadcrumbs="[$breadcrumb]"/>
    </x-slot>

    <x-chief::page.grid>
        <form id="timeTableEditForm" action="{{ route('chief.timetables.update', $model->id) }}" method="POST" class="card">
            @csrf
            @method('PUT')

            <div class="space-y-4">
                @foreach($fields as $field)
                    {!! $field->render() !!}
                @endforeach

                <button class="btn btn-primary" type="submit">Bewaar naam</button>
            </div>
        </form>

        <x-chief::window title="Weekschema" class="card">
            {{-- TODO: this shouldn't display the current week, but rather a week with all default day values --}}
            <x-chief-timetable::time-table
                :time-table="$timeTable"
                :days="$timeTable->forCurrentWeek()"
                :is-calendar="false"
                :day-models="$model->days"
            />
        </x-chief::window>

        <x-slot name="aside">
            <x-chief::window title="Uitzonderingen" class="card">
                <x-slot name="actions">
                    <a href="{{ route('chief.timetable_dates.create', $model->id) }}">
                        <x-chief::icon-button icon="icon-plus" color="grey" class="shadow-none bg-grey-50 text-grey-500" />
                    </a>
                </x-slot>

                <div>
                    @forelse($model->exceptions as $date)
                        @php
                            $title = \Thinktomorrow\Chief\Plugins\TimeTable\Domain\Values\Day::fromDateTime($date->date)->getLabel().' '.$date->date->format('d/m/Y');
                        @endphp

                        <div @class([
                            'w-full space-y-1',
                            'pt-3' => !$loop->first,
                            'pb-4 border-b border-grey-100' => !$loop->last,
                        ])>
                            <div class="flex items-start justify-between gap-2">
                                @if($title)
                                    <div class="mt-1 text-sm font-medium leading-5 body body-dark">
                                        {{ $title }}
                                    </div>
                                @endif

                                <a href="{{ route('chief.timetable_dates.edit', [$model->id, $date->id]) }}" title="Uitzondering aanpassen"/>
                                    <x-chief::icon-button icon="icon-edit" color="grey" class="shadow-none text-grey-500">
                                        <svg class="w-4 h-4"><use xlink:href="#icon-edit"></use></svg>
                                    </x-chief::icon-button>
                                </a>
                            </div>

                            <div class="flex flex-col items-start gap-1">
                                @forelse($date->getSlots()->getSlots() as $slot)
                                    <p class="label label-xs label-grey">
                                        {{ $slot->getAsString() }}
                                    </p>
                                @empty
                                    <p class="label label-xs label-grey">
                                        Gesloten
                                    </p>
                                @endforelse

                                @if($date->content)
                                    <p class="text-xs body text-grey-500">
                                        {{ $date->content }}
                                    </p>
                                @endif
                            </div>
                        </div>
                    @empty
                        <p class="body body-dark">
                            Er zijn momenteel geen uitzonderingen.
                        </p>
                    @endforelse
                </div>
            </x-chief::window>
        </x-slot>
    </x-chief::page.grid>
</x-chief::page.template>
