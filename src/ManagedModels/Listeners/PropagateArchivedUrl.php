<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\ManagedModels\Listeners;

use Thinktomorrow\Chief\ManagedModels\Events\ManagedModelArchived;
use Thinktomorrow\Chief\Urls\App\Actions\Redirects\AddRedirect;
use Thinktomorrow\Chief\Urls\App\Actions\Redirects\RedirectApplication;
use Thinktomorrow\Chief\Urls\Models\UrlRecord;

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
                return $record->locale == $urlRecord->locale && ! $record->isRedirect();
            })) {
                app(RedirectApplication::class)->addRedirect(new AddRedirect((string) $urlRecord->id, $targetRecord->id));
            }
        }

        // Cast all existing records to the new owning model
        $archivedUrlRecords->each(function (UrlRecord $urlRecord) use ($redirectModel) {
            $urlRecord->changeOwningModel($redirectModel);
            $urlRecord->save();
        });
    }
}
