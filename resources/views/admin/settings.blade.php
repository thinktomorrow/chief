<x-chief::page.template title="Instellingen" container="md">
    <x-slot name="header">
        <x-chief::page.header
            :breadcrumbs="[['label' => 'Dashboard', 'url' => route('chief.back.dashboard'), 'icon' => 'home'], 'Instellingen']"
        >
            <x-slot name="actions">
                <x-chief::button form="updateForm" type="submit" variant="blue">Wijzigingen opslaan</x-chief::button>
            </x-slot>
        </x-chief::page.header>
    </x-slot>

    <x-chief::window>
        <form action="{{ route('chief.back.settings.update') }}" id="updateForm" method="POST" role="form">
            @csrf
            @method('put')

            @foreach ($fields as $field)
                {!! $field->render() !!}
            @endforeach
        </form>
    </x-chief::window>
</x-chief::page.template>
