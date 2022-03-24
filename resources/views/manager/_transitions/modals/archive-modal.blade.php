<modal id="archive-manager-{{ $model->id }}" title="Ben je zeker?" url="{{ $manager->route('archive_modal', $model->id) }}">

    <!-- form -->

    <div v-cloak slot="modal-action-buttons">
        <button type="submit" class="btn btn-warning" form="archive-manager-form-{{ $model->id }}">Archiveren</button>
    </div>
</modal>
