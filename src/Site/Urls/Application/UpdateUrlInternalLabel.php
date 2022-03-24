<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Site\Urls\Application;

use Thinktomorrow\Chief\ManagedModels\Events\ManagedModelUpdated;
use Thinktomorrow\Chief\Managers\Register\Registry;
use Thinktomorrow\Chief\Site\Urls\UrlRecord;

class UpdateUrlInternalLabel
{
    private Registry $registry;

    public function __construct(Registry $registry)
    {
        $this->registry = $registry;
    }

    public function onManagedModelUpdated(ManagedModelUpdated $event): void
    {
        $label = $this->registry->findResourceByModel($event->modelReference->className())->getPageTitle($event->modelReference->instance());

        UrlRecord::where('model_type', $event->modelReference->shortClassName())
            ->where('model_id', $event->modelReference->id())
            ->where(function ($query) use ($label) {
                $query->where('internal_label', '<>', $label)
                    ->orWhereNull('internal_label')
                ;
            })
            ->update(['internal_label' => $label])
        ;
    }
}
