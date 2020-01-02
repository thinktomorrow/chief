<?php

namespace Thinktomorrow\Chief\App\Http\Controllers\Back\Assistants;

use Thinktomorrow\Chief\Management\Manager;
use Thinktomorrow\Chief\Management\Managers;
use Thinktomorrow\Chief\App\Http\Controllers\Controller;

class AssistantController extends Controller
{
    /** @var Managers */
    private $managers;

    public function __construct(Managers $managers)
    {
        $this->managers = $managers;
    }

    public function __call($method, $parameters)
    {
        $managerKey = $parameters[0];
        $modelId = $parameters[1];
        $assistantKey = $parameters[2];

        $manager = $this->managers->findByKey($managerKey, $modelId);

        return $this->forwardCallToAssistant($manager, $assistantKey, $method);
    }

    private function forwardCallToAssistant(Manager $manager, string $assistantKey, string $method)
    {
        return $manager->assistant($assistantKey)->$method(request());
    }
}
