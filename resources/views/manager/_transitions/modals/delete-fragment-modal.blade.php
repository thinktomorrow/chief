<modal id="delete-fragment-{{ str_replace('\\','',$model->modelReference()->get()) }}" title="Ben je zeker?">
    <form
        action="{{ $manager->route('fragment-delete', $owner, $model) }}"
        method="POST"
        id="delete-fragment-form-{{ $model->modelReference()->get() }}"
        v-cloak
    >
        @method('DELETE')
        @csrf

        <p>Hiermee verwijder je dit fragment van deze pagina. Ben je zeker?</p>
        <p>Dit zal niet verwijderd worden op eventueel andere pagina's waar dit fragment gebruikt wordt.</p>

    </form>

    <div v-cloak slot="modal-action-buttons">
        <button form="delete-fragment-form-{{ $model->modelReference()->get() }}" type="submit" class="btn btn-error">
            Verwijderen
        </button>
    </div>
</modal>
