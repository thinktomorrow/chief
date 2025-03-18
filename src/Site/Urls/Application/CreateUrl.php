<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Site\Urls\Application;

use Thinktomorrow\Chief\Shared\ModelReferences\ReferableModel;
use Thinktomorrow\Chief\Site\Urls\LinkStatus;
use Thinktomorrow\Chief\Site\Urls\UrlRecord;
use Thinktomorrow\Chief\Site\Visitable\Visitable;

final class CreateUrl
{
    use WithUniqueSlug;

    /**
     * Saving urls slugs in strict mode prevents identical urls to be automatically removed.
     * When set to false, this would remove the identical url records.
     */
    public function handle(Visitable&ReferableModel $model, string $site, string $slug, ?string $contextId, LinkStatus $status): void
    {
        $slug = $this->composeSlug($model, $site, $slug);

        $this->cleanupIdenticalSlugs($model, $site, $slug);

        // Get current url record for this model and site
        $existingRecord = UrlRecord::where('model_type', $model->modelReference()->shortClassName())
            ->where('model_id', $model->modelReference()->id())
            ->where('site', $site)
            ->first();

        // Redirect former active slug to this new one
        if ($existingRecord) {
            app(RedirectUrl::class)->handle($existingRecord, $slug, [
                'context_id' => $contextId,
                'status' => $status->value,
            ]);
        } else {
            $urlRecord = UrlRecord::create([
                'model_type' => $model->modelReference()->shortClassName(),
                'model_id' => $model->modelReference()->id(),
                'site' => $site,
                'slug' => $slug,
                'context_id' => $contextId ?: null,
                'status' => $status->value,
            ]);
        }
    }
}
