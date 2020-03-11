<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Management\Application;

use Illuminate\Http\Request;
use Thinktomorrow\Chief\Management\Manager;
use Thinktomorrow\Chief\Fields\Validation\FieldValidator;

class StoreManager
{
    /** @var FieldValidator */
    private $fieldValidator;

    public function __construct(FieldValidator $fieldValidator)
    {
        $this->fieldValidator = $fieldValidator;
    }

    public function handle(Manager $manager, Request $request): Manager
    {
        $manager->guard('store');

        $request = $manager->storeRequest($request);

        $this->fieldValidator->handle($manager->fieldsWithAssistantFields(), $request->all());

        if (method_exists($manager, 'beforeStore')) {
            $manager->beforeStore($request);
        }

        $manager->saveFields($request);

        if (method_exists($manager, 'afterStore')) {
            $manager->afterStore($request);
        }

        // Since the model doesn't exist yet, it is now created via the save method
        // For the store we return the new manager which is now connected to the created model instance
        return $manager;
    }
}
