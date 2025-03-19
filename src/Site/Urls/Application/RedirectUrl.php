<?php

namespace Thinktomorrow\Chief\Site\Urls\Application;

use Thinktomorrow\Chief\Site\Urls\UrlRecord;

class RedirectUrl
{
    /**
     * @return int The id of the newly created redirect record
     */
    public function handle(UrlRecord $original, string|UrlRecord $target, array $values = []): int
    {
        $targetRecord = $target instanceof UrlRecord ? $target : UrlRecord::firstOrCreate(array_merge([
            'site' => $original->site,
            'status' => $original->status,
            'context_id' => $original->context_id,
            'model_type' => $original->model_type,
            'model_id' => $original->model_id,
            'slug' => $target,
        ], $values));

        // redirect the original record to the new record
        if ($original->id === $targetRecord->id) {
            throw new \InvalidArgumentException('Cannot redirect to itself. Failed to create a redirect from ['.$original->slug.'] to ['.$targetRecord->slug.']');
        }

        $original->redirect_id = $targetRecord->id;
        $original->save();

        return $targetRecord->id;
    }
}
