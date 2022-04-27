<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\ManagedModels\States\State;

class StateMachine
{
    private array $states;
    private array $transitions;
    private StatefulContract $statefulContract;
    private string $stateKey;

    final public function __construct(StatefulContract $statefulContract, string $stateKey, array $states, array $transitions)
    {
        $this->statefulContract = $statefulContract;
        $this->states = $states;
        $this->transitions = $transitions;
        $this->stateKey = $stateKey;

        $this->validateTransitions();
    }

    public static function fromConfig(StatefulContract $statefulContract, StateConfig $stateConfig): static
    {
        return new static(
            $statefulContract,
            $stateConfig->getStateKey(),
            $stateConfig->getStates(),
            $stateConfig->getTransitions()
        );
    }

    public function can($transition): bool
    {
        if (!in_array($transition, $this->getAllowedTransitions())) {
            return false;
        }

        if (!in_array($this->statefulContract->getState($this->stateKey), $this->transitions[$transition]['from'])) {
            return false;
        }

        return true;
    }

    public function assertNewState($state)
    {
        if (!$this->canTransitionTo($state)) {
            throw StateException::invalidState($state, $this->statefulContract->getState($this->stateKey));
        }
    }

    public function getAllowedTransitions(): array
    {
        $transitions = [];

        foreach ($this->transitions as $transitionKey => $transition) {
            if (in_array($this->statefulContract->getState($this->stateKey), $transition['from'])) {
                $transitions[] = $transitionKey;
            }
        }

        return $transitions;
    }

    public function apply($transition): void
    {
        if (!$this->can($transition)) {
            throw StateException::invalidTransition($transition, $this->statefulContract->getState($this->stateKey)->getValueAsString());
        }

        $state = $this->transitions[$transition]['to'];

        $this->statefulContract->changeState($this->stateKey, $state);
    }

    /**
     * Verify the new state is valid.
     *
     * @param $state
     *
     * @return bool
     */
    public function canTransitionTo($state)
    {
        if (!in_array($state, $this->states)) {
            return false;
        }

        foreach ($this->transitions as $transition) {
            if (!in_array($this->statefulContract->getState($this->stateKey), $transition['from'])) {
                continue;
            }

            if ($transition['to'] == $state) {
                return true;
            }
        }

        return false;
    }

    private function validateTransitions(): void
    {
        foreach ($this->transitions as $transitionKey => $transition) {
            if (!isset($transition['from']) || !isset($transition['to']) || !is_array($transition['from'])) {
                throw StateException::malformedTransition($transitionKey);
            }

            foreach ($transition['from'] as $fromState) {
                if (!$fromState instanceof State) {
                    throw StateException::transitionStateIsNotAsStateInstance($transitionKey);
                }
                if (!in_array($fromState, $this->states)) {
                    throw StateException::invalidTransitionState($transitionKey, $fromState->getValueAsString());
                }
            }

            if (!$transition['to'] instanceof State) {
                throw StateException::transitionStateIsNotAsStateInstance($transitionKey);
            }

            if (!in_array($transition['to'], $this->states)) {
                throw StateException::invalidTransitionState($transitionKey, $transition['to']);
            }
        }
    }
}
