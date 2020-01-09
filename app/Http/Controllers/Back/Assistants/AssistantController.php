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

    /**
     * Each assistant route expects 3 dynamics url segment. This is expected to be in the following sequence:
     * key (manager key), id (managed model id) and assistant as the assistant identifier.
     *
     * e.g. assistant-route-call/{key}/{id}/{assistant}
     *
     * @param string $method
     * @param array $parameters
     * @return mixed
     */
    public function __call($method, $parameters)
    {
        $managerKey = $parameters[0];
        $modelId = $parameters[1];

        // If there is no third parameter passed, we assume the assistantKey is given.
        $assistantKey = (!isset($parameters[2])) ? $modelId : $parameters[2];

        $manager = $this->managers->findByKey($managerKey, $modelId);

        return $this->forwardCallToAssistant($manager, $assistantKey, $method);
    }

    private function forwardCallToAssistant(Manager $manager, string $assistantKey, string $method)
    {
        return $manager->assistant($assistantKey)->$method(request());
    }
}
