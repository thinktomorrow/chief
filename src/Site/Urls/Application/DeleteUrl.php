<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Site\Urls\Application;

use Thinktomorrow\Chief\Shared\ModelReferences\ModelReference;
use Thinktomorrow\Chief\Site\Urls\Events\UrlDeleted;
use Thinktomorrow\Chief\Site\Urls\UrlRecord;

final class DeleteUrl
{
    public function handle(int $id): void
    {
        $url = UrlRecord::findOrFail($id);

        $url->delete();

        event(new UrlDeleted(
            $url->id,
            $url->slug,
            $url->site,
            ModelReference::make($url->model_type, $url->model_id)
        ));

    }
}
