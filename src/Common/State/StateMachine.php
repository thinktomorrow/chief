<?php

namespace Chief\Common\State;

abstract class StateMachine
{
    /**
     * States and transitions should be set on the specific state Machine.
     *
     * @var array
     */
    protected $states = [];
    protected $transitions = [];

    /**
     * @var StatefulContract
     */
    protected $statefulContract;

    public function __construct(StatefulContract $statefulContract)
    {
        // TODO: add event dispatcher cause here we want to add loads of events no?
        // NO! WE SHOULD BETTER TO THIS ON THE AGGREGATE
        $this->statefulContract = $statefulContract;

        $this->validateTransitions();
    }

    public function apply($transition)
    {
        // Check valid transition request
        if (!array_key_exists($transition, $this->transitions)) {
            throw StateException::invalidTransitionKey($transition, $this);
        }

        if (!in_array($this->statefulContract->state(), $this->transitions[$transition]['from'])) {
            throw StateException::invalidTransition($transition, $this->statefulContract->state(), $this);
        }

        $state = $this->transitions[$transition]['to'];

        $this->statefulContract->changeState($state);
    }

    /**
     * assert the integrity of the new state.
     *
     * @param StatefulContract $statefulContract
     * @param $state
     *
     * @throws StateException
     */
    public static function assertNewState(StatefulContract $statefulContract, $state)
    {
        $machine = new static($statefulContract);

        if (!$machine->canTransitionTo($state)) {
            throw StateException::invalidState($state, $statefulContract->state(), $machine);
        }
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
            if (!in_array($this->statefulContract->state(), $transition['from'])) {
                continue;
            }

            if ($transition['to'] == $state) {
                return true;
            }
        }

        return false;
    }

    private function validateTransitions()
    {
        foreach ($this->transitions as $transitionKey => $transition) {
            if (!isset($transition['from']) || !isset($transition['to']) || !is_array($transition['from'])) {
                throw StateException::malformedTransition($transitionKey, $this);
            }

            foreach ($transition['from'] as $fromState) {
                if (!in_array($fromState, $this->states)) {
                    throw StateException::invalidTransitionState($transitionKey, $fromState, $this);
                }
            }

            if (!in_array($transition['to'], $this->states)) {
                throw StateException::invalidTransitionState($transitionKey, $transition['to'], $this);
            }
        }
    }
}
