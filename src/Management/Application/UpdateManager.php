<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Management\Application;

use Illuminate\Http\Request;
use Thinktomorrow\Chief\Management\Manager;
use Thinktomorrow\Chief\Fields\Validation\FieldValidator;

class UpdateManager
{
    /** @var FieldValidator */
    private $fieldValidator;

    public function __construct(FieldValidator $fieldValidator)
    {
        $this->fieldValidator = $fieldValidator;
    }

    public function handle(Manager $manager, Request $request)
    {
        $manager->guard('update');

        $request = $manager->updateRequest($request);

        $this->fieldValidator->handle($manager->fieldsWithAssistantFields(), $request->all());

        if (method_exists($manager, 'beforeUpdate')) {
            $manager->beforeUpdate($request);
        }

        $manager->saveFields($request);

        if (method_exists($manager, 'afterUpdate')) {
            $manager->afterUpdate($request);
        }
    }
}
