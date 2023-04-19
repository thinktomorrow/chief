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

                <!-- label -->
                <div class="space-y-1 form-light">
                    <x-chief::input.label :required="true">Intern label</x-chief::input.label>

                    <x-chief::input.text
                        name="label"
                        placeholder="Bijv. Openingsuren Gent, Levertijden magazijn, ..."
                        value="{{ old('label', $model->label) }}"
                    />

                    <x-chief::input.error rule="label"/>
                </div>

                <!-- day -->
                <div class="space-y-1 form-light">
                    <h2>Maandag</h2>

                    <x-chief::input.label>Eigen tekstje</x-chief::input.label>

                    <x-chief::input.description>
                       Kan ook in mensentaal. Bijv. "Gesloten op Paasmaandag", "Kantoor dicht want Teambuilding 🥳
                    </x-chief::input.description>

                    <x-chief::input.text
                        name="days[1][content]"
                        placeholder="Bijv. Openingsuren Gent, Levertijden magazijn, ..."
                        value="{{ old('days.1.content', $model->getDayForForm(1)) }}"
                    />

                    <x-chief::input.error rule="days.1.content"/>
                </div>

                <!-- day -->
                <div class="space-y-1 form-light">
                    <h2>Maandag</h2>

                    <x-chief::input.label>Eigen tekstje</x-chief::input.label>

                    <x-chief::input.description>
                        Kan ook in mensentaal. Bijv. "Gesloten op Paasmaandag", "Kantoor dicht want Teambuilding 🥳
                    </x-chief::input.description>

                    <x-chief::input.text
                        name="days[1][content]"
                        placeholder="Bijv. Openingsuren Gent, Levertijden magazijn, ..."
                        value="{{ old('days.1.content', $model->getDayForForm(1)) }}"
                    />

                    <x-chief::input.error rule="days.1.content"/>
                </div>


            </div>

            <button class="btn btn-primary mt-4" type="submit">Bewaar aanpassingen</button>
        </form>
    </x-chief::page.grid>
</x-chief::page.template>
