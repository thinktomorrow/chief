<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\TableNew\Actions;

use Illuminate\Contracts\Support\Htmlable;
use Thinktomorrow\Chief\Forms\Concerns\HasComponentRendering;
use Thinktomorrow\Chief\Forms\Concerns\HasCustomAttributes;
use Thinktomorrow\Chief\Forms\Concerns\HasDescription;
use Thinktomorrow\Chief\Forms\Fields\Concerns\HasKey;
use Thinktomorrow\Chief\Forms\Fields\Concerns\HasLabel;
use Thinktomorrow\Chief\Forms\Fields\Concerns\HasLocalizableProperties;
use Thinktomorrow\Chief\Forms\Fields\Concerns\HasModel;
use Thinktomorrow\Chief\Table\Concerns\HasIcon;
use Thinktomorrow\Chief\Table\Concerns\HasView;
use Thinktomorrow\Chief\TableNew\Actions\Concerns\HasEffect;
use Thinktomorrow\Chief\TableNew\Actions\Concerns\HasModal;
use Thinktomorrow\Chief\TableNew\Columns\Concerns\HasLink;
use Thinktomorrow\Chief\TableNew\Columns\Concerns\HasVisibility;

class Action extends \Illuminate\View\Component implements Htmlable
{
    use HasComponentRendering;
    use HasView;
    use HasCustomAttributes;
    use HasKey;
    use HasLabel;
    use HasDescription;
    use HasLocalizableProperties;
    use HasModel;
    use HasIcon;

    use HasVisibility;
    use HasLink;
    use HasEffect;
    use HasModal;

    protected string $view = 'chief-table-new::actions.action';

    public function __construct(string $key)
    {
        $this->key($key);
        $this->label($key);
    }

    public static function make(string|int|null $key)
    {
        return new static((string) $key);
    }

    public function toRowAction(): static
    {
        $action = BulkAction::make($this->key);

        if ($this->label) {
            $action->label($this->label);
        }
        if ($this->description) {
            $action->description($this->description);
        }
        if ($this->effect) {
            $action->effect($this->effect);
        }
        if ($this->modal) {
            $action->modal($this->modal);
        }
        if ($this->link) {
            $action->link($this->link);
        }
        if ($this->model) {
            $action->model($this->model);
        }

        return $action;
    }

    public function toBulkAction(): static
    {
        $action = clone $this;

        $action->view('chief-table-new::actions.bulk-action');

        return $action;
    }


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
}
