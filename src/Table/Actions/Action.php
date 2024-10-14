<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Table\Actions;

use Illuminate\Contracts\Support\Htmlable;
use Thinktomorrow\Chief\Forms\Concerns\HasComponentRendering;
use Thinktomorrow\Chief\Forms\Concerns\HasCustomAttributes;
use Thinktomorrow\Chief\Forms\Concerns\HasDescription;
use Thinktomorrow\Chief\Forms\Fields\Concerns\HasKey;
use Thinktomorrow\Chief\Forms\Fields\Concerns\HasLabel;
use Thinktomorrow\Chief\Forms\Fields\Concerns\HasLocalizableProperties;
use Thinktomorrow\Chief\Forms\Fields\Concerns\HasModel;
use Thinktomorrow\Chief\Table\Actions\Concerns\HasDialog;
use Thinktomorrow\Chief\Table\Actions\Concerns\HasEffect;
use Thinktomorrow\Chief\Table\Actions\Concerns\HasNotification;
use Thinktomorrow\Chief\Table\Actions\Concerns\HasRedirectOnSuccess;
use Thinktomorrow\Chief\Table\Actions\Concerns\HasRefresh;
use Thinktomorrow\Chief\Table\Columns\Concerns\HasIcon;
use Thinktomorrow\Chief\Table\Columns\Concerns\HasLink;
use Thinktomorrow\Chief\Table\Columns\Concerns\HasView;
use Thinktomorrow\Chief\Table\Columns\Concerns\HasVisibility;

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
    use HasDialog;
    use HasNotification;
    use HasRefresh;
    use HasRedirectOnSuccess;

    protected string $view = 'chief-table::actions.action';

    public function __construct(string $key)
    {
        $this->key($key);
        $this->label($key);
    }

    public static function make(string|int|null $key)
    {
        return new static((string) $key);
    }

    public function toRowAction(): RowAction
    {
        return $this->replicateActionAs(RowAction::class);
    }

    public function toBulkAction(): BulkAction
    {
        return $this->replicateActionAs(BulkAction::class);
    }

    private function replicateActionAs(string $class): Action|BulkAction|RowAction
    {
        $action = $class::make($this->key);

        $action->label($this->label);

        if ($this->description) {
            $action->description($this->description);
        }
        if ($this->effect) {
            $action->effect($this->effect);
        }
        if ($this->dialog) {
            $action->dialog($this->dialog);
        }
        if ($this->link) {
            $action->link($this->link);
        }
        if ($this->notificationOnSuccess) {
            $action->notifyOnSuccess($this->notificationOnSuccess);
        }
        if ($this->notificationOnFailure) {
            $action->notifyOnFailure($this->notificationOnFailure);
        }
        if ($this->refreshTable) {
            $action->refreshTable();
        }
        if ($this->model) {
            $action->model($this->model);
        }

        if ($this->prependIcon) {
            $action->prependIcon($this->prependIcon);
        }

        if ($this->appendIcon) {
            $action->appendIcon($this->appendIcon);
        }

        if($this->redirectOnSuccess) {
            $action->redirectOnSuccess($this->redirectOnSuccess);
        }

        $action->visible($this->visible);

        return $action;
    }
}
