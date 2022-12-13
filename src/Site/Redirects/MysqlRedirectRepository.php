<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\Site\Redirects;

use Illuminate\Support\Collection;
use Thinktomorrow\Chief\Site\Urls\UrlRecord;

// TODO: WIP
final class MysqlRedirectRepository
{
    public function getAll(): Collection
    {
        $records = UrlRecord::whereNotNull('redirect_id')->get();

        return $records->map(fn (UrlRecord $record) => Redirect::fromUrlRecord($record));
    }
}
