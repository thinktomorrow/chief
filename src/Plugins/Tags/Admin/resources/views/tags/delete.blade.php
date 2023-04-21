@php
    $modalId = \Illuminate\Support\Str::random(10);
@endphp

@if($tag->getUsages() > 0)
<div data-vue-fields class="space-y-4">
    <div>
        <a
            v-cloak
            @click="showModal('state-modal-<?= $modalId; ?>')"
            class="block cursor-pointer"
        >
            <x-chief::icon-button icon="icon-trash" color="grey" class="bg-white shadow-none text-grey-500"/>
        </a>
    </div>

    @push('portals')
        <modal
            id="state-modal-{{ $modalId }}"
            title="Verwijder tag {{ $tag->getLabel() }}"
        >
            <p>
                Als je de tag verwijdert, zal deze ook worden ontkoppeld van alle pagina's.
            </p>

            <form
                id="delete-tag-modal-form-{{ $modalId }}"
                action="{{ route('chief.tags.delete', $tag->getTagId()) }}"
                method="POST"
                v-cloak
            >
                @csrf
                @method('DELETE')
            </form>

            <div v-cloak slot="modal-action-buttons">
                <button
                    form="delete-tag-modal-form-{{ $modalId }}"
                    type="submit"
                    class="btn btn-error"
                >
                    DELETE
                </button>
            </div>
        </modal>
    @endpush
</div>
@else
    <form
        id="delete-tag-form-{{ $tag->getTagId() }}"
        action="{{ route('chief.tags.delete', $tag->getTagId()) }}"
        method="POST"
        v-cloak
    >
        @csrf
        @method('DELETE')
    </form>

        <div class="relative space-y-6">
            <button
                type="submit"
                form="delete-tag-form-{{ $tag->getTagId() }}"
                class="block w-full text-left cursor-pointer"
            >
                <x-chief::icon-button icon="icon-trash" color="grey" class="bg-white shadow-none text-grey-500"/>
            </button>
        </div>
    </form>
@endif
