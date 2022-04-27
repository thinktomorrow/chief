<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\ManagedModels\States\State;

class StateException extends \Exception
{
    public static function invalidState($state, $currentState)
    {
        return new self('Transition to state ['.$state.'] is not allowed from state ['.$currentState.'] or state does not exist');
    }

    public static function malformedTransition($transition)
    {
        return new self('Transition ['.$transition.'] is malformed. It should contain both a [from:array] and [to:string] value.');
    }

//    public static function invalidTransitionKey($transition)
//    {
//        return new self('unknown transition ['.$transition.'].');
//    }

    public static function invalidTransition($transition, $state)
    {
        return new self('Transition ['.$transition.'] cannot be applied from current state ['.$state.'].');
    }

    public static function invalidTransitionState($transition, $state)
    {
        return new self('Transition ['.$transition.'] contains a non existing ['.$state.'].');
    }

    public static function transitionStateIsNotAsStateInstance($transition)
    {
        return new self('Transition ['.$transition.'] contains a state that does not implements the State interface.');
    }
}
