<div class="w-full mb-8">
    <p class="mb-2">Geen @adminLabel('label') gevonden.</p>
    <p><a href="@adminRoute('index')">Bekijk alle resultaten</a></p>
</div>

@adminCan('create')
    <div class="stack">
        <a href="@adminRoute('create')" class="btn btn-primary inline-flex items-center">
            <span class="mr-2"><svg width="18" height="18"><use xlink:href="#zap"/></svg></span>
            <span>Tijd om er eentje toe te voegen</span>
        </a>
    </div>
@endAdminCan
