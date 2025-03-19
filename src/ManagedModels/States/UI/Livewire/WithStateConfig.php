<?php

namespace Thinktomorrow\Chief\ManagedModels\States\UI\Livewire;

use Thinktomorrow\Chief\ManagedModels\States\State\StateAdminConfig;
use Thinktomorrow\Chief\ManagedModels\States\State\StateConfig;
use Thinktomorrow\Chief\ManagedModels\States\State\StatefulContract;
use Thinktomorrow\Chief\ManagedModels\States\State\StateMachine;
use Thinktomorrow\Chief\Shared\ModelReferences\ModelReference;

trait WithStateConfig
{
    private ?StatefulContract $model = null;

    private ?StateConfig $config = null;

    private ?StateAdminConfig $adminConfig = null;

    private ?StateMachine $stateMachine = null;

    private function getStateConfig(): StateConfig
    {
        if ($this->config) {
            return $this->config;
        }

        return $this->config = $this->getModel()->getStateConfig($this->stateKey);
    }

    public function hasAdminConfig(): bool
    {
        return $this->getStateConfig() instanceof StateAdminConfig;
    }

    /**
     * Admin config has settings for the admin
     * interface and the state representation.
     */
    private function getStateAdminConfig(): ?StateAdminConfig
    {
        if ($this->adminConfig) {
            return $this->adminConfig;
        }

        if ($this->hasAdminConfig()) {
            return $this->adminConfig = $this->getStateConfig();
        }
    }

    private function getStateMachine(): StateMachine
    {
        if ($this->stateMachine) {
            return $this->stateMachine;
        }

        return $this->stateMachine = StateMachine::fromConfig($this->getModel(), $this->getStateConfig());
    }

    private function getModel(): StatefulContract
    {
        if ($this->model) {
            return $this->model;
        }

        return $this->model = ModelReference::fromString($this->modelReference)->instance();
    }

    private function getFreshModel(): StatefulContract
    {
        $this->model = null;

        return $this->getModel();
    }
}
