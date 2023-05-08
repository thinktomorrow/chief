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
            <x-chief-timetable::time-table :model="$model" :days="$model->days" :wrap="true" />
        </x-chief::window>

        <x-chief::window title="Uitzonderingen" class="card">
            <x-slot name="buttons">
                <a href="{{ route('chief.timetable_dates.create', $model->id) }}">
                    <x-chief::icon-button icon="icon-plus" color="grey" class="shadow-none bg-grey-50 text-grey-500" />
                </a>
            </x-slot>

            <div class="row-start-start gutter-2 sm:gutter-3">
                @foreach($model->exceptions as $date)
                    @php
                        $title = \Thinktomorrow\Chief\Plugins\TimeTable\Domain\Values\Day::fromDateTime($date->date)->getShortLabel().' '.$date->date->format('d/m/Y');
                    @endphp

                    <a
                        href="{{ route('chief.timetable_dates.edit', [$model->id, $date->id]) }}"
                        title="{{ $title }}"
                        class="block w-full space-y-1 sm:w-1/2 lg:w-1/3 xl:w-1/4"
                    >
                        @if($title)
                            <div class="text-sm font-medium leading-5 body body-dark">
                                {{ $title }}
                            </div>
                        @endif

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
                    </a>
                @endforeach
            </div>
        </x-chief::window>
    </x-chief::page.grid>
</x-chief::page.template>
