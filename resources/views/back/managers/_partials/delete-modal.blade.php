<?php $managedModelId = str_slug($manager->route('delete')); ?>

<modal id="delete-manager-{{ $managedModelId }}" class="large-modal" title=''>
    <form action="{{ $manager->route('delete') }}" method="POST" id="delete-manager-form-{{ $managedModelId }}" slot>
        @method('DELETE')
        @csrf
        <div v-cloak>
            <h2>Verwijder {{ $manager->details()->title }}</h2>
            <p>Bevestig jouw actie door hieronder de tekst 'DELETE' te typen:</p>
            <div class="input-group stack column-6">
                <input data-delete-confirmation name="deleteconfirmation" placeholder="DELETE" type="text" class="input inset-s" autocomplete="off">
            </div>
        </div>
    </form>

    <div v-cloak slot="modal-action-buttons">
        <button type="button" class="btn btn-link" data-submit-form="delete-manager-form-{{ $managedModelId }}">Ja, verwijder</button>
    </div>
</modal>
