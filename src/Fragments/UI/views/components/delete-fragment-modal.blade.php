<x-chief::dialog
    id="delete-fragment-{{ str_replace('\\', '', $model->getFragmentId()) }}"
    title="Verwijder dit fragment"
    size="xs"
>
    <form
        id="delete-fragment-form-{{ $model->modelReference()->get() }}"
        method="POST"
        action="{{ route('chief::fragments.delete', [$context->id, $model->getFragmentId()]) }}"
    >
        @method('DELETE')
        @csrf
    </form>

    <div class="prose prose-dark prose-spacing">
        <p>Hiermee verwijder je dit fragment van deze pagina. Ben je zeker?</p>
        <p>Dit zal niet verwijderd worden op eventueel andere pagina's waar dit fragment gebruikt wordt.</p>
    </div>

    <x-slot name="footer">
        <button type="button" x-on:click="open = false" class="btn btn-grey">
            Annuleer
        </button>

        <button
            type="submit"
            form="delete-fragment-form-{{ $model->getFragmentId() }}"
            x-on:click="open = false"
            class="btn btn-error"
        >
            Verwijder fragment
        </button>
    </x-slot>
</x-chief::dialog>
