<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\TableNew\Actions;

use Thinktomorrow\Chief\Forms\Fields\Concerns\HasModel;
use Thinktomorrow\Chief\TableNew\Columns\Concerns\HasLink;

class RowAction extends Action
{
    use HasModel;
    use HasLink;

    // icon('name')

    // link()

    // ->apply(payload)

    // BULKACTION
    // ->apply(payload)

    // Confirmation
    // content of this?
    // Icon
    // Route
    // or Class-Method
    // or inline, callable

    // isItemSelectable(): bool on table

    // withModal() -> Form::make()...

    // Preset actions
    // DuplicateAction
    // EditAction
    // CreateAction

    public function __construct(string $key)
    {
        $this->key($key);
    }

    public static function make(string $key): static
    {
        return new static($key);
    }
}
