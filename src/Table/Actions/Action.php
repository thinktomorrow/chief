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
use Thinktomorrow\Chief\Table\Actions\Concerns\CloseDialog;
use Thinktomorrow\Chief\Table\Actions\Concerns\HasDialog;
use Thinktomorrow\Chief\Table\Actions\Concerns\HasEffect;
use Thinktomorrow\Chief\Table\Actions\Concerns\HasNotification;
use Thinktomorrow\Chief\Table\Actions\Concerns\HasOrdinalLevel;
use Thinktomorrow\Chief\Table\Actions\Concerns\HasRedirectOnSuccess;
use Thinktomorrow\Chief\Table\Actions\Concerns\HasRefresh;
use Thinktomorrow\Chief\Table\Actions\Concerns\HasWhenCondition;
use Thinktomorrow\Chief\Table\Columns\Concerns\HasIcon;
use Thinktomorrow\Chief\Table\Columns\Concerns\HasLink;
use Thinktomorrow\Chief\Table\Columns\Concerns\HasVariant;

class Action extends \Illuminate\View\Component implements Htmlable
{
    use CloseDialog;
    use HasComponentRendering;
    use HasCustomAttributes;
    use HasDescription;
    use HasDialog;
    use HasEffect;
    use HasIcon;
    use HasKey;
    use HasLabel;
    use HasLink;
    use HasLocalizableProperties;
    use HasModel;
    use HasNotification;
    use HasOrdinalLevel;
    use HasRedirectOnSuccess;
    use HasRefresh;
    use HasVariant;
    use HasWhenCondition;

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
        if ($this->dialogResolver) {
            $action->dialog($this->dialogResolver);
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
        if ($this->redirectOnSuccess) {
            $action->redirectOnSuccess($this->redirectOnSuccess);
        }
        if ($this->hasWhen()) {
            $action->when($this->when);
        }

        $action->variant($this->variant);
        $action->ordinalLevel($this->ordinalLevel);
        $action->closeDialog($this->closeDialog);

        return $action;
    }
}
