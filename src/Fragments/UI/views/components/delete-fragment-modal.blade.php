<<<<<<<< HEAD:src/Fragments/UI/views/components/delete-fragment-modal.blade.php
<x-chief::dialog
    id="delete-fragment-{{ str_replace('\\', '', $model->getFragmentId()) }}"
========
<x-chief::dialog.modal
    id="delete-fragment-{{ str_replace('\\', '', $model->modelReference()->get()) }}"
>>>>>>>> @{-1}:src/Fragments/UI/views/components/_partials/delete-fragment-modal.blade.php
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
        <button type="button" x-on:click="close()" class="btn btn-grey">Annuleer</button>

        <button
            type="submit"
<<<<<<<< HEAD:src/Fragments/UI/views/components/delete-fragment-modal.blade.php
            form="delete-fragment-form-{{ $model->getFragmentId() }}"
            x-on:click="open = false"
========
            form="delete-fragment-form-{{ $model->modelReference()->get() }}"
            x-on:click="close()"
>>>>>>>> @{-1}:src/Fragments/UI/views/components/_partials/delete-fragment-modal.blade.php
            class="btn btn-error"
        >
            Verwijder fragment
        </button>
    </x-slot>
</x-chief::dialog.modal>
