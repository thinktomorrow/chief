<?php

namespace Thinktomorrow\Chief\Management\Application;

use Illuminate\Http\Request;
use Thinktomorrow\Chief\Fields\FieldValidator;
use Thinktomorrow\Chief\Management\ModelManager;

class StoreManager
{
    use StoringAndUpdatingFields;

    public function handle(ModelManager $manager, Request $request): ModelManager
    {
        if( ! $manager->can('store')){
            NotAllowedManagerRoute::store($manager);
        }

        $request = $manager->storeRequest($request);

        app(FieldValidator::class)->validate($manager->fields(), $request);

        if(method_exists($manager, 'beforeStore')){
            $manager->beforeStore($request);
        }

        $this->handleFields($manager, $request);

        // Handle off any custom save methods
        $this->handleCustomSaves($manager, $request);

        if(method_exists($manager, 'afterStore')){
            $manager->afterStore($request);
        }

        // Since the model doesn't exist yet, it is now created via the save method
        // For the store we return the new manager which is now connected to the created model instance
        return $manager;
    }
}