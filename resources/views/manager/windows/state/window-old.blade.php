@adminCan('state-edit', $model)
<x-chief-form::window
    title="{{ $stateConfig->getEditTitle($model) }}"
    :edit-url="isset($allowedToEdit) ? $manager->route('state-edit', $model, $stateConfig->getStateKey()) : null"
    :refresh-url="$manager->route('state-window', $model, $stateConfig->getStateKey())"
    tags="status,links"
>
    {!! $stateConfig->getWindowContent($model, get_defined_vars()) !!}
</x-chief-form::window>
@endAdminCan
