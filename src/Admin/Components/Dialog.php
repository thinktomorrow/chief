<?php

namespace Thinktomorrow\Chief\Admin\Components;

class Dialog
{
    public array $states = [];

    // states
    // Each 'state' is html but can be a livewire component on its own as well 🤯
    // State: id, order, html, visibility, displayType (modal, sidebar, toast?)

    // The order value represent the sequence
    // Track the sequence of the states. Compare it to the sidebar panels. This allows to go back to a previous one

    // addState: new state to the pack
    // showState: show this state and hide all others. WE NEED transition animation here

    // dropState: remove a certain state
    // dropAll: remove all states
    // refreshState: (maybe not necessary) drop a state but re-adds the same state on the same step position
}
