@php
    $breadcrumb = new \Thinktomorrow\Chief\Admin\Nav\BreadCrumb('Terug naar tags', route('chief.tags.index'));
@endphp

<x-chief::page.template title="Tag aanpassen" container="md">
    <x-slot name="header">
        <x-chief::page.header
            :breadcrumbs="[
                ['label' => 'Dashboard', 'url' => route('chief.back.dashboard'), 'icon' => 'home'],
                ['label' => 'Tags', 'url' => route('chief.tags.index'), 'icon' => 'tags'],
                'Tag aanpassen',
            ]"
        />
    </x-slot>

    <x-chief::window>
        <form id="tagsEditForm" method="POST" action="{{ route('chief.tags.update', $model->id) }}">
            @csrf
            @method('PUT')

            @foreach ($fields as $field)
                {!! $field->render() !!}
            @endforeach

            <div data-slot="submit" class="flex items-start gap-2">
                <x-chief::button type="submit" variant="blue">Bewaar</x-chief::button>

                <x-chief::button
                    x-data
                    x-on:click="$dispatch('open-dialog', { 'id': 'delete-tag-modal-{{ $model->id }}' })"
                    variant="outline-red"
                >
                    <x-chief::icon.delete />
                    <span>Verwijder tag</span>
                </x-chief::button>
            </div>
        </form>
    </x-chief::window>

    @push('portals')
        <x-chief::dialog.modal id="delete-tag-modal-{{ $model->id }}" title="Verwijder deze tag" size="xs">
            <form
                id="delete-tag-modal-form-{{ $model->id }}"
                method="POST"
                action="{{ route('chief.tags.delete', $model->id) }}"
            >
                @csrf
                @method('DELETE')
            </form>

            <div class="prose prose-dark prose-spacing">
                <p>
                    Hiermee verwijder je
                    <b>{{ $model->label }}</b>
                    . Ben je zeker? Als je deze tag verwijdert, verdwijnt deze ook van alle gekoppelde pagina's.
                </p>
            </div>

            <x-slot name="footer">
                <x-chief::dialog.modal.footer>
                    <x-chief::button type="submit" form="delete-tag-modal-form-{{ $model->id }}" variant="red">
                        <x-chief::icon.delete />
                        <span>Verwijder tag</span>
                    </x-chief::button>
                </x-chief::dialog.modal.footer>
            </x-slot>
        </x-chief::dialog.modal>
    @endpush
</x-chief::page.template>
