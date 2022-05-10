<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\ManagedModels\Listeners;

use Thinktomorrow\Chief\Site\Urls\UrlRecord;
use Thinktomorrow\Chief\ManagedModels\Events\ManagedModelArchived;

class PropagateArchivedUrl
{
    public function onManagedModelArchived(ManagedModelArchived $e)
    {
        if (! $e->redirectReference) {
            return;
        }

        $model = $e->modelReference->instance();
        $archivedUrlRecords = UrlRecord::getByModel($model);

        $redirectModel = $e->redirectReference->instance();
        $targetRecords = UrlRecord::getByModel($redirectModel);

        // Ok now get all urls from this model and point them to the new records
        foreach ($archivedUrlRecords as $urlRecord) {
            if ($targetRecord = $targetRecords->first(function ($record) use ($urlRecord) {
                return ($record->locale == $urlRecord->locale && ! $record->isRedirect());
            })) {
                $urlRecord->redirectTo($targetRecord);
            }
        }

        // Cast all existing records to the new owning model
        $archivedUrlRecords->each(function (UrlRecord $urlRecord) use ($redirectModel) {
            $urlRecord->changeOwningModel($redirectModel);
            $urlRecord->save();
        });
    }
}
