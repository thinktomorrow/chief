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
        return in_array($transition, $this->allowedTransitions());
    }

    public function apply($transition): void
    {
        if (! $this->can($transition)) {
            throw StateException::invalidTransition($transition, $this->statefulContract->stateOf($this->stateKey), get_class($this));
        }

        $state = $this->transitions[$transition]['to'];

        $this->statefulContract->changeStateOf($this->stateKey, $state);
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
        if (! in_array($state, $this->states)) {
            return false;
        }

        foreach ($this->transitions as $transition) {
            if (! in_array($this->statefulContract->stateOf($this->stateKey), $transition['from'])) {
                continue;
            }

            if ($transition['to'] == $state) {
                return true;
            }
        }

        return false;
    }

    public function allowedTransitions(): array
    {
        $transitions = [];

        foreach ($this->transitions as $transitionKey => $transition) {
            if (false !== array_search($this->statefulContract->stateOf($this->stateKey), $transition['from'])) {
                $transitions[] = $transitionKey;
            }
        }

        return $transitions;
    }

    private function validateTransitions(): void
    {
        foreach ($this->transitions as $transitionKey => $transition) {
            if (! array_key_exists('from', $transition) || ! array_key_exists('to', $transition) || ! is_array($transition['from'])) {
                throw StateException::malformedTransition($transitionKey, get_class($this));
            }

            foreach ($transition['from'] as $fromState) {
                if (! in_array($fromState, $this->states)) {
                    throw StateException::invalidTransitionState($transitionKey, $fromState, get_class($this));
                }
            }

            if (! in_array($transition['to'], $this->states)) {
                throw StateException::invalidTransitionState($transitionKey, $transition['to'], get_class($this));
            }
        }
    }
}
