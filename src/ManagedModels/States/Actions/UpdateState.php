<?php

namespace Thinktomorrow\Chief\ManagedModels\States\Actions;

use Thinktomorrow\Chief\Forms\Fields;
use Thinktomorrow\Chief\Forms\Fields\Validation\FieldValidator;
use Thinktomorrow\Chief\ManagedModels\Events\PageChanged;
use Thinktomorrow\Chief\ManagedModels\States\State\StateAdminConfig;
use Thinktomorrow\Chief\ManagedModels\States\State\StatefulContract;
use Thinktomorrow\Chief\ManagedModels\States\State\StateMachine;
use Thinktomorrow\Chief\Managers\Register\Registry;
use Thinktomorrow\Chief\Resource\Resource;
use Thinktomorrow\Chief\Shared\ModelReferences\ModelReference;

class UpdateState
{
    private FieldValidator $fieldValidator;

    private Registry $registry;

    public function __construct(FieldValidator $fieldValidator, Registry $registry)
    {

        $this->fieldValidator = $fieldValidator;
        $this->registry = $registry;
    }

    public function handle(string $resourceKey, ModelReference $modelReference, string $stateKey, string $transitionKey, array $data = [], array $files = []): void
    {
        $resource = $this->registry->resource($resourceKey);
        $model = $modelReference->instance();

        if (! $model instanceof StatefulContract) {
            throw new \Exception('Model does not implement StatefulContract');
        }

        $stateConfig = $model->getStateConfig($stateKey);

        if ($stateConfig instanceof StateAdminConfig) {
            $this->saveTransitionFields($resource, $model, $stateConfig->getTransitionFields($transitionKey, $model), $data, $files);
        }

        $machine = StateMachine::fromConfig($model, $stateConfig);
        $machine->apply($transitionKey);

        $model->save();

        $stateConfig->emitEvent($model, $transitionKey, $data);

        event(new PageChanged($model->modelReference()));
    }

    private function saveTransitionFields(Resource $resource, $model, iterable $fields, array $data, array $files)
    {
        $fields = Fields::make($fields);

        if ($fields->isEmpty()) {
            return;
        }

        $this->fieldValidator->handle($fields, $data);

        app($resource->getSaveFieldsClass())->save($model, $fields, $data, $files);
    }
}
