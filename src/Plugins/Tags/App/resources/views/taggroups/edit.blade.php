@php
    $breadcrumb = new \Thinktomorrow\Chief\Admin\Nav\BreadCrumb('Terug naar tags', route('chief.tags.index'));
@endphp

<x-chief::page.template title="Tag groep aanpassen" container="md">
    <x-slot name="header">
        <x-chief::page.header
            :breadcrumbs="[
                ['label' => 'Dashboard', 'url' => route('chief.back.dashboard'), 'icon' => 'home'],
                ['label' => 'Tags', 'url' => route('chief.tags.index'), 'icon' => 'tags'],
                'Tag groep aanpassen',
            ]"
        />
    </x-slot>

    <x-chief::window>
        <form id="tagGroupsEditForm" method="POST" action="{{ route('chief.taggroups.update', $model->id) }}">
            @csrf
            @method('PUT')

            @foreach ($fields as $field)
                {!! $field->render() !!}
            @endforeach

            <div data-slot="form-group" class="flex items-start gap-2">
                <x-chief::button type="submit" variant="blue">Bewaar</x-chief::button>

                <x-chief::button
                    x-data
                    x-on:click="$dispatch('open-dialog', { 'id': 'delete-taggroup-modal-{{ $model->id }}' })"
                    variant="outline-red"
                >
                    <x-chief::icon.delete />
                    <span>Verwijder</span>
                </x-chief::button>
            </div>
        </form>

        @push('portals')
            <x-chief::dialog.modal
                id="delete-taggroup-modal-{{ $model->id }}"
                title="Verwijder deze tag groep"
                size="xs"
            >
                <form
                    id="delete-taggroup-modal-form-{{ $model->id }}"
                    action="{{ route('chief.taggroups.delete', $model->id) }}"
                    method="POST"
                >
                    @csrf
                    @method('DELETE')
                </form>

                <div class="prose prose-dark prose-spacing">
                    <p>
                        Hiermee verwijder je
                        <b>{{ $model->label }}</b>
                        . Ben je zeker? De tags die onder deze groep horen worden niet verwijderd, maar zullen getoond
                        worden onder de algemene tags.
                    </p>
                </div>

                <x-slot name="footer">
                    <x-chief::dialog.modal.footer>
                        <x-chief::button type="submit" form="delete-taggroup-modal-form-{{ $model->id }}" variant="red">
                            Verwijder tag groep
                        </x-chief::button>
                    </x-chief::dialog.modal.footer>
                </x-slot>
            </x-chief::dialog.modal>
        @endpush
    </x-chief::window>
</x-chief::page.template>
