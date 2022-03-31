@adminCan('archive_index')
    <div class="card">
        <div class="w-full space-x-1 mt-0.5">
            <span class="text-lg display-base display-dark">
                Archief
            </span>
        </div>

        @if(Route::currentRouteName() == 'chief.single.archive_index')
            <a href="@adminRoute('index')" class="link link-primary">Ga terug naar overzicht</a>
        @else
            <a href="@adminRoute('archive_index')" class="link link-warning">Bekijk de gearchiveerde items</a>
        @endif
    </div>
@endAdminCan
