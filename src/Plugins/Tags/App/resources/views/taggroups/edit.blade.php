@php
    $breadcrumb = new \Thinktomorrow\Chief\Admin\Nav\BreadCrumb('Terug naar tags', route('chief.tags.index'));
@endphp

<x-chief::page.template title="Tag groep aanpassen">
    <x-slot name="hero">
        <x-chief::page.hero title="Tag groep aanpassen" :breadcrumbs="[$breadcrumb]" class="max-w-3xl" />
    </x-slot>

    <x-chief::page.grid class="max-w-3xl">
        <form
            id="tagGroupsEditForm"
            method="POST"
            action="{{ route('chief.taggroups.update', $model->id) }}"
            class="card"
        >
            @csrf
            @method('PUT')

            <div class="space-y-4">
                @foreach ($fields as $field)
                    {!! $field->render() !!}
                @endforeach

                <div class="mt-4 flex items-center justify-between">
                    <button class="btn btn-primary" type="submit">Bewaar aanpassingen</button>

                    <button
                        type="button"
                        x-data
                        x-on:click="$dispatch('open-dialog', { 'id': 'delete-taggroup-modal-{{ $model->id }}' })"
                        class="btn btn-grey"
                    >
                        <x-chief::icon-label icon="icon-trash" color="grey">Verwijder groep</x-chief::icon-label>
                    </button>
                </div>
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
                    v-cloak
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
                    <button type="submit" form="delete-taggroup-modal-form-{{ $model->id }}" class="btn btn-error">
                        Verwijder tag groep
                    </button>
                </x-slot>
            </x-chief::dialog.modal>
        @endpush
    </x-chief::page.grid>
</x-chief::page.template>
