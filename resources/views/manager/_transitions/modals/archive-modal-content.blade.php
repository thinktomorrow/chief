<form
        action="@adminRoute('archive', $model)"
        method="POST"
        id="archive-manager-form-{{ $model->id }}"
>
    @csrf

    <h2 class="h2 display-dark">Archiveer: {{ $resource->getPageTitle($model) }}</h2>

    @if(contract($model, \Thinktomorrow\Chief\Site\Visitable\Visitable::class))
        <p>
            Opgelet, dit haalt deze pagina van de site en bezoekers krijgen een 404-pagina te zien.
            Je kan ook kiezen om door te linken naar een andere pagina:
        </p>

        <select class="mt-3" name="redirect_id" id="redirectId">
            @foreach($targetModels as $targetModelGroup)
                <option value="">---</option>
                <optgroup label="{{ $targetModelGroup['group'] }}">
                    @foreach($targetModelGroup['values'] as $targetModel)
                        <option value="{{ $targetModel['id'] }}">{{ $targetModel['label'] }}</option>
                    @endforeach
                </optgroup>
            @endforeach
        </select>

    @else
        <p>Archiveren haalt de {{ $resource->getPageTitle($model) }} onmiddellijk van de site.</p>
    @endif
</form>