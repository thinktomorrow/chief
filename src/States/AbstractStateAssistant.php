<?php declare(strict_types=1);

namespace Thinktomorrow\Chief\States;

use Thinktomorrow\Chief\Management\Manager;
use Thinktomorrow\Chief\States\State\StatefulContract;
use Thinktomorrow\Chief\Management\Assistants\Assistant;
use Thinktomorrow\Chief\Management\Exceptions\NotAllowedManagerRoute;

abstract class AbstractStateAssistant
{
    /** @var Manager */
    protected $manager;

    /** @var StatefulContract $model */
    protected $model;

    public function manager(Manager $manager)
    {
        $this->manager  = $manager;
        $this->model    = $manager->existingModel();

        if(!$this->model instanceof StatefulContract){
            throw new \InvalidArgumentException(static::class . ' requires the model to implement the ' . StatefulContract::class);
        }
    }

    abstract public static function key(): string;

    abstract public function route($verb): ?string;

    public function can($verb): bool
    {
        return !is_null($this->route($verb));
    }

    public function guard($verb): Assistant
    {
        if (! $this->can($verb)) {
            NotAllowedManagerRoute::notAllowedVerb($verb, $this->manager);
        }

        return $this;
    }
}
