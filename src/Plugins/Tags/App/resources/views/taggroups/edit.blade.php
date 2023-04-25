@php
    $breadcrumb = new \Thinktomorrow\Chief\Admin\Nav\BreadCrumb('Terug naar tags', route('chief.tags.index'));
@endphp

<x-chief::page.template title="Tag groep aanpassen">
    <x-slot name="hero">
        <x-chief::page.hero title="Tag groep aanpassen" :breadcrumbs="[$breadcrumb]" class="max-w-3xl">

        </x-chief::page.hero>
    </x-slot>

    <x-chief::page.grid class="max-w-3xl">
        <form id="tagGroupsEditForm" action="{{ route('chief.taggroups.update', $model->id) }}" method="POST" class="card">
            @csrf
            @method('PUT')

            <div class="space-y-4">
                @foreach($fields as $field)
                    {!! $field->render() !!}
                @endforeach

                <div class="flex justify-between items-center mt-4">
                    <button class="btn btn-primary" type="submit">Bewaar aanpassingen</button>

                    <a
                        v-cloak
                        @click="showModal('delete-taggroup-modal-{{ $model->id }}')"
                        class="block cursor-pointer"
                    >
                        <x-chief::icon-label icon="icon-trash" color="grey" class="bg-white shadow-none text-grey-500">
                            Verwijder groep
                        </x-chief::icon-label>
                    </a>
                </div>
            </div>

        </form>


        @push('portals')
            <modal
                id="delete-taggroup-modal-{{ $model->id }}"
                title="Wil je deze groep verwijderen?"
            >
                <p>

                </p>

                <form
                    id="delete-taggroup-modal-form-{{ $model->id }}"
                    action="{{ route('chief.taggroups.delete', $model->id) }}"
                    method="POST"
                    v-cloak
                >
                    @csrf
                    @method('DELETE')
                </form>

                <div v-cloak slot="modal-action-buttons">
                    <button
                        form="delete-taggroup-modal-form-{{ $model->id }}"
                        type="submit"
                        class="btn btn-error"
                    >
                        <x-chief::icon-label icon="icon-trash">
                            Ja, groep verwijderen
                        </x-chief::icon-label>
                    </button>
                </div>
            </modal>
        @endpush

    </x-chief::page.grid>
</x-chief::page.template>
