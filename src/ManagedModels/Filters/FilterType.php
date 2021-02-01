<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\ManagedModels\Filters;

class FilterType
{
    const HIDDEN = 'hidden';   // hidden input - default sorting or filtering without admin manipulation possible
    const INPUT = 'input';   // oneliner text (input)
    const SELECT = 'select';  // Select options
    const CHECKBOX = 'checkbox';
    const RADIO = 'radio';
}
