<x-chief-window title="Archief" class="card">
    @if(Route::currentRouteName() == 'chief.single.archive_index')
        <a href="@adminRoute('index')" class="link link-primary">Overzicht</a>
    @else
        <a href="@adminRoute('archive_index')" class="link link-warning">Bekijk de gearchiveerde items</a>
    @endif
</x-chief-window>
