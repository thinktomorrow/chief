<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\ManagedModels\States\State;

class StateException extends \Exception
{
    public static function invalidState($state, $currentState, string $stateMachineClass): self
    {
        return new self('Transition to state [' . $state . '] is not allowed from state [' . $currentState . '] or state does not exist on ' . $stateMachineClass);
    }

    public static function malformedTransition($transition, string $stateMachineClass): self
    {
        return new self('Transition [' . $transition . '] is malformed on ' . $stateMachineClass . '. It should contain both a [from:array] and [to:string] value.');
    }

    public static function invalidTransitionKey($transition, string $stateMachineClass): self
    {
        return new self('unknown transition [' . $transition . '] on ' . $stateMachineClass);
    }

    public static function invalidTransition($transition, $state, string $stateMachineClass): self
    {
        return new self('Transition [' . $transition . '] cannot be applied from current state [' . $state . '] on ' . $stateMachineClass);
    }

    public static function invalidTransitionState($transition, $state, string $stateMachineClass): self
    {
        return new self('Transition [' . $transition . '] contains a non existing [' . $state . '] on ' . $stateMachineClass);
    }
}
