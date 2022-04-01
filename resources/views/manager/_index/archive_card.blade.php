@adminCan('archive_index')
    <div class="space-y-6 card">
        <div class="mt-0.5">
            <span class="text-lg display-base display-dark"> Archief </span>
        </div>

        <div>
            @if(Route::currentRouteName() == 'chief.single.archive_index')
                <a href="@adminRoute('index')" class="link link-primary">Ga terug naar overzicht</a>
            @else
                <a href="@adminRoute('archive_index')" class="link link-warning">Bekijk de gearchiveerde items</a>
            @endif
        </div>
    </div>
@endAdminCan
