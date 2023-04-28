@php
    $breadcrumb = new \Thinktomorrow\Chief\Admin\Nav\BreadCrumb('Terug naar overzicht', route('chief.timetables.index'));
@endphp

<x-chief::page.template title="Schema aanpassen">
    <x-slot name="hero">
        <x-chief::page.hero title="Schema aanpassen" :breadcrumbs="[$breadcrumb]" class="max-w-3xl"></x-chief::page.hero>
    </x-slot>

    <x-chief::page.grid class="max-w-3xl">
        <form id="timeTableEditForm" action="{{ route('chief.timetables.update', $model->id) }}" method="POST" class="card">
            @csrf
            @method('PUT')

            <div class="space-y-4">

                @foreach($fields as $field)
                    {!! $field->render() !!}
                @endforeach

            </div>

            <button class="btn btn-primary mt-4" type="submit">Bewaar naam</button>
        </form>

        <div class="card space-y-4">

            <h3>Weekschema</h3>

            @foreach($model->days as $day)
                <div class="bg-grey-50 shadow p-4 rounded relative">
                    <h2 class="font-bold">{{ $day->getLabel() }}</h2>

                    @if(empty($day->getSlots()->getSlots()))
                        <p>Gesloten</p>
                    @else
                        @foreach($day->getSlots()->getSlots() as $slot)
                            <p>{{ $slot->getAsString() }}</p>
                        @endforeach
                    @endif

                    @if($day->content)
                        <p>{{ $day->content }}</p>
                    @endif

                    <div class="absolute top-0 right-0 p-4">
                        <a href="{{ route('chief.timetable_days.edit', $day->id) }}">
                            <x-chief::icon-button icon="icon-edit" color="grey" class="bg-grey-50 shadow-none text-grey-500" />
                        </a>
                    </div>

                </div>

            @endforeach
        </div>

        <div class="card space-y-4">

            <h3>Uitzonderingen</h3>

            <a href="{{ route('chief.timetable_dates.create', $model->id) }}">
                <x-chief::icon-button icon="icon-plus" color="grey" class="bg-grey-50 shadow-none text-grey-500" />
            </a>

            @foreach($model->exceptions as $date)
                <div class="bg-grey-50 shadow p-4 rounded relative">
                    <h2 class="font-bold">{{ \Thinktomorrow\Chief\Plugins\TimeTable\Domain\Values\Day::fromDateTime($date->date)->getLabel().' '.$date->date->format('d/m/Y') }}</h2>

                    @if(empty($date->getSlots()->getSlots()))
                        <p>Gesloten</p>
                    @else
                        @foreach($date->getSlots()->getSlots() as $slot)
                            <p>{{ $slot->getAsString() }}</p>
                        @endforeach
                    @endif

                    @if($date->content)
                        <p>{{ $date->content }}</p>
                     @endif


                    <div class="absolute top-0 right-0 p-4">
                        <a href="{{ route('chief.timetable_dates.edit', [$model->id, $date->id]) }}">
                            <x-chief::icon-button icon="icon-edit" color="grey" class="bg-grey-50 shadow-none text-grey-500" />
                        </a>
                    </div>

                </div>

            @endforeach
        </div>

    </x-chief::page.grid>
</x-chief::page.template>
