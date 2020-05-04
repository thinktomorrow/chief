<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Management\Application;

use Illuminate\Http\Request;
use Thinktomorrow\Chief\FlatReferences\FlatReferenceFactory;
use Thinktomorrow\Chief\Management\Manager;
use Thinktomorrow\Chief\Fields\Validation\FieldValidator;
use Thinktomorrow\Chief\Templates\ApplyTemplate;

class StoreManager
{
    /** @var FieldValidator */
    private $fieldValidator;

    /** @var ApplyTemplate */
    private $applyTemplate;

    public function __construct(FieldValidator $fieldValidator, ApplyTemplate $applyTemplate)
    {
        $this->fieldValidator = $fieldValidator;
        $this->applyTemplate = $applyTemplate;
    }

    public function handle(Manager $manager, Request $request): Manager
    {
        $manager->guard('store');

        $request = $manager->storeRequest($request);

        $this->fieldValidator->handle($manager->createFields(), $request->all());

        if (method_exists($manager, 'beforeStore')) {
            $manager->beforeStore($request);
        }

        $manager->saveCreateFields($request);
        if ($request->filled('template')) {
            $this->applyTemplate->handle(
                FlatReferenceFactory::fromString($request->input('template'))->className(),
                (string) FlatReferenceFactory::fromString($request->input('template'))->id(),
                get_class($manager->existingModel()),
                (string) $manager->existingModel()->id
            );
        }

        if (method_exists($manager, 'afterStore')) {
            $manager->afterStore($request);
        }

        // Since the model doesn't exist yet, it is now created via the save method
        // For the store we return the new manager which is now connected to the created model instance
        return $manager;
    }
}
