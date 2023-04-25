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


{{--                <!-- label -->--}}
{{--                <div class="space-y-1 form-light">--}}
{{--                    <x-chief::input.label :required="true">Intern label</x-chief::input.label>--}}

{{--                    <x-chief::input.text--}}
{{--                        name="label"--}}
{{--                        placeholder="Bijv. Openingsuren Gent, Levertijden magazijn, ..."--}}
{{--                        value="{{ old('label', $model->label) }}"--}}
{{--                    />--}}

{{--                    <x-chief::input.error rule="label"/>--}}
{{--                </div>--}}

{{--                <!-- day -->--}}
{{--                @foreach(\Thinktomorrow\Chief\Plugins\TimeTable\Domain\Values\Day::fullList() as $day)--}}
{{--                    <div class="space-y-4 form-light">--}}
{{--                        <h2>{{ $day->getLabel() }}</h2>--}}

{{--                        <div class="space-y-2">--}}
{{--                            <x-chief::input.label>Uren (van - tot)</x-chief::input.label>--}}


{{--                            @foreach([0,1] as $i)--}}
{{--                                <div class="row-start-start gap-2">--}}

{{--                                    <div class="w-1/3">--}}
{{--                                        <x-chief::input.time--}}
{{--                                            step="{{ 60*15 }}"--}}
{{--                                            name="days[{{ $day->getIso8601WeekDay() }}][hours][{{ $i }}][from]"--}}
{{--                                            value="{{ old('days.'.$day->getIso8601WeekDay().'.hours.' . $i.'.from', $model->getSlotForForm($day->getIso8601WeekDay(), $i,'from')) }}"--}}
{{--                                            placeholder="van"--}}
{{--                                        />--}}

{{--                                        <x-chief::input.error rule="days.{{ $day->getIso8601WeekDay() }}.hours.{{$i}}.from"/>--}}
{{--                                    </div>--}}
{{--                                    <div class="w-1/3">--}}
{{--                                        <x-chief::input.time--}}
{{--                                            step="{{ 60*15 }}"--}}
{{--                                            name="days[{{ $day->getIso8601WeekDay() }}][hours][{{ $i }}][until]"--}}
{{--                                            value="{{ old('days.'.$day->getIso8601WeekDay().'.hours.' . $i.'.until', $model->getSlotForForm($day->getIso8601WeekDay(), $i,'until')) }}"--}}
{{--                                            placeholder="tot"--}}
{{--                                        />--}}

{{--                                        <x-chief::input.error rule="days.{{ $day->getIso8601WeekDay() }}.hours.{{$i}}.until"/>--}}
{{--                                    </div>--}}
{{--                                </div>--}}

{{--                            @endforeach--}}
{{--                        </div>--}}

{{--                        {{ \Thinktomorrow\Chief\Forms\Fields\Text::make('days.'.$day->getIso8601WeekDay().'.content')--}}
{{--                            ->label('Eigen tekstje')--}}
{{--                            ->placeholder('Bijv. Openingsuren Gent, Levertijden magazijn, ...')--}}
{{--                            ->render() }}--}}
{{--                    </div>--}}
{{--                @endforeach--}}

            </div>

            <button class="btn btn-primary mt-4" type="submit">Bewaar aanpassingen</button>
        </form>
    </x-chief::page.grid>
</x-chief::page.template>
