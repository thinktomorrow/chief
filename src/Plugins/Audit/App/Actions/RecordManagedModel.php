<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Plugins\Audit\App\Actions;

use Illuminate\Database\Eloquent\Model;
use Thinktomorrow\Chief\ManagedModels\Events\ChiefActionCompleted;
use Thinktomorrow\Chief\ManagedModels\Events\ManagedModelCreated;
use Thinktomorrow\Chief\ManagedModels\Events\ManagedModelUpdated;

final class RecordManagedModel
{
    public function created(ManagedModelCreated $event): void
    {
        $this->record('created', $event->modelReference->instance());
    }

    public function updated(ManagedModelUpdated $event): void
    {
        $this->record('updated', $event->modelReference->instance());
    }

    private function record(string $action, Model $model): void
    {
        app(RecordChiefAction::class)->handle(ChiefActionCompleted::forModels($action, $model));
    }
}
