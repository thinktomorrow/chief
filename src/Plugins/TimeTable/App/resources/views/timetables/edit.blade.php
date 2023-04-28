@php
    $breadcrumb = new \Thinktomorrow\Chief\Admin\Nav\BreadCrumb('Terug naar overzicht', route('chief.timetables.index'));
@endphp

<x-chief::page.template title="Schema aanpassen">
    <x-slot name="hero">
        <x-chief::page.hero :title="$model->label" :breadcrumbs="[$breadcrumb]">
            {{-- TODO: render field as pagetitle --}}
            {{-- <x-slot name="customTitle">
                {!! $fields->first()->render() !!}
            </x-slot> --}}
        </x-chief::page.hero>
    </x-slot>

    <x-chief::page.grid>
        {{-- TODO: remove this card once the field is editable as pagetitle --}}
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
            <div class="flex flex-wrap items-start gap-3">
                @foreach($model->days as $day)
                    <a href="{{ route('chief.timetable_days.edit', $day->id) }}" title="{{ $day->getLabel() }}">
                        <x-chief-timetable::day :title="$day->getLabel()" :day="$day" class="w-48"/>
                    </a>
                @endforeach
            </div>
        </x-chief::window>

        <x-slot name="aside">
            <x-chief::window title="Uitzonderingen" class="card">
                <x-slot name="buttons">
                    <a href="{{ route('chief.timetable_dates.create', $model->id) }}">
                        <x-chief::icon-button icon="icon-plus" color="grey" class="shadow-none bg-grey-50 text-grey-500" />
                    </a>
                </x-slot>

                <div class="space-y-3">
                    @foreach($model->exceptions as $date)
                        @php
                            $title = \Thinktomorrow\Chief\Plugins\TimeTable\Domain\Values\Day::fromDateTime($date->date)->getShortLabel().' '.$date->date->format('d/m/Y');
                        @endphp

                        <a href="{{ route('chief.timetable_dates.edit', [$model->id, $date->id]) }}" title="{{ $title }}" class="block">
                            <x-chief-timetable::day :title="$title" :day="$date" class="w-full"/>
                        </a>
                    @endforeach
                </div>
            </x-chief::window>
        </x-slot>
    </x-chief::page.grid>
</x-chief::page.template>
