<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Site\Urls\Application;

use Thinktomorrow\Chief\Site\Urls\LinkStatus;
use Thinktomorrow\Chief\Site\Urls\UrlRecord;

final class UpdateUrl
{
    use WithUniqueSlug;

    /**
     * Saving urls slugs in strict mode prevents identical urls to be automatically removed.
     * When set to false, this would remove the identical url records.
     */
    public function handle(int $id, string $slug, ?string $contextId, LinkStatus $status): void
    {
        $urlRecord = UrlRecord::findOrFail($id);

        $slug = $this->composeSlug($urlRecord->model, $urlRecord->site, $slug);

        $this->assertSlugDoesNotExistsAsActiveUrl($urlRecord->site, $slug, $urlRecord->id);

        $this->force()->cleanupIdenticalSlugs($urlRecord->model, $urlRecord->site, $slug, $urlRecord->id);

        // Create redirects for the old slugs of this site... ??
        if ($urlRecord->slug != $slug) {
            app(RedirectUrl::class)->handle($urlRecord, $slug, [
                'context_id' => $contextId,
                'status' => $status->value,
            ]);

            return;
        }

        // Update the existing record
        $urlRecord->slug = $slug;
        $urlRecord->context_id = $contextId ?: null;
        $urlRecord->status = $status->value;
        $urlRecord->save();
    }
}
