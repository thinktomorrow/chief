<x-chief::dialog.modal
    id="delete-fragment-{{ str_replace('\\', '', $model->modelReference()->get()) }}"
    title="Verwijder dit fragment"
    size="xs"
>
    <form
        id="delete-fragment-form-{{ $model->modelReference()->get() }}"
        method="POST"
        action="{{ $manager->route('fragment-delete', $owner, $model) }}"
    >
        @method('DELETE')
        @csrf
    </form>

    <div class="prose prose-dark prose-spacing">
        <p>Hiermee verwijder je dit fragment van deze pagina. Ben je zeker?</p>
        <p>Dit zal niet verwijderd worden op eventueel andere pagina's waar dit fragment gebruikt wordt.</p>
    </div>

    <x-slot name="footer">
        <button type="button" x-on:click="close()" class="btn btn-grey">Annuleer</button>

        <button
            type="submit"
            form="delete-fragment-form-{{ $model->modelReference()->get() }}"
            x-on:click="close()"
            class="btn btn-error"
        >
            Verwijder fragment
        </button>
    </x-slot>
</x-chief::dialog.modal>
