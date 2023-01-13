<x-chief-window class="card">
    @if(isset($is_archive_index) && $is_archive_index)
        <a href="@adminRoute('index')" class="link link-primary">Terug naar het overzicht</a>
    @else
        <a href="@adminRoute('archive_index')" class="link link-warning">Bekijk de gearchiveerde items</a>
    @endif
</x-chief-window>
