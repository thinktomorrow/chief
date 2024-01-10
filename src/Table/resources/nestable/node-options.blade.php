@php use Thinktomorrow\Chief\ManagedModels\States\State\StatefulContract; @endphp
@php use Thinktomorrow\Chief\ManagedModels\States\State\StateMachine; @endphp
<div data-sortable-hide-when-sorting class="flex justify-end gap-1">
    @adminCan('edit', $model)
    <a href="{{ $manager->route('edit', $model->getKey()) }}" title="Aanpassen">
        <x-chief::icon-button color="grey" icon="icon-edit"/>
    </a>
    @endAdminCan

    @adminCan('preview', $model)
    <a href="@adminRoute('preview', $model)" title="Bekijk op de site" target="_blank" rel="noopener">
        <x-chief::icon-button color="grey" icon="icon-external-link"/>
    </a>
    @endAdminCan

</div>
