@adminCan('state-edit', $model)
    <x-chief-form::window
            title="Status"
            :edit-url="$manager->route('state-edit', $model, $stateConfig->getStateKey())"
            :refresh-url="$manager->route('state-window', $model, $stateConfig->getStateKey())"
            tags="status,links"
            class="card"
    >
        {!! $stateConfig->getWindowContent($model, get_defined_vars()) !!}
    </x-chief-form::window>
@endAdminCan
