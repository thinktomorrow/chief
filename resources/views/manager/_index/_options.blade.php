@php
    use Thinktomorrow\Chief\ManagedModels\States\State\StatefulContract;
@endphp

@php
    use Thinktomorrow\Chief\ManagedModels\States\State\StateMachine;
@endphp

<div data-sortable-hide-when-sorting class="flex justify-end gap-1">
    @adminCan('edit', $model)
    <a href="{{ $manager->route('edit', $model->getKey()) }}" title="Aanpassen">
        <x-chief::icon-button color="grey" icon="icon-edit" />
    </a>
    @endAdminCan

    @adminCan('preview', $model)
    <a href="@adminRoute('preview', $model)" title="Bekijk op de site" target="_blank" rel="noopener">
        <x-chief::icon-button color="grey" icon="icon-external-link" />
    </a>
    @endAdminCan

    @if ($manager->can('state-update', $model) && $model instanceof StatefulContract)
        <button
            type="button"
            x-on:click="$dispatch('open-dialog', { 'id': 'index-options-{{ $model->getKey() }}' })"
        >
            <x-chief::button>
                <x-chief::icon.more-vertical-circle class="size-5" />
            </x-chief::button>
        </button>

        <x-chief::dialog.dropdown id="index-options-{{ $model->getKey() }}">
            @if ($manager->can('state-update', $model) && $model instanceof StatefulContract)
                @foreach ($model->getStateKeys() as $stateKey)
                    @php
                        $stateConfig = $model->getStateConfig($stateKey);
                        $stateMachine = StateMachine::fromConfig($model, $stateConfig);
                    @endphp

                    @foreach ($stateMachine->getAllowedTransitions() as $transitionKey)
                        @include(
                            'chief::manager.windows.state.transition-options-dropdown-link',
                            [
                                'model' => $model,
                                'transitionKey' => $transitionKey,
                                'stateConfig' => $stateConfig,
                            ]
                        )
                    @endforeach
                @endforeach
            @endif
        </x-chief::dialog.dropdown>
    @endif
</div>
