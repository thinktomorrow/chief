<?php

namespace Thinktomorrow\Chief\App\Http\Controllers\Back\Assistants;

use Illuminate\Http\Request;
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
     * Each assistant route expects 4 dynamics url segments. This is expected to be in the following sequence:
     * - assistant as the assistant identifier,
     * - method as the assistant::method.
     * - manager as the manager key
     * - model as the model id
     *
     * e.g. assistant-route-call/{assistant}/{method}/{manager}/{model}
     *
     * @param Request $request
     * @param string $assistant
     * @param string $method
     * @param string $manager
     * @param null $model
     * @return mixed
     */
    public function view(Request $request, string $assistant, string $method, string $manager, $model = null)
    {
        $manager = $this->managers->findByKey($manager, $model);

        return $this->forwardRequestToAssistant($request, $manager, $assistant, $method);
    }

    public function update(Request $request, string $assistant, string $method, string $manager, $model)
    {
        $manager = $this->managers->findByKey($manager, $model);

        return $this->forwardRequestToAssistant($request, $manager, $assistant, $method);
    }

    private function forwardRequestToAssistant(Request $request, Manager $manager, string $assistantKey, string $method)
    {
        // If the given method is not related to this route, we'll abort to make sure no unintented method calls are being made
        if (is_null($manager->assistant($assistantKey)->route($method))) {
            throw new \InvalidArgumentException("Assistant $assistantKey does not allow method $method to be called via route. Consider adding this method as route key.");
        }

        return $manager->assistant($assistantKey)->$method($request);
    }
}
