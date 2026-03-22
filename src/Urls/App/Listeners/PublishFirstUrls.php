<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Urls\App\Listeners;

use Illuminate\Database\Eloquent\Model;
use Thinktomorrow\Chief\ManagedModels\Events\ManagedModelPublished;
use Thinktomorrow\Chief\Site\Visitable\Visitable;
use Thinktomorrow\Chief\Urls\Models\LinkStatus;
use Thinktomorrow\Chief\Urls\Models\UrlRecord;

class PublishFirstUrls
{
    public function onManagedModelPublished(ManagedModelPublished $event): void
    {
        $model = $event->modelReference->instance();

        if (! $model instanceof Visitable || ! $model instanceof Model) {
            return;
        }

        $records = UrlRecord::query()
            ->where('model_type', $model->getMorphClass())
            ->where('model_id', $model->getKey())
            ->whereNull('redirect_id')
            ->get();

        if ($records->isEmpty()) {
            return;
        }

        if ($records->contains(fn (UrlRecord $record) => $record->status === LinkStatus::online->value)) {
            return;
        }

        $records
            ->filter(fn (UrlRecord $record) => $record->status === LinkStatus::offline->value)
            ->each(function (UrlRecord $record): void {
                $record->changeStatus(LinkStatus::online);
                $record->save();
            });
    }
}
