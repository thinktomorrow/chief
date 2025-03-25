<div class="prose prose-dark prose-spacing">
    <p>
        Je staat op het punt om
        <b>{{ $resource->getPageTitle($model) }}</b>
        te archiveren.
    </p>

    @if (contract($model, \Thinktomorrow\Chief\Site\Visitable\Visitable::class))
        <p>
            Opgelet, dit haalt deze pagina van de site en bezoekers krijgen een 404-pagina te zien. Je kan ook kiezen om
            door te linken naar een andere pagina:
        </p>

        <x-chief::form.input.select wire:model="form.redirect_id" id="redirectId" name="redirect_id" class="my-4">
            @foreach ($targetModels as $targetModelGroup)
                <option value="">---</option>
                <optgroup label="{{ $targetModelGroup['label'] }}">
                    @foreach ($targetModelGroup['options'] as $targetModel)
                        <option value="{{ $targetModel['value'] }}">{{ $targetModel['label'] }}</option>
                    @endforeach
                </optgroup>
            @endforeach
        </x-chief::form.input.select>
    @else
        <p>Archiveren haalt de {{ $resource->getPageTitle($model) }} onmiddellijk van de site.</p>
    @endif
</div>
