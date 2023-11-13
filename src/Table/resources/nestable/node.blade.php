@php
    use Thinktomorrow\Chief\Admin\Settings\Homepage;
    use Thinktomorrow\Chief\ManagedModels\States\State\StatefulContract;
    use Thinktomorrow\Chief\Plugins\Tags\App\Taggable\Taggable;

    $model = $node->getModel();

@endphp

<div
    x-data="{}"
    data-sortable-id="{{ $node->getId() }}"
    class="py-3 nested:ptl sortable-item sorting:nested:p-0 sorting:nested:space-y-4"
>
    <div class="flex items-start justify-between gap-4 group">
        <div class="flex items-start gap-1">
            {{-- Sortable handle icon --}}
            <span
                data-sortable-show-when-sorting
                data-sortable-handle
                class="cursor-pointer link link-primary"
                style="margin-left: 0; margin-top: -2px; margin-right: 0.5rem"
            >
                <x-chief::icon-button icon="icon-chevron-up-down"/>
            </span>

            {{-- Arrow icon --}}
            <span
                data-sortable-hide-when-sorting
                class="hidden cursor-pointer link link-black nested:block"
                style="margin-left: -1.75rem; margin-right: 0.5rem; margin-top: 0.2rem;"
            >
                <svg width="20" height="20"><use xlink:href="#icon-arrow-tl-to-br"/></svg>
            </span>

            {{-- Card label --}}
            <div class="flex flex-wrap items-start gap-1 mt-[0.2rem]">
                @adminCan('edit')
                <a
                    href="{{ $manager->route('edit', $node->getId()) }}"
                    title="{{ $model->getPageTitle($model) }}"
                    class="mr-1 font-medium body-dark group-hover:underline"
                >
                    {{ $model->getPageTitle($model) }}
                </a>
                @elseAdminCan
                <span class="mr-1 font-medium body-dark">
                        {{ $model->getPageTitle($model) }}
                    </span>
                @endAdminCan

                @if(Homepage::is($model))
                    <span class="label label-xs label-primary mt-[1px]">Home</span>
                @endif

                @if(
                    $model instanceof StatefulContract
                    && !$model->inOnlineState()
                )
                    <span class="label label-xs label-error mt-[1px]">Offline</span>
                @endif

                @if($model->getNestableNodeLabels())
                    {!! $model->getNestableNodeLabels() !!}
                @endif

                @if ($model instanceof Taggable)
                    <x-dynamic-component
                        component="chief-tags::tags"
                        :tags="$model->getTags()"
                        size="xs"
                        threshold="4"
                    />
                @endif
            </div>
        </div>

        <div data-sortable-hide-when-sorting>
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

                @if (($manager->can('state-update', $model) && $model instanceof StatefulContract) || $manager->can('duplicate', $model))
                    <button
                        x-on:click="Livewire.dispatch('openRowActionsDropdown', {'modelReference': '{{ str_replace('\\','\\\\',$model->modelReference()->get()) }}' })"
                        type="button" id="index-options-{{ $model->id }}">
                        <x-chief::button>
                            <svg class="w-5 h-5">
                                <use xlink:href="#icon-ellipsis-vertical"/>
                            </svg>
                        </x-chief::button>
                    </button>
                @endif

{{--                <x-chief::dropdown trigger="#index-options-{{ $model->id }}">--}}
{{--                    testj--}}
{{--                </x-chief::dropdown>--}}

{{--                    <x-chief::dropdown trigger="#index-options-{{ $model->id }}">--}}
{{--                        @if($manager->can('state-update', $model) && $model instanceof StatefulContract)--}}
{{--                            @foreach ($model->getStateKeys() as $stateKey)--}}
{{--                                @php--}}
{{--                                    $stateConfig = $model->getStateConfig($stateKey);--}}
{{--                                    $stateMachine = StateMachine::fromConfig($model, $stateConfig);--}}
{{--                                @endphp--}}

{{--                                @foreach($stateMachine->getAllowedTransitions() as $transitionKey)--}}
{{--                                    @include('chief::manager.windows.state.transition-options-dropdown-link', [--}}
{{--                                    'model' => $model,--}}
{{--                                    'transitionKey' => $transitionKey,--}}
{{--                                    'stateConfig' => $stateConfig,--}}
{{--                                    ])--}}
{{--                                @endforeach--}}
{{--                            @endforeach--}}
{{--                        @endif--}}

{{--                        @adminCan('duplicate', $model)--}}
{{--                        @include('chief::manager._transitions.index.duplicate')--}}
{{--                        @endAdminCan--}}
{{--                    </x-chief::dropdown>--}}
            </div>
{{--            @include('chief::manager._index._options', ['model' => $model])--}}
        </div>
    </div>

    <div
        data-sortable
        data-sortable-group-id="{{ $node->getId() }}"
        data-sortable-endpoint="{{ $manager->route('sort-index') }}"
        data-sortable-nested-endpoint="{{ $manager->route('move-index') }}"
        class="relative w-full has-nested-items sorting:drop-zone"
    >
        @foreach($node->getChildNodes() as $child)
            @include('chief-table::nestable.node', ['node' => $child])
        @endforeach
    </div>
</div>
