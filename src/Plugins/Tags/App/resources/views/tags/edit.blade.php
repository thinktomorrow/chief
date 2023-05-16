@php
    $breadcrumb = new \Thinktomorrow\Chief\Admin\Nav\BreadCrumb('Terug naar tags', route('chief.tags.index'));
@endphp

<x-chief::page.template title="Tag aanpassen">
    <x-slot name="hero">
        <x-chief::page.hero title="Tag aanpassen" :breadcrumbs="[$breadcrumb]" class="max-w-3xl">
            <a
                v-cloak
                @click="showModal('delete-tag-modal-{{ $model->id }}')"
                class="block cursor-pointer"
            >
                <x-chief::icon-label class="text-grey-500 hover:text-red-500">
                    <x-slot name="icon">
                        <svg width="18" height="18"><use xlink:href="#icon-trash"/></svg>
                    </x-slot>
                    Verwijder tag
                </x-chief::icon-label>
            </a>
        </x-chief::page.hero>
    </x-slot>

    <x-chief::page.grid class="max-w-3xl">
        <form id="tagsEditForm" action="{{ route('chief.tags.update', $model->id) }}" method="POST" class="card">
            @csrf
            @method('PUT')

            <div class="space-y-4">
                @foreach($fields as $field)
                    {!! $field->render() !!}
                @endforeach

                <button class="btn btn-primary" type="submit">Bewaar aanpassingen</button>
            </div>
        </form>
    </x-chief::page.grid>

    @push('portals')
        <modal
            id="delete-tag-modal-{{ $model->id }}"
            title="Wil je deze tag verwijderen?"
        >
            <p>
                Als je de tag verwijdert, zal deze ook worden ontkoppeld van alle pagina's.
            </p>

            <form
                id="delete-tag-modal-form-{{ $model->id }}"
                action="{{ route('chief.tags.delete', $model->id) }}"
                method="POST"
                v-cloak
            >
                @csrf
                @method('DELETE')
            </form>

            <div v-cloak slot="modal-action-buttons">
                <button
                    form="delete-tag-modal-form-{{ $model->id }}"
                    type="submit"
                    class="btn btn-error"
                >
                    <x-chief::icon-label icon="icon-trash">
                        Ja, tag verwijderen
                    </x-chief::icon-label>
                </button>
            </div>
        </modal>
    @endpush

</x-chief::page.template>
