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

    @if (
        $manager->can('state-update', $model)
        && $model instanceof \Thinktomorrow\Chief\ManagedModels\States\State\StatefulContract
        || $manager->can('duplicate', $model)
    )
        <options-dropdown>
            <div v-cloak class="dropdown-content">
                @if($manager->can('state-update', $model) && $model instanceof \Thinktomorrow\Chief\ManagedModels\States\State\StatefulContract)
                    @foreach ($model->getStateKeys() as $stateKey)
                        @php
                            $stateConfig = $model->getStateConfig($stateKey);
                            $stateMachine = \Thinktomorrow\Chief\ManagedModels\States\State\StateMachine::fromConfig($model, $stateConfig);
                        @endphp

                        @foreach($stateMachine->getAllowedTransitions() as $transitionKey)
                            @include('chief::manager.windows.state.transition-options-dropdown-link', [
                                'model' => $model,
                                'transitionKey' => $transitionKey,
                                'stateConfig' => $stateConfig,
                            ])
                        @endforeach
                    @endforeach
                @endif

                @adminCan('duplicate', $model)
                    @include('chief::manager._transitions.index.duplicate')
                @endAdminCan
            </div>
        </options-dropdown>
    @endif
</div>
