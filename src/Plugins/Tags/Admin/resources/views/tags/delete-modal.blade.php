@php
    $modalId = \Illuminate\Support\Str::random(10);
@endphp

@if('meer dan 0 pagina')
<div data-vue-fields class="space-y-4">
    <div>
        <a
            v-cloak
            @click="showModal('state-modal-<?= $modalId; ?>')"
            class="block cursor-pointer"
        >
            DELETE
        </a>
    </div>

    @push('portals')
        <modal
            id="state-modal-{{ $modalId }}"
            title="Ben je zeker?"
        >
            <form
                id="delete-tag-modal-form-{{ $modalId }}"
                action="DELETEMODELURL"
                method="POST"
                v-cloak
            >
                @csrf
                @method('PUT')
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
        id="delete-tag-modal-form-{{ $modalId }}"
        action="DELETEMODELURL"
        method="POST"
        v-cloak
    >
        @csrf
        @method('PUT')
    </form>

        <div class="relative space-y-6">
            <button
                type="submit"
                class="block w-full text-left cursor-pointer"
            >
                DELETE
            </button>
        </div>
    </form>
@endif
