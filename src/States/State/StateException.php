<?php

namespace Thinktomorrow\Chief\States\State;

class StateException extends \Exception
{
    public static function invalidState($state, $currentState, $stateMachine)
    {
        return new self('Transition to state ['.$state.'] is not allowed from state ['.$currentState.'] or state does not exist on '.get_class($stateMachine));
    }

    public static function malformedTransition($transition, $stateMachine)
    {
        return new self('Transition ['.$transition.'] is malformed on '.get_class($stateMachine).'. It should contain both a [from:array] and [to:string] value.');
    }

    public static function invalidTransitionKey($transition, $stateMachine)
    {
        return new self('unknown transition ['.$transition.'] on '.get_class($stateMachine));
    }

    public static function invalidTransition($transition, $state, $stateMachine)
    {
        return new self('Transition ['.$transition.'] cannot be applied from current state ['.$state.'] on '.get_class($stateMachine));
    }

    public static function invalidTransitionState($transition, $state, $stateMachine)
    {
        return new self('Transition ['.$transition.'] contains a non existing ['.$state.'] on '.get_class($stateMachine));
    }
}
