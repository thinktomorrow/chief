@php
    $breadcrumb = new \Thinktomorrow\Chief\Admin\Nav\BreadCrumb('Terug naar tags', route('chief.tags.index'));
@endphp

<x-chief::page.template title="Tag aanpassen">
    <x-slot name="hero">
        <x-chief::page.hero title="Tag aanpassen" :breadcrumbs="[$breadcrumb]" class="max-w-3xl" />
    </x-slot>

    <x-chief::page.grid class="max-w-3xl">
        <form id="tagsEditForm" method="POST" action="{{ route('chief.tags.update', $model->id) }}" class="card">
            @csrf
            @method('PUT')

            <div class="space-y-4">
                @foreach ($fields as $field)
                    {!! $field->render() !!}
                @endforeach

                <div class="flex justify-between gap-3">
                    <button class="btn btn-primary" type="submit">Bewaar aanpassingen</button>

                    <button
                        type="button"
                        x-data
                        x-on:click="$dispatch('open-dialog', { 'id': 'delete-tag-modal-{{ $model->id }}' })"
                        class="btn btn-grey"
                    >
                        <x-chief::icon-label class="text-grey-500 hover:text-red-500" icon="icon-trash">
                            Verwijder tag
                        </x-chief::icon-label>
                    </button>
                </div>
            </div>
        </form>
    </x-chief::page.grid>

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
                    <x-chief-table::button type="submit" form="delete-tag-modal-form-{{ $model->id }}" variant="red">
                        <x-chief::icon.delete />
                        <span>Verwijder tag</span>
                    </x-chief-table::button>
                </x-chief::dialog.modal.footer>
            </x-slot>
        </x-chief::dialog.modal>
    @endpush
</x-chief::page.template>
