<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\Site\Urls\Application;

use Thinktomorrow\Chief\ManagedModels\Events\ManagedModelPutOffline;
use Thinktomorrow\Chief\ManagedModels\Events\ManagedModelPutOnline;
use Thinktomorrow\Chief\Site\Urls\UrlRecord;
use Thinktomorrow\Chief\Site\Urls\UrlStatus;

class UpdateUrlStatus
{
    public function onManagedModelPutOnline(ManagedModelPutOnline $event): void
    {
        UrlRecord::where('model_type', $event->modelReference->shortClassName())
            ->where('model_id', $event->modelReference->id())
            ->where('status', UrlStatus::offline->value)
            ->update(['status' => UrlStatus::online->value]);
    }

    public function onManagedModelPutOffline(ManagedModelPutOffline $event): void
    {
        UrlRecord::where('model_type', $event->modelReference->shortClassName())
            ->where('model_id', $event->modelReference->id())
            ->where('status', UrlStatus::online->value)
            ->update(['status' => UrlStatus::offline->value]);
    }
}
