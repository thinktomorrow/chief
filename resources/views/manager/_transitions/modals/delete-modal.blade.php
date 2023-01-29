<modal id="delete-manager-{{ $model->id }}" title="Ben je zeker?">
    <form
        action="@adminRoute('delete', $model)"
        method="POST"
        id="delete-manager-form-{{ $model->id }}"
        v-cloak
    >
        @method('DELETE')
        @csrf

        <h2 class="h2 h1-dark">Verwijder: {{ $resource->getPageTitle($model) }}</h2>

        <p>Bevestig jouw actie door hieronder de tekst 'DELETE' te typen:</p>

        <input
            data-delete-confirmation
            name="deleteconfirmation"
            placeholder="DELETE"
            type="text"
            autocomplete="off"
            class="w-full mt-3"
        >
    </form>

    <div v-cloak slot="modal-action-buttons">
        <button form="delete-manager-form-{{ $model->id }}" type="submit" class="btn btn-error">
            Verwijder
        </button>
    </div>
</modal>
