<div class="space-y-8">
    <h3>Status beheren</h3>

    @adminCan('publish', $model)
        <div class="space-y-3 prose prose-dark">
            <p>De pagina staat nog in draft.</p>

            <form action="@adminRoute('publish', $model)" method="POST">
                {{ csrf_field() }}

                <button type="submit" class="btn btn-primary">Publiceer deze pagina</button>
            </form>
        </div>
    @endAdminCan

    @adminCan('unpublish', $model)
        <div class="space-y-3 prose prose-dark">
            @if($isAnyLinkOnline)
                <p>De pagina staat online. 👍</p>

                <form action="@adminRoute('unpublish', $model)" method="POST">
                    {{ csrf_field() }}

                    <button type="submit" class="btn btn-error-outline">Haal offline</button>
                </form>
            @else
                <p>De pagina staat gepubliceerd maar zal zonder link nog niet bereikbaar zijn. Voeg hieronder nog een link toe!</p>

                <form action="@adminRoute('unpublish', $model)" method="POST">
                    {{ csrf_field() }}

                    <button type="submit" class="btn btn-warning-outline">Zet terug in draft</button>
                </form>
            @endif
        </div>
    @endAdminCan

    <div data-vue-fields class="flex flex-col items-start space-y-4">
        @adminCan('delete', $model)
            <a v-cloak @click="showModal('delete-manager-<?= $model->id; ?>')" class="cursor-pointer btn btn-error-outline">
                Verwijderen
            </a>
        @endAdminCan

        @adminCan('archive', $model)
            <a v-cloak @click="showModal('archive-manager-<?= $model->id ?>')" class="cursor-pointer btn btn-warning-outline">
                Archiveren
            </a>
        @endAdminCan

        @adminCan('unarchive', $model)
            <a data-submit-form="unarchiveForm-{{ $model->id }}" class="cursor-pointer btn btn-primary-outline">Herstellen</a>

            <form class="hidden" id="unarchiveForm-{{ $model->id }}" action="@adminRoute('unarchive', $model)" method="POST">
                {{ csrf_field() }}
            </form>
        @endAdminCan
    </div>
</div>
