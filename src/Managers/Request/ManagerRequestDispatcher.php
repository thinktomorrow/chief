<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Managers\Request;

use Thinktomorrow\Chief\Managers\Register\Registry;

final class ManagerRequestDispatcher
{
    private Registry $registry;

    public function __construct(Registry $registry)
    {
        $this->registry = $registry;
    }

    public function fromRequest(string $method, string $resourceKey, ...$parameters)
    {
        $manager = $this->registry->manager($resourceKey);

        return $manager->$method(request(), ...$parameters);
    }

    public function __call($method, $arguments)
    {
        return $this->fromRequest($method, request()->segment(2), ...$arguments);
    }
}
